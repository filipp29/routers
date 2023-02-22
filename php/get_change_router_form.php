<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/View.php';

error_reporting(E_ALL); //Включаем вывод ошибок
set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
ini_set('display_errors', 1); //Еще один вывод ошибок
ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)

//require '../html_templates/routerChangeForm/main_header.php';
//require '../html_templates/routerChangeForm/state_header.php';

/*--------------------------------------------------*/



$names = [
    "dnum" => "Договор",
    "installer" => "Установил",
    "servEng" => "Сервисный инженер",
    "tester" => "Тестировщик",
    "store" => "Склад",
    "comment" => "Комментарий",
    "author" => "Автор"
];


/*--------------------------------------------------*/


$cityName = [
    "kst" => "Костанай",
    "lsk" => "Лисаковск",
    "kchr" => "Качар"
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
                "chkalova_16" => "Чкалова 16",
                "mayakovskogo_120" => "Маяковского 120",
                "altinsarina_117" => "Алтынсарина 117",
                "gogol_62" => "Гоголя 62",
                "tekstilshik_18" => "Текстильщиков 18",
                "plaza" => "Плаза",
                "lisakovsk" => "Лисаковск",
                "kachar" => "Качар",
                "kzhbi" => "Склад КЖБИ",
                "ksk" => "Склад КСК",
                "centr" => "Склад центр"
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