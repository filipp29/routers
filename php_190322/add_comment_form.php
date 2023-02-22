<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
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
        echo "MAC ����� �� ������<br><br>";
    }
    $view->show("check_button", $data);
}
else{
    $view->show("comment", $data);
}
$view->show("main_footer", $data);












