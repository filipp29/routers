<?php

//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once './RouterBase.php';
require_once './Decoder.php';
require_once './RouterTicket.php';



$str = $_GET["data"];

$dec = new \Decoder();
$data = $dec->strToArray($str);

$mac = $data["mac"];
$type = $data["type"];
$author = $data["author"];
$text = $data["text"];
$opened = true;
try{
    $ticket = new \RouterTicket($mac, $type);
    
    if ($ticket->isOpened()){
        $ticket->addComment($text, $author);
    }
    else{
        $opened = false;
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}

if ($opened){
    echo "Комментарий к тикету добавлен";
}
else{
    echo "Нет открытого тикета";
}



















