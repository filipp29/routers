<?php

//error_reporting(E_ALL); //�������� ����� ������
//set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
//ini_set('display_errors', 1); //��� ���� ����� ������
//ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
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
        echo "MAC ����� �� ������<br><br>";
    }
    $view->show("check_button", $data);
}
else{
    $view->show("type", $data);
}
$view->show("main_footer", $data);








