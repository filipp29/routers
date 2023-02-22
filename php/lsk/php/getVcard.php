<?php


$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php');


$dnum = $_GET["dnum"];
$result = [];
$keyList = [
    "uname",
    "address",
    "balance",
    "rate",
    "dnum"
];
$obj = objLoad("/users/{$dnum}/user.vcard");
foreach($keyList as $key){
    $result[$key] = $obj[$key];
}
$result["comment"] = objLoad("/users/{$dnum}/user.cmnt")["comment"];
$dec = new \Decoder();
$str = $dec->arrayToStr($result);
echo $str;




