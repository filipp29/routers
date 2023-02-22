<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once './RouterBase.php';
require_once './RouterSearch.php';
require_once './Decoder.php';


//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)






$dec = new \Decoder();
$str = $_GET["data"];

$data = $dec->strToArray($str);
$states = [
    "inTaking",
    "installed"
];
$city = ["", null];
$search = new \RouterSearch($data["params"], $states, $city,$ticket);
$buf = $search->search();
$minDays = $data["minDays"];
$maxDays = $data["maxDays"];
$result["installed"] = [];
$result["inTaking"] = [];
foreach($buf as $key => $value){
    $negDays = (int)$value["negDays"];
    if (($negDays >= $minDays) && ($negDays <= $maxDays)){
        $router = new \RouterBase($value["mac"]);
        $value["comment"] = $router->getComment(0);
        $result = $value;
    }
}

$res = $dec->arrayToStr($result);
echo $res;





































