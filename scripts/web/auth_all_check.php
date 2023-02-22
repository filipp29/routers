<?php



ini_set('error_reporting', E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterAuth.php';

date_default_timezone_set("Asia/Almaty");











$result = "none";
$type = $_GET["type"];
echo $_GET["value"]. "<br>";
if ($type == "mac"){
    $mac = $_GET["value"];
    $showType = ($_GET["showType"] == null);
   
    $showMac = ($_GET["showMac"] != null);
//    if ($showType == "on"){
//        $showType = false;
//    }
//    else {
//        $showType = true;
//    }
//    if ($showMac == "off"){
//        $showMac = false;
//    }
//    else {
//        $showMac = true;
//    }
    $mac = \RouterBase::getValidMac($mac);
    $mac = RouterBase::getMacName($mac);
    $result = \RouterAuth::getAllByMac($mac,$showType);
    echo "<br><br>----{$result[0]["login"]}------{$result[0]["authtype"]}-----------------------------<br>";
    $prev = $result[0]["login"];
    for($key = 0; $key < count($result); $key++){
        $value = $result[$key];
        if ($value["time"]){
            $value["time"] = date("Y-m-d H:i:s",$value["time"]);
        }
        if ($value["login"] !== $prev){
            echo "<br><br>------{$result[$key]["login"]}----{$result[$key]["authtype"]}-----------------------------<br>";
            $prev = $value["login"];
        }
        if ($showMac){
            echo $value["time"];
            if (!$showType){
                echo " -- ".$result[$key]["authtype"]. "<br>";
            }
            else{
                echo "<br>";
            }
        }
        else {
            echo "--------------------<br>";
            foreach($value as $k => $v){
                echo "[{$k}] : [{$v}]<br>";
            }
            echo "--------------------<br>";
        }

    }
    echo "-------------------------------------------------------------------------------------------------------<br>";
    
    
}
else if ($type == "dnum"){
    $dnum = $_GET["value"];
    $showType = ($_GET["showType"] == null);
   
    $showMac = ($_GET["showMac"] != null);
//    if ($showType == "on"){
//        $showType = false;
//    }
//    else {
//        $showType = true;
//    }
//    if ($showMac == "off"){
//        $showMac = false;
//    }
//    else {
//        $showMac = true;
//    }
    
    $result = \RouterAuth::getAllByDnum($dnum,$showType);
    
    echo "<br><br>----{$result[0]["mac"]}----{$result[0]["authtype"]}-----------------------------<br>";
    $prev = $result[0]["mac"];
    for($key = 0; $key < count($result); $key++){
        $value = $result[$key];
        if ($value["time"]){
            $value["time"] = date("Y-m-d H:i:s",$value["time"]);
        }
        if ($value["mac"] !== $prev){
            echo "<br><br>----{$result[$key]["mac"]}----{$result[$key]["authtype"]}-----------------------------<br>";
            $prev = $value["mac"];
        }
        if ($showMac){
            echo $value["time"];
            if (!$showType){
                echo " -- ".$result[$key]["authtype"]. "<br>";
            }
            else{
                echo "<br>";
            }
        }
        else {
            echo "--------------------<br>";
            foreach($value as $k => $v){
                echo "[{$k}] : [{$v}]<br>";
            }
            echo "--------------------<br>";
        }

    }
    echo "-------------------------------------------------------------------------------------------<br>";
    
}




