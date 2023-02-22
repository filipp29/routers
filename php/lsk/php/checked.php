<?php



$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php');



$dnum = $_GET["dnum"];

$list = objLoad("/routers/lsk/dnumlist.info");
unset($list["#e"]);
foreach($list as $key => $value){
    $buf = explode("&", $value);
    if ($buf[0] == $dnum){
        $list[$key] = "{$buf[0]}&1&0";
        break;
    }
}

objSave("/routers/lsk/dnumlist.info", "raw", $list);







