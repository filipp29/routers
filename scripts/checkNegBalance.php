<?php

ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterTicket.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';


/*--------------------------------------------------*/


function checkBalance(
        $state
){
    $i = 0;
    $macList = \RouterBase::getMacList($state);
    foreach ($macList as $key => $mac){
        
        $router = new \RouterBase($mac);
        $dnum = $router->getCStatus("dnum");
        $path = "/routers/byMac/". $mac. "/lastcheck.info";
        $timeStamp = objLoad($path, "raw")["timeStamp"];
        $lastCheck = (int)date("Ymd", (int)$timeStamp);
        $curDate = (int)date("Ymd");
        if ((\RouterBase::checkNegative($dnum) && ($curDate > $lastCheck))){
            $neg = (int)$router->getNegDays();
            $neg++;
            $router->setNegDays($neg);
            if ($neg > 14){
                $start = $router->getCurState();
                $ticket = new \RouterTicket($mac, "inWork");
                if (!$ticket->isOpened()){
                    $ticket->openTicket($start);
                    $ticket->addComment("Отрицательный баланс более двух недель.", "SYSTEM");
                    echo "Router {$mac} opened ticket inWork \n";
                }
                
            }
            echo "Router {$mac} negDays incrised to {$neg}\n";
            
        }
        
//        $i++;
//        if ($i % 30 ==0){
//            echo $i. "\n";
//        }
        
        else if ($curDate <= $lastCheck){
//            echo "Checked \n";
        }
        objSave($path, "raw", ["timeStamp" => time()]);
    }

}







/*--------------------------------------------------*/


//for ($i = 0; $i < 14; $i++){

checkBalance("installed");
checkBalance("inTaking");
//}











