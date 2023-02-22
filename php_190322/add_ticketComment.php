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



$str = $_GET["data"];

$dec = new \Decoder();
$data = $dec->strToArray($str);

$mac = $data["mac"];
$type = $data["type"];
$author = $data["author"];
$text = $data["text"];
$opened = true;
try{
    $ticket = new \RouterTicket($mac, $type);
    
    if ($ticket->isOpened()){
        $ticket->addComment($text, $author);
    }
    else{
        $opened = false;
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}

if ($opened){
    echo "����������� � ������ ��������";
}
else{
    echo "��� ��������� ������";
}



















