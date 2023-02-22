<?php

//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once './RouterBase.php';
require_once './Decoder.php';




$mac = $_GET["mac"];
if ($mac){
    $mac = \RouterBase::getValidMac($mac);
    $mac = \RouterBase::getMacName($mac);
}
else{
    $mac = "";
}

if (!objCheckExist("/routers/byMac/". $mac. "/cstatus.info", $type)){
    $checked = false;
    $checkHide = "";
}
else{
    $checked = true;
    $checkHide = 'value="'. $mac. '" disabled';
}


require '../html_templates/ticketCommentAddForm/main_header.php';

if (!$checked){
    if ($mac){
        echo "MAC адрес не найден<br><br>";
    }
    require '../html_templates/ticketCommentAddForm/check_button.php';
}
else{
    require '../html_templates/ticketCommentAddForm/comment.php';
}

require '../html_templates/ticketCommentAddForm/main_footer.php';





























