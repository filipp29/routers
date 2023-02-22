<?php


//error_reporting(E_ALL); //�������� ����� ������
//set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
//ini_set('display_errors', 1); //��� ���� ����� ������
//ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)


$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterAuth.php');


$dnum = $_GET["dnum"];
try{
$authList = \RouterAuth::getByDnum($dnum, 100);
$i = 0;
foreach($authList as $auth){
    $i++;
    echo "<tr>";
    echo "<td>";
    echo date("d.m.Y H:i:s",$auth["time"]);
    echo "</td>";
    echo "<td>";
    echo $auth["mac"];
    echo "</td>";
    echo "<td>";
    echo "<button onclick='linkRouter(`{$auth['mac']}`)'>...</button>";
    echo "</td>";
    echo "</tr>";
    if ($i > 20){
        break;
    }
}
}
catch(\Exception $ex){
    echo $ex->getMessage();
}












