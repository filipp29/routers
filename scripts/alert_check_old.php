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



/*--------------------------------------------------*/


function checkChangeDnum(
        $state
){
    $i = 0;
    $macList = \RouterBase::getMacList($state);
    
    foreach($macList as $mac){
        try{
            $router = new \RouterBase($mac);
            $dnum = $router->getCStatus("dnum");
            $byMac = \RouterAuth::getLastByMac($mac);
            if ($byMac["login"] == $dnum){
//                echo "Router {$mac} is OK\n";
            }
            else{
                $text = $dnum. " -> ".$byMac["login"];
                echo $mac. " - ". $text. "\n";
                $router->addAlert("changeDnum", $text, $byMac["time"]);
            }
        }
        catch (\Exception $e){
            echo $e->getMessage(). "\n";
        }
        
//        $i++;
//        if ($i % 50 ==0){
//            echo $i. "\n";
//        }
        
    }
    
}


/*--------------------------------------------------*/


function checkChangeRouter(
        $state
){
    $i = 0;
    $macList = \RouterBase::getMacList($state);
    
    foreach($macList as $mac){
        try{
            $router = new \RouterBase($mac);
            $dnum = $router->getCStatus("dnum");
            $byMac = \RouterAuth::getLastByMac($mac);
            $byDnum = \RouterAuth::getLastByDnum($dnum);
            if ($byDnum["mac"] != $byMac["mac"]){
                $day = floor(((int)$byDnum["time"] - (int)$byMac["time"])/86400);
                if (abs($day) > 14){
                    $text = $byMac["mac"]. " -> ".$byDnum["mac"]; 
                    echo $dnum. " - ". $text. "\n";
                    $router->addAlert("changeRouter", $text, $byDnum["time"]);
                }
            }
        }
        catch (\Exception $e){
            echo $e->getMessage(). "\n";
        }
        
//        $i++;
//        if ($i % 50 ==0){
//            echo $i. "\n";
//        }
    }
}


/*--------------------------------------------------*/


$dnum = array_keys(\RouterAlert::getText()["changeDnum"]);
$router = array_keys(\RouterAlert::getText()["changeRouter"]);


foreach($dnum as $value){
    checkChangeDnum($value);
}

foreach ($router as $value){
    checkChangeRouter($value);
}

















