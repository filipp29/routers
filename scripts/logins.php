<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once '../php/RouterBase.php';
require_once '../php/RouterSearch.php';
require_once '../php/Decoder.php';
require_once '../php/View.php';
require_once '../php/RouterState.php';
require_once '../php/Log.php';


//error_reporting(E_ALL); //�������� ����� ������
//set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
//ini_set('display_errors', 1); //��� ���� ����� ������
//ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)


date_default_timezone_set("Asia/Almaty");

$macList = array_keys(objLoadBranch("/routers/byMac/", false, true));
foreach($macList as $value){
    $router = new \RouterBase($value);
    if ($router->getCStatus("dnum")){
        echo $value. "\n";
        $dnum = $router->getCStatus("dnum");
        $path = "/routers/byLogin/".$dnum."/user.info";
        $data["mac"] = $value;
        objSave($path, "raw", $data);
        echo $path. " - ". $value. "\n";
    }
    
}


















