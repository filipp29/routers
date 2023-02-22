<?php

ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);


$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //”казываем корневую папку (нужно, только если работаем с консольным скриптом

require_once $_SERVER['DOCUMENT_ROOT']. "/_modules/routers/php/RouterBase.php";
require_once $_SERVER['DOCUMENT_ROOT']. "/_modules/routers/php/RouterState.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterAuth.php';

date_default_timezone_set("Asia/Almaty");




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

$mac = readline("MAC: ");
$mac = \RouterBase::getValidMac($mac);
if (strlen($mac) != 12){
    echo "Wrong MAC ". $params[2]. "\n";
    exit;
}
$mac = \RouterBase::getMacName($mac);
if (objCheckExist("/routers/byMac/". $mac. "/cstatus.info", "raw")){
    echo "Mac exists 1 \n";
    exit;
}
$data["dnum"] = $params[3];
$comment = $params[5];
try {
//        echo microtime(). " 1\n";
//        $stateList = \RouterAuth::getIntstall($mac);
        $stateList = \RouterAuth::getIntstall($mac);
//        echo microtime(). " 1\n";
        usort($stateList,srt);
        
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
            $router->setNegDays("0");
            unset($data,$state);
        }
        unset($stateList);
    
    }
    catch (\Exception $e){
        echo $e->getMessage(). "  MAC: {$mac}\n";
    }