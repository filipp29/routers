<?php
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
if (!$_GET["password"] || ($_GET["password"] != "moduleRouters")){
    exit();
}
$path = [];

$path[0] = "/routers/byMac/";
$path[1] = "/routers/list/";
$path[2] = "/routers/reports/";
$path[3] = "/routers/byLogin/";

foreach($path as $value){
    $br = array_keys(objLoadBranch($value, false, true));
    foreach($br as $v){
        objUnlinkBranch($value. $v);
    }
}
















