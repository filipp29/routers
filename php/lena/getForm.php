<?php
error_reporting(E_ALL); //�������� ����� ������
set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
ini_set('display_errors', 1); //��� ���� ����� ������
ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/View.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterAuth.php';



$path = "/routers/temp/";

$number = $_GET["number"];

$obj = objLoad($path. "{$number}.info");
$dec = new Decoder();
echo  $dec->arrayToStr($obj);








