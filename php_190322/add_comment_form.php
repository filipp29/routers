<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once './RouterBase.php';
require_once './Decoder.php';
require_once './View.php';


$mac = $_GET["mac"];
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


$view = new \View("/_modules/routers/html_templates/commentAddForm/");
$data = compact("checkHide","mac");
$view->show("main_header", $data);
if (!$checked){
    if ($mac){
        echo "MAC адрес не найден<br><br>";
    }
    $view->show("check_button", $data);
}
else{
    $view->show("comment", $data);
}
$view->show("main_footer", $data);












