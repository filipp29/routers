<?php

ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('memory_limit', '512M'); //”станавливаем ограничение пам€ти на скрипт (512ћб)
set_time_limit(600);

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //”казываем корневую папку (нужно, только если работаем с консольным скриптом

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

$macList = [];
$dnumList = [];
$wrongMac = [];
$emptyDnum = [];
$noAuth = [];

while($str = fgets($file)){
    $i++;
    if ($i < $n-10){
//        continue;
    }
    $params = explode(';',$str);
    
    foreach($params as $key => $value){
        $params[$key] = substr($value, 1, strlen($value)-2);
    }
    $mac = \RouterBase::getValidMac($params[2]);
    
    if (strlen($mac) != 12){
        $wrongMac[] = $mac;
        continue;
    }
    $dnum = $params[3];
    $mac = \RouterBase::getMacName($mac);
    if (objCheckExist("/routers/byMac/". $mac. "/cstatus.info", "raw")){
        continue;
    }
    $data["dnum"] = $params[3];
    $comment = iconv("UTF-8", "cp1251", $params[5]); 
    try {
        $macAuth = \RouterAuth::getLastByMac($mac);
//        echo "Last by Mac - {$macAuth['login']}";
        $dnumAuth = \RouterAuth::getLastByDnum($dnum);
        
        if ((trim($macAuth["login"]) != trim($dnum))){
            $macList[] = [
                "mac" => $mac,
                "dnum" => $dnum,
            ];
//            echo "Dnum incorrect [{$macAuth["login"]}] - [{$dnum}]\n";
            continue;
        }
        if (($dnumAuth["mac"] != $mac)){
            $dnumList[] = [
                "dnum" => $dnum,
                "mac" => $mac,
            ];
//            echo "Mac incorrect [{$dnumAuth['mac']}] - [{$mac}]\n";
            continue;
        }
        
    
    }
    catch (\Exception $e){
//        echo $e->getMessage(). "  MAC: {$mac}\n";
        if ($e->getMessage() == "Auth not found"){
            $noAuth[] = [
                "mac" => $mac,
                "dnum" => $dnum
            ];
        }
    }
    $wrongMacCount = count($wrongMac);
    $macCount = count($macList);
    $dnumCount = count($dnumList);
    $emptyCount = count($emptyDnum);
    $noAuthCount = count($noAuth);
    
    $str = ""
            . "Incorrect mac  - {$macCount}\n"
            . "Incorrect dnum - {$dnumCount}\n"
            . "Wrong mac      - {$wrongMacCount}\n"
            . "Empty dnum     - {$emptyCount}\n"
            . "No auth        - {$noAuthCount}\n";
    echo $str;
    unset($params,$data);
}


$i = 0;
foreach($macList as $value){
    $result = [];
    $result["mac"] = $value["mac"];
    $result["dnum"] = $value["dnum"];
    $result["type"] = "mac";
    objSave("/routers/temp/{$i}.info", "raw", $result);
    $i++;
}
foreach($dnumList as $value){
    $result = [];
    $result["mac"] = $value["mac"];
    $result["dnum"] = $value["dnum"];
    $result["type"] = "dnum";
    objSave("/routers/temp/{$i}.info", "raw", $result);
    $i++;
}
foreach($noAuth as $value){
    $result = [];
    $result["mac"] = $value["mac"];
    $result["dnum"] = $value["dnum"];
    $result["type"] = "auth";
    objSave("/routers/temp/{$i}.info", "raw", $result);
    $i++;
}

echo "Complete\n";
