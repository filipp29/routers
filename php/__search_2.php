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



//$params = [
//    "mac" => "0",
//    "name" => "Ирина"
//];
//
//$city = ["", null];
//$states = ["inTaking","installed"];
//$search = new \RouterSearch($params, $states, $city);
//$result = $search->search();
//foreach($result as $key => $value){
//    echo "----------------------------------------------------------------<br>";
//    foreach ($value as $k => $v){
//        echo $k. " ". $v. "<br>";
//    }
//    echo "----------------------------------------------------------------<br>";
//}
//try{
//    $router = new \RouterBase($result[0]["mac"]);
//    $ticket = new \RouterTicket($router->getCStatus("mac"), "inTaking");
//    $support = $router->getSupport();
//    $comment = $router->getComment();
//    echo "Comment-------------------------<br>";
//    foreach ($comment as $key => $value){
//        echo $value["timeStamp"]. " ". $value["author"]. " ". $value["text"]. "<br>";
//    }
//    echo "<br> Support------------------------------<br>";
//    foreach ($support as $key => $value){
//        
//        foreach ($value as $k => $v){
//            echo $k. " ". $v. "<br>";
//        }
//    }
//    echo "<br> Tickets---------------------------------<br>";
//    $timeStamp = $ticket->getAllTickets();
//    foreach ($timeStamp as $key => $value){
//        $buf = $ticket->getComments($value);
//        foreach ($buf as $k => $v){
//            echo date("Y-m-d H:i:s ",(int)$v["timeStamp"]). " ". $v["author"]. " ". $v["text"]. "<br>";
//        }
//    }
//}
//catch(\Exception $e){
//    echo $e->getMessage(). "<br>";
//}
//
//echo "!!!!!!!!!";


$dec = new \Decoder();
$str = $_GET["data"];
//if(mb_detect_encoding($str) != "windows-1251") {
//    $str = mb_convert_encoding($str, "windows-1251", mb_detect_encoding($str));
//}
$data = $dec->strToArray($str);
if ($data["params"]["address"]){
    $data["params"]["address"] = preg_replace("/[^0-9а-яА-ЯёЁa-zA-Z]+/", ".*", $data["params"]["address"]);
}
if ($data["params"]["name"]){
    $data["params"]["name"] = preg_replace("/[^0-9а-яА-ЯёЁa-zA-Z]+/", ".*", $data["params"]["name"]);
}
//$city = ["", null];
$search = new \RouterSearch($data["params"], $data["states"], $data["city"]);
$result = $search->search();
foreach ($result as $key => $value){
    if ($result[$key]["inCharge"]){
        $result[$key]["inCharge"] = \RouterBase::getProfileName($result[$key]["inCharge"]);
    }
}
$res = $dec->arrayToStr($result);
echo $res;





































