<?php
error_reporting(E_ALL); //Включаем вывод ошибок
set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
ini_set('display_errors', 1); //Еще один вывод ошибок
ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT']. '/_modules/routers/php/RouterReports.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/_modules/routers/php/View.php';
date_default_timezone_set("Asia/Almaty");


//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)


function srt(
        $a,
        $b
){
    if ((int)$a["timeStamp"] == (int)$b["timeStamp"]){
        return 0;
    }
    return ($a["timeStamp"] > $b["timeStamp"]) ? -1 : 1;
}

try{
    
    $report = new \RouterReports("alert_journal");
    $report->setDateEnd(time());
    $result = $report->getReport();
    usort($result, 'srt');
    $view = new \View("/_modules/routers/html_templates/alertTable/");
    $number = 0;
    for($i = 0; $i < count($result); $i++){
        $data = $result[$i];
        $number++;
        $data["number"] = $number;
        $view->show("data",$data);
    }

}
catch(\Exception $er){
    echo $er->getMessage();
}











