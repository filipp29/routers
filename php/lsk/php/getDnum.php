<?php



$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php');

$list = objLoad("/routers/lsk/dnumlist.info");
unset($list["#e"]);
foreach($list as $key => $value){
    $buf = explode("&", $value);
    if ($buf[1] == "0"){
        if (time() - (int)$buf[2] > 300){
            $dnum = $buf[0];
            $list[$key] = "{$buf[0]}&0&".time();
            break;
        }
    }
}

echo $dnum;
objSave("/routers/lsk/dnumlist.info", "raw", $list);







