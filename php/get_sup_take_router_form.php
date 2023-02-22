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

$dnum = $_GET["dnum"];
//$dnum = readline("dnum: ");
$authList = \RouterAuth::getByDnum($dnum, 7);
$macList = [];
foreach($authList as $value){
    $mac = $value["mac"];
    if (!in_array($mac, $macList)){
        $macList[] = $mac;
    }
}
$path = "/users/{$dnum}/user.vcard";
if (!objCheckExist($path, "raw")){
    echo "������� {$dnum} �� ������";
    exit;
}
$obj = objLoad($path);
if (preg_match("/���������/", $obj["address"])){
    $city = "���������";
}
else if (preg_match("/�����/", $obj["address"])){
    $city = "�����";
}
else {
    $city = "��������";
}
$br = array_keys(objLoadBranch("/profiles/", false, true));
$servList = [];
foreach($br as $value){
    $obj = objLoad("/profiles/".$value. "/profile.pro");
    if ($obj["login"] == "gerdt"){
        continue;
    }
    if (isset($obj["mod_supporter"])){
        if (($obj["mod_supporter"] == "1") && (preg_match("/{$city}/i", $obj["areas"]))){
            $buf = [];
            $buf["login"] = $obj["login"];
            $buf["name"] = $obj["uname"];
            $servList[] = $buf;
//            $view->show("select_block_data", $buf);
        }
    }
}


/*--------------------------------------------------*/


$view = new \View("/_modules/routers/html_templates/supChangeRouterForm/");
$data = [];
$data ["dnum"] = $dnum;
$view->show("header", $data);
$data = [];
$data["text"] = "������� MAC �����";
$data["id"] = "macOld";
$view->show("select_header", $data);
foreach ($macList as $value){
    $data = [];
    $data["value"] = $value;
    $data["name"] = $value;
    $view->show("select_data", $data);
}
$view->show("select_footer");




$data = [];
$data["text"] = "��������� �������";
$data["id"] = "servEng";
$view->show("select_header", $data);
foreach ($servList as $value){
    $data = [];
    $data["value"] = $value["login"];
    $data["name"] = $value["name"];
    $view->show("select_data", $data);
}
$view->show("select_footer");

$view->show("footer",["action" => "takeRouter()"]);




































