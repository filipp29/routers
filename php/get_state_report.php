<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterTicket.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterSupport.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterReports.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterList.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterSearch.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/View.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php';


date_default_timezone_set("Asia/Almaty");

//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)


$dec = new \Decoder();
$str = $_GET["data"];
$data = $dec->strToArray($str);

$params["start"] = $data["start"];

$params["end"] = $data["end"]+(60*60*16);
//$params["state"] = $_GET["state"];
switch ($data["type"]):
    case "status":
        $params["state"] = ["installed", "inTaking","atServEng"];
        break;
    case "servEng":
        $params["state"] = ["installed","atServEng"];
        break;
endswitch;

$params["city"] = $data["city"];
$params["type"] = $data["type"];
\RouterReports::addDailyReport();
/*--------------------------------------------------*/

//$data = [];
//$dailyReport = new \RouterReports("dailyReport", $params);
//$result = $dailyReport->getReport();
//$view = new \View("/_modules/routers/html_templates/dailyReportTable/");
//$view->show("main_header");
//$data = $result["start"];
//$data["date"] = $params["start"];
//$view->show("data",$data);
//$data = $result["end"];
//$data["date"] = $params["end"];
//$view->show("data",$data);
//$view->show("main_footer");








/*--------------------------------------------------*/
$report = new \RouterReports("stateJournal", $params);

$result = $report->getReport();
$view = new \View("/_modules/routers/html_templates/stateJournalTable/");
$view->show("main_header");
$lvl1 = 0;
$lvl2 = 0;
$lvl3 = 0;
if ($data["type"] == "status"){
    foreach($result as $state => $dateList){
        $data = [];
        $data["name"] = \RouterState::getStateName()[$state];
        $data["type"] = "lvl1";
        $lvl1++;
        $data["id"] = "lvl1_{$lvl1}";
        $class1 = $data["id"];
        $data["devstate"] = "close";
        $view->show("data", $data);
        foreach($dateList as $date => $macList){
            $data = [];

            $data["name"] = substr($date, 0, 4). ".". substr($date, 4,2). ".". substr($date, 6, 2);
            $data["value"] = count($macList);
    //        $data["date"] = $date;
            $data["type"] = "lvl2";
            $lvl2++;
            $data["id"] = "lvl2_{$lvl2}";
            $data["class"] = $class1. "_child hidden";
            $view->show("data", $data);
            $class2 = $class1. " ". $data["id"];
            $data["devstate"] = "close";
            foreach($macList as $mac => $timeList){
                $data = [];
                $lvl3++;
                $data["name"] = $mac;
                $data["type"] = "lvl3";
                $data["id"] = "lvl3_{$lvl3}";
                $data["devstate"] = "close";
                $data["class"] = $class2. "_child hidden";
                $view->show("data",$data);
                $class3 = $class2. " ". $data["id"];
                foreach($timeList as $time){
                    $data = [];
                    $data["name"] = $time["time"];
                    $data["value"] = $time["text"];
                    $data["type"] = "lvl4";
                    $data["class"] = $class3. "_child hidden";
                    $view->show("data",$data);
                }

            }

        }
        unset($data);
    }
}
if ($data["type"] == "servEng"){
    $data = [
        "params" => [],
        "states" => [
            "atServEng"
        ],
        "city" => [
            "lsk",
            "kchr",
            "kst"
        ]
    ];
    $search = new \RouterSearch($data["params"], $data["states"], $data["city"]);
    $profileList = [];
    $buf = $search->search();
    foreach($buf as $value){
        $profileList[$value["inCharge"]]++;
    }
//    echo "<pre>";
//    print_r($profileList);
//    echo "</pre>";
    $stateName = [
        "installed" => "Установил",
        "atServEng" => "Получил"
    ];
    foreach($result as $profile => $stateList){
        if (($profile == "SYSTEM") || ($profile == "UNKNOUWN")){
            continue;
        }
        $data = [];
        $data["name"] = profileGetUsername($profile);
        $data["value"] = "количество роутеров на текущий момент: ".  (isset($profileList[$profile]) ? $profileList[$profile] : "0");
        $data["type"] = "lvl1";
        $lvl1++;
        $data["id"] = "lvl1_{$lvl1}";
        $class1 = $data["id"];
        $data["devstate"] = "close";
        $view->show("data", $data);
        foreach($stateList as $state => $macList){
            $data = [];
            $data["name"] = $stateName[$state];
            $data["value"] = count($macList);
//            $data["name"] = substr($date, 0, 4). ".". substr($date, 4,2). ".". substr($date, 6, 2);
    //        $data["date"] = $date;
            $data["type"] = "lvl2";
            $lvl2++;
            $data["id"] = "lvl2_{$lvl2}";
            $data["class"] = $class1. "_child hidden";
            $view->show("data", $data);
            $class2 = $class1. " ". $data["id"];
            $data["devstate"] = "close";
            foreach($macList as $mac => $timeList){
                $data = [];
                $lvl3++;
                $data["name"] = $mac;
                $data["type"] = "lvl3";
                $data["id"] = "lvl3_{$lvl3}";
                $data["devstate"] = "close";
                $data["class"] = $class2. "_child hidden";
                $view->show("data",$data);
                $class3 = $class2. " ". $data["id"];
                foreach($timeList as $time => $text){
                    $buf = explode("_",$time);
                    $date = $buf[0];
                    $time = $buf[1];
                    $data = [];
                    $data["name"] = substr($date, 6, 2) . ".". substr($date, 4,2). ".". substr($date, 0, 4). " - ". $time;
                    $data["value"] = $text;
                    $data["type"] = "lvl4";
                    $data["class"] = $class3. "_child hidden";
                    $view->show("data",$data);
                }

            }

        }
        unset($data);
    }
}

$view->show("main_footer");































