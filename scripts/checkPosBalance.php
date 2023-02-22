<?php

ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterTicket.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';


/*--------------------------------------------------*/


function checkInstalled(){

    $macList = \RouterBase::getMacList("installed");
    foreach ($macList as $key => $mac){

        $router = new \RouterBase($mac);
        $dnum = $router->getCStatus("dnum");
        if (!\RouterBase::checkNegative($dnum)){
            $router->setNegDays(0);
            $end = $router->getCurState();
            $ticket = new \RouterTicket($mac, "inWork");
            if ($ticket->isOpened()){
                $ticket->addComment("Пополнили баланс", "SYSTEM");
                $ticket->closeTicket($end);
                echo "Router {$mac} closed ticket inWork\n";
            }
        }
    }

}



/*--------------------------------------------------*/



function checkInTaking(){

    $macList = \RouterBase::getMacList("inTaking");
    foreach ($macList as $key => $mac){
        $router = new \RouterBase($mac);
        $params = $router->getCStatus();
        $dnum = $router->getCStatus("dnum");
        $ticket = new \RouterTicket($mac, "inTaking");
        if (!\RouterBase::checkNegative($dnum)){
            $state = new \RouterState("installed", $params);
            $ticket->addComment("Пополнили баланс", "SYSTEM");
            $router->addState($state);
            echo "Router {$mac} changed status to installed\n";
        }
    }

}


/*--------------------------------------------------*/
/*--------------------------------------------------*/
/*--------------------------------------------------*/



checkInTaking();

checkInstalled();