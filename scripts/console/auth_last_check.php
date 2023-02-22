<?php



ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterAuth.php';

date_default_timezone_set("Asia/Almaty");











$result = "none";
$type = readline("Type: ");
if ($type == "mac"){
    $mac = readline("MAC: ");
    $result = \RouterAuth::getLastByMac($mac);
}
else if ($type == "dnum"){
    $dnum = readline("Dnum: ");
    $result = \RouterAuth::getLastByDnum($dnum);
}
else if ($type == "both"){
    $dnum = readline("Dnum: ");
    $mac = readline("MAC: ");
    $result = \RouterAuth::getLastByBoth($mac, $dnum);
    
}

if ($result["time"]){
    $result["time"] = date("Y-m-d H:i:s",$result["time"]);
}
print_r($result);


