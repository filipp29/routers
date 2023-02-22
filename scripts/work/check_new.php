<?php

ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);


$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterTicket.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterAuth.php';
ini_set('memory_limit', '1024M'); //Устанавливаем ограничение памяти на скрипт (512Мб)




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


function srtStateList(
        $a,
        $b
){
    return (int)$b->getTimeStamp - (int)$a->getTimeStamp;
}



/*--------------------------------------------------*/

function check(
        $mac
){
    try {
//        echo microtime(). " 1\n";
//        $stateList = \RouterAuth::getIntstall($mac);
        
        
        $router = new \RouterBase($mac);
        
        $curStateList = $router->getAllStates();
        usort($curStateList, srtStateList);
        $timeStamp = $curStateList[0]->getTimeStamp();
        
        
        
        $stateList = \RouterAuth::getSomeIntstall($mac,(int)$timeStamp - 10);
        usort($stateList,srt);
        
        
        $store = [
            "timeStamp" => (int)$timeStamp - 10,
            "state" => "store",
            "store" => "store",
            "storeMan" => "storeMan",
            "author" => "SYSTEM"
        ];
        
        $stateList[] = $store;
        
        
        
        foreach($curStateList as $state){
            $params = $state->getParams();
            $params["timeStamp"] = $state->getTimeStamp();
            $params["state"] = $state->getState();
            $stateList[] = $params;
        }
        
        $router->deleteAllStates();
        
        
        
        for ($j = 0; $j < count($stateList); $j++){
            $data = $stateList[$j];
            $status = $data["state"];
            unset($data["state"]);
            $timeStamp = $data["timeStamp"];
            unset($data["timeStamp"]);
            $state = new \RouterState($status,$data);
            $state->setTimeStamp($timeStamp);
            if (!objCheckExist("/routers/byMac/". $mac. "/cstatus.info", "raw")){
                echo "{$mac} !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
            }
            if ($j == 0){
                $router->addState($state,true);
            }
            else{
                $router = new \RouterBase($mac);
                $router->addState($state);
            }
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
        echo $e->getMessage(). "  MAC: {$mac}\n {$j}";
    }
}



/*--------------------------------------------------*/

$time = time() - 60*60*24;
$params["start"] = $time;

$params["end"] = time()+(60*60*16);
//$params["state"] = $_GET["state"];
$params["state"] = ["installed", "inTaking","atServEng","store","toStore"];
/*--------------------------------------------------*/
$data = [];
$dailyReport = new \RouterReports("stateJournal", $params);
$result = $dailyReport->getReport();
foreach($result as $type => $dateList){
    foreach($dateList as $date => $macList){
        foreach ($macList as  $mac => $stateList){
            $router = new \RouterBase($mac);
            
//            if ($router->getCStatus("city") != "lsk"){
//                continue;
//            }
            
//            if ($mac != "D8:32:14:71:EC:68"){
//                continue;
//            }
            
            rsort($stateList);
            $state = reset($stateList);
            if (!$router->isChecked()){
//                  echo $mac."\n";
                check($mac);
                $router->setChecked();
            }
            
                
        }
    }
}

















