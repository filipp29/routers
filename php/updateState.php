<?php



error_reporting(E_ALL); //�������� ����� ������
set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
ini_set('display_errors', 1); //��� ���� ����� ������
ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once $_SERVER['DOCUMENT_ROOT']. '/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/_modules/routers/php/Decoder.php';

$mac = $_GET["mac"];

$router = new \RouterBase($mac);

$state = $router->getCurState();
if ($state->getState() == "atServEng"){
    $state->setTimeStamp(time());
    $router->addState($state);
    echo "OK";
}
else{
    echo "Error wrong state ". $state->getState();
}






















