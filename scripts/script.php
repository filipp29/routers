<?php

ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('memory_limit', '512M'); //”станавливаем ограничение пам€ти на скрипт (512ћб)
set_time_limit(600);
if (!$_GET["password"] || ($_GET["password"] != "moduleRouters")){
    exit();
}
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //”казываем корневую папку (нужно, только если работаем с консольным скриптом

require_once $_SERVER['DOCUMENT_ROOT']. "/_modules/routers/php/RouterBase.php";
require_once $_SERVER['DOCUMENT_ROOT']. "/_modules/routers/php/RouterState.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterAuth.php';

date_default_timezone_set("Asia/Almaty");


function checkChangeDnum(
        $mac
){
    $i = 0;
    
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

    $i++;
    if ($i % 50 ==0){
        echo $i. "\n";
    }
        
    
}

$mac = "30:B5:C2:68:C1:E5";
checkChangeDnum($mac);








