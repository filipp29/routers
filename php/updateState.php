<?php



error_reporting(E_ALL); //Включаем вывод ошибок
set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
ini_set('display_errors', 1); //Еще один вывод ошибок
ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT']. '/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/_modules/routers/php/Decoder.php';

$mac = $_GET["mac"];

$router = new \RouterBase($mac);

$state = $router->getCurState();
if ($state->getState() == "atServEng"){
    $state->setTimeStamp(time());
    $router->addState($state);
    echo "OK";
}
else{
    echo "Error wrong state ". $state->getState();
}






















