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
    $showType = readline("Show auth type?(yes / no): ");
    $showMac = readline("Show only time?(yes/no): ");
    if ($showType == "yes"){
        $showType = false;
    }
    else {
        $showType = true;
    }
    if ($showMac == "no"){
        $showMac = false;
    }
    else {
        $showMac = true;
    }
    $result = \RouterAuth::getAllByMac($mac,$showType);
    print_r($result);
    echo "\n\n\n----{$result[0]["login"]}------{$result[0]["authtype"]}---------------------------------------------------------------------------------\n";
    $prev = $result[0]["login"];
    for($key = 0; $key < count($result); $key++){
        $value = $result[$key];
        if ($value["time"]){
            $value["time"] = date("Y-m-d H:i:s",$value["time"]);
        }
        if ($value["login"] !== $prev){
            echo "\n\n----{$result[$key]["login"]}----{$result[$key]["authtype"]}-----------------------------\n";
            $prev = $value["login"];
        }
        if ($showMac){
            echo $value["time"];
            if (!$showType){
                echo " -- ".$result[$key]["authtype"]. "\n";
            }
            else{
                echo "\n";
            }
        }
        else {
            print_r($value);
        }

    }
    echo "-------------------------------------------------------------------------------------------\n\n\n";
    
    
}
else if ($type == "dnum"){
    $dnum = readline("Dnum: ");
    $showType = readline("Show auth type?(yes / no): ");
    $showMac = readline("Show only time?(yes/no): ");
    if ($showType == "yes"){
        $showType = false;
    }
    else {
        $showType = true;
    }
    if ($showMac == "no"){
        $showMac = false;
    }
    else {
        $showMac = true;
    }
    
    $result = \RouterAuth::getAllByDnum($dnum,$showType);
    
    echo "\n\n\n----{$result[0]["mac"]}----{$result[0]["authtype"]}-----------------------------------------------------------------------------------\n";
    $prev = $result[0]["mac"];
    for($key = 0; $key < count($result); $key++){
        $value = $result[$key];
        if ($value["time"]){
            $value["time"] = date("Y-m-d H:i:s",$value["time"]);
        }
        if ($value["mac"] !== $prev){
            echo "\n\n----{$result[$key]["mac"]}----{$result[$key]["authtype"]}-----------------------------\n";
            $prev = $value["mac"];
        }
        if ($showMac){
            echo $value["time"];
            if (!$showType){
                echo " -- ".$result[$key]["authtype"]. "\n";
            }
            else{
                echo "\n";
            }
        }
        else {
            print_r($value);
        }

    }
    echo "-------------------------------------------------------------------------------------------\n\n\n";
    
}




