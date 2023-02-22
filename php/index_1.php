<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterSearch.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/View.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterReports.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterAuth.php';

error_reporting(E_ALL); //Включаем вывод ошибок
set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
ini_set('display_errors', 1); //Еще один вывод ошибок
ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)


$mac = $_GET["mac"];

try{
    $router = new \RouterBase($mac);
    $dnum = $router->getCStatus("dnum");
    $byMac = \RouterAuth::getLastByMac($mac);
    if ($byMac["login"] == $dnum){
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











