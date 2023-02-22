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
$type = $_GET["type"];
$dnum = $_GET["dnum"];
$mac = $_GET["mac"];

if ($type == "last"){
    $result = \RouterAuth::getLastByBoth($mac, $dnum);
}
else
if ($type == "first"){
    $result = \RouterAuth::getFirstByBoth($mac, $dnum);
}



if ($result["time"]){
    $result["time"] = date("Y-m-d H:i:s",$result["time"]);
}
echo "MAC:          {$mac}<br><br>";
echo "Login:        {$dnum}<br><br>";
echo "Type:         {$type}<br><br>";

echo "--------------------<br>";
foreach($result as $key => $value){
    echo "[ {$key} ] :  {$value} <br>";
}
echo "--------------------<br>";


