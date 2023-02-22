<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once '../php/RouterBase.php';
require_once '../php/RouterSearch.php';
require_once '../php/Decoder.php';
require_once '../php/View.php';
require_once '../php/RouterState.php';
require_once '../php/Log.php';


//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)


date_default_timezone_set("Asia/Almaty");

$macList = array_keys(objLoadBranch("/routers/byMac/", false, true));
foreach($macList as $value){
    $router = new \RouterBase($value);
    if ($router->getCStatus("dnum")){
        echo $value. "\n";
        $dnum = $router->getCStatus("dnum");
        $path = "/routers/byLogin/".$dnum."/user.info";
        $data["mac"] = $value;
        objSave($path, "raw", $data);
        echo $path. " - ". $value. "\n";
    }
    
}


















