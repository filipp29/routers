<?php



$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php');



$br = array_keys(objLoadBranch("/users/", false, true));
$result = [];
$i = 0;
foreach($br as $dnum){
    $obj = objLoad("/users/{$dnum}/user.vcard");
    if (preg_match("/Лисаковск/", $obj["address"])){
        $result[] = "{$dnum}&0&0";
    }
}

objSave("/routers/lsk/dnumlist.info", "raw", $result);
echo "!!!!!!!!!!!";










