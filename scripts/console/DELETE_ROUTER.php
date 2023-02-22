<?php
    $_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
    ini_set('error_reporting', E_ERROR);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 0);
    ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)
    set_time_limit(600);
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libSwitches.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libMsg.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libCity.php');
    require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
    while(true){
        try{
            $mac = readline("MAC: ");
            $router = new \RouterBase($mac);
            $router->remove(false);
            $ans = readline("Are you sure? :(y/n)");
            if ($ans == "y"){
                $router->remove(true);
            }
        }
        catch(\Exception $ex){
            echo $ex->getMessage(). "\n";
        }
    }