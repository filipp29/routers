<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/View.php';

error_reporting(E_ALL); //�������� ����� ������
set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
ini_set('display_errors', 1); //��� ���� ����� ������
ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)

//require '../html_templates/routerChangeForm/main_header.php';
//require '../html_templates/routerChangeForm/state_header.php';

/*--------------------------------------------------*/



$names = [
    "dnum" => "�������",
    "installer" => "���������",
    "servEng" => "��������� �������",
    "tester" => "�����������",
    "store" => "�����",
    "comment" => "�����������",
    "author" => "�����"
];


/*--------------------------------------------------*/


$cityName = [
    "kst" => "��������",
    "lsk" => "���������",
    "kchr" => "�����"
];


/*--------------------------------------------------*/



$curState = $_GET["state"];
$mac = $_GET["mac"];
$author = $_GET["author"];
$data = [];
$view = new \View("/_modules/routers/html_templates/getRouterChangeForm/");
$data["mac"] = $mac;
$stateName = \RouterState::getStateName();
$data["stateName"] = $stateName[$curState];
$view->show("main_header",$data);
//$view->show("state_header");
//
//foreach ($states as $key => $value){
//    $selected = "";
//    if ($curState == $key){
//        $selected = "selected";
//    }
////    require '../html_templates/routerChangeForm/state_data.php';
//    $view->show("state_data");
//}
////require '../html_templates/routerChangeForm/state_footer.php';
//$view->show("state_footer");

if ($curState){
    $params = \RouterState::getStateListAll()[$curState]["params"];
    $data = [];
    $count = count($params);
    $data["curState"] = $curState;
    $data["count"] = $count;
    
//    require '../html_templates/routerChangeForm/count.php';
    $view->show("vars",$data);
    
    for($i = 0; $i < $count; $i++){
        $data = [];
        $data["name"] = $names[$params[$i]];
        $data["key"] = $params[$i];
        $data["i"] = $i;
        $router = new \RouterBase($mac);
        $city = $cityName[$router->getCStatus("city")];
        
        if (($params[$i] == "installer") || ($params[$i] == "servEng") || ($params[$i] == "tester")){
            $view->show("select_block_header", $data);
            $br = array_keys(objLoadBranch("/profiles/", false, true));
            foreach($br as $value){
                $obj = objLoad("/profiles/".$value. "/profile.pro");
                if ($obj["login"] == "gerdt"){
                    continue;
                }
                if (isset($obj["mod_supporter"])){
                    if (($obj["mod_supporter"] == "1") && (preg_match("/{$city}/i", $obj["areas"]))){
                        $buf = [];
                        $buf["value"] = $obj["login"];
                        $buf["name"] = $obj["uname"];
                        $view->show("select_block_data", $buf);
                    }
                }
            }
            
            $view->show("select_block_footer", $data);
        }
        else if ($params[$i] == "store"){
            $view->show("select_block_header", $data);
            $storeList = [
                "chkalova_16" => "������� 16",
                "mayakovskogo_120" => "����������� 120",
                "altinsarina_117" => "����������� 117",
                "gogol_62" => "������ 62",
                "tekstilshik_18" => "������������� 18",
                "plaza" => "�����",
                "lisakovsk" => "���������",
                "kachar" => "�����",
                "kzhbi" => "����� ����",
                "ksk" => "����� ���",
                "centr" => "����� �����"
            ];
            foreach($storeList as $k => $v){
                $buf["value"] = $k;
                $buf["name"] = $v;
                $view->show("select_block_data", $buf);
            }
            $view->show("select_block_footer", $data);
            
        }
        else if($params[$i] == "comment"){
            $view->show("comment", $data);
        }
        else if ($params[$i] != "author"){
            $view->show("block",$data);
        }
    }
}
//require '../html_templates/routerChangeForm/main_footer.php';
$view->show("main_footer");


?>