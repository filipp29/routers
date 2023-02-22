<?php

//error_reporting(E_ALL); //�������� ����� ������
//set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
//ini_set('display_errors', 1); //��� ���� ����� ������
//ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
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
        echo "MAC ����� �� ������<br><br>";
    }
    require '../html_templates/ticketCommentAddForm/check_button.php';
}
else{
    require '../html_templates/ticketCommentAddForm/comment.php';
}

require '../html_templates/ticketCommentAddForm/main_footer.php';





























