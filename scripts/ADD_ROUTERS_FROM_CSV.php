<?php

ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('memory_limit', '512M'); //”станавливаем ограничение пам€ти на скрипт (512ћб)
set_time_limit(600);

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //”казываем корневую папку (нужно, только если работаем с консольным скриптом
if (!$_GET["password"] || ($_GET["password"] != "moduleRouters")){
    exit();
}
require_once $_SERVER['DOCUMENT_ROOT']. "/_modules/routers/php/RouterBase.php";
require_once $_SERVER['DOCUMENT_ROOT']. "/_modules/routers/php/RouterState.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterAuth.php';

date_default_timezone_set("Asia/Almaty");




/*--------------------------------------------------*/


function getMinusDays(
        $dnum
){
    if (objCheckExist('/finance/negative/'.$dnum.'.raw', 'raw')){
        $obj=objLoad('/finance/negative/'.$dnum.'.raw', 'raw');
        if (isset($obj["tstamp"])){
            $secs=time()-($obj["tstamp"]);
            return (floor($secs/(60*60*24)));
        }
    }
    return 0;
}


/*--------------------------------------------------*/

function srt(
        $a,
        $b
){
    if ((int)$a["timeStamp"] == (int)$b["timeStamp"]) {
        return 0;
    }
    return ((int)$a["timeStamp"] < (int)$b["timeStamp"]) ? -1 : 1;
}


/*--------------------------------------------------*/


$path = $_SERVER['DOCUMENT_ROOT']. "/_FDB/routers/routers.csv";
$file = fopen($path, "r");
$n = objLoad("/routers/str.count")["count"];
$i = 0;
$timeStart = microtime(true);
while($str = fgets($file)){
    $i++;
    if ($i < $n-10){
//        continue;
    }
    echo $i. "-------------------------------------------------\n";
    $timeLocal = microtime(true);
    $params = explode(';',$str);
    
    foreach($params as $key => $value){
        $params[$key] = substr($value, 1, strlen($value)-2);
    }
    $mac = \RouterBase::getValidMac($params[2]);
    
    if (strlen($mac) != 12){
        echo "Wrong MAC ". $params[2]. "\n";
        continue;
    }
    $dnum = $params[3];
    $mac = \RouterBase::getMacName($mac);
    if (objCheckExist("/routers/byMac/". $mac. "/cstatus.info", "raw")){
        echo "Mac exists \n";
        continue;
    }
    $data["dnum"] = $params[3];
    $comment = iconv("UTF-8", "cp1251", $params[5]); 
    try {
//        echo microtime(). " 1\n";
//        $stateList = \RouterAuth::getIntstall($mac);
        $stateList = \RouterAuth::getIntstall($mac);
        $dnumAuth = \RouterAuth::getLastByDnum($dnum);
//        echo microtime(). " 1\n";
        usort($stateList,srt);
        
        if ((trim($stateList[count($stateList) - 1]["dnum"]) != trim($dnum))){
            echo "Dnum incorrect [{$stateList[count($stateList) - 1]["dnum"]}] - [{$dnum}]\n";
            continue;
        }
        if (($dnumAuth["mac"] != $mac)){
            echo "Mac incorrect [{$dnumAuth['mac']}] - [{$mac}]\n";
            continue;
        }
        for ($j = 0; $j < count($stateList); $j++){
            $data = $stateList[$j];
            $status = $data["state"];
            unset($data["state"]);
            $timeStamp = $data["timeStamp"];
            unset($data["timeStamp"]);
            $state = new \RouterState($status,$data);
            $state->setTimeStamp($timeStamp);
            if (!objCheckExist("/routers/byMac/". $mac. "/cstatus.info", "raw")){
                \RouterBase::addRouter($mac, $state);
                $router = new \RouterBase($mac);
            }
            else{
                $router = new \RouterBase($mac);
                $router->addState($state);
            }
            $router->setCity("kst");
            if (isset($data["dnum"])){
                $router->setNegDays(getMinusDays($data["dnum"]));
            }
            else{
                $router->setNegDays("0");
            }
            if ($comment){
                $router->addComment($comment, "_system", (int)$timeStamp+2);
            }
            unset($data,$state);
        }
        unset($stateList);
    
    }
    catch (\Exception $e){
        echo $e->getMessage(). "  MAC: {$mac}\n";
        continue;
    }
    
    
    
//    echo (memory_get_usage() / 1024). "\n";
    echo ((microtime(true) - $timeLocal)). " local end sec\n";
    echo "Router #{$i} MAC: {$mac} added \n";
    echo ((microtime(true) - $timeStart)). " end sec\n";
    if ((microtime(true) - $timeStart) > 20){
        objSave("/routers/str.count", "raw", ["count" => $i]);
        exit("------------------------------------------------------");
    }
    unset($params,$data);
}


