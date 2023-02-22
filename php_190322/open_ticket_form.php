<?php

//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once './RouterBase.php';
require_once './Decoder.php';
require_once './RouterTicket.php';
require_once './View.php';







$mac = $_GET["mac"];
$mac = \RouterBase::getValidMac($mac);

if ($mac){
    $mac = \RouterBase::getValidMac($mac);
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


$view = new \View("/_modules/routers/html_templates/openTicketForm/");
$data = compact("checkHide","mac");
$view->show("main_header", $data);
if (!$checked){
    if ($mac){
        echo "MAC адрес не найден<br><br>";
    }
    $view->show("check_button", $data);
}
else{
    $view->show("type", $data);
}
$view->show("main_footer", $data);








