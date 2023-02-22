<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once './RouterBase.php';
require_once './RouterSearch.php';
require_once './Decoder.php';


//error_reporting(E_ALL); //�������� ����� ������
//set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
//ini_set('display_errors', 1); //��� ���� ����� ������
//ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)






$dec = new \Decoder();
$str = $_GET["data"];

$data = $dec->strToArray($str);
$states = [
    "inTaking",
    "installed"
];
$city = ["", null];
$search = new \RouterSearch($data["params"], $states, $city,$ticket);
$buf = $search->search();
$minDays = $data["minDays"];
$maxDays = $data["maxDays"];
$result["installed"] = [];
$result["inTaking"] = [];
foreach($buf as $key => $value){
    $negDays = (int)$value["negDays"];
    if (($negDays >= $minDays) && ($negDays <= $maxDays)){
        $router = new \RouterBase($value["mac"]);
        $value["comment"] = $router->getComment(0);
        $result = $value;
    }
}

$res = $dec->arrayToStr($result);
echo $res;





































