<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once './RouterBase.php';
require_once './RouterSearch.php';
require_once './Decoder.php';
require_once './View.php';
require_once './RouterState.php';
require_once './Log.php';


//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)


date_default_timezone_set("Asia/Almaty");







/*--------------------------------------------------*/

function cellCheck(
        $type,
        $value
){
    
    if ($value == null){
        return "";
    }
    if ($type === "curState"){
        $stateName = \RouterState::getStateName();
        return $stateName[$value];
    }
    if(($type === "inCharge") || ($type === "author")){
        return \RouterBase::getProfileName($value);
    }
    if ($type === "address"){
        $reg = "/.*?,/";
        $value = preg_replace($reg, "", $value, 1); 
        $reg = "/,,*/";
        $value = preg_replace($reg, " , ", $value);
        return $value;
    }
    if ($type === "store"){
        return \RouterBase::getStoreName($value);
    }
    return $value;
}


/*--------------------------------------------------*/


function routerIndexTable(
        $data
){
    $view = new \View("/_modules/routers/html_templates/routerIndexTable/");
    $search = new \RouterSearch($data["params"], $data["states"], $data["city"]);
    $result = $search->search();
    $number = 0;
    $count = [];
    $count["installed"] = 0;
    $count["inTaking"] = 0;
    $count["atServEng"] = 0;
    $count["store"] = 0;
    $count["toStore"] = 0;
    $count["writeOff"] = 0;
    $count["lost"] = 0;
    $count["testing"] = 0;
    $count["lsk"] = 0;
    $count["kst"] = 0;
    $count["kchr"] = 0;
    foreach ($result as $key => $value){
        $count[$value["city"]]++;
        $count[$value["curState"]]++;
        $row = [];
        foreach($value as $k => $v){
            $row[$k] = cellCheck($k, $v);
        }
        $row["number"] = $number;
        
        $number++;
        $view->show("data", $row);
    }
    foreach($count as $key => $value){
        $buf = [];
        $buf["key"] = $key;
        $buf["value"] = $value;
        $view->show("vars", $buf);
    }
}



/*--------------------------------------------------*/


function routerStateInWorkTable(
        $data
){
    $view = new \View("/_modules/routers/html_templates/routerStateInWorkTable/");
    $states = [
        "installed"
    ];
    $search = new \RouterSearch($data["params"], $states, $data["city"], "inWork");
    $result = $search->search();
    $number = 0;
    foreach ($result as $key => $value){
        $row = [];
        foreach($value as $k => $v){
            $row[$k] = cellCheck($k, $v);
        }
        $router = new \RouterBase($row["mac"]);
        $ticket = new \RouterTicket($row["mac"], "inWork");
        
        $negDays = (int)$router->getCStatus("negDays");
        $start = (int)$data["start"];
        $end = (int)$data["end"];
        
        if (($negDays >= $start) && ($negDays <= $end)){
            $comment = $ticket->getComments()[0];
            $author = cellCheck("author", $comment["author"]);
            $timeStamp = date("d.m.Y H:i:s", $comment["timeStamp"]);
            $text = substr($comment["text"], 0, 45);
            $row["lastComment"] = "[". $timeStamp. "] ". $author. " : ". $text;
            $row["number"] = $number;
            $number++;
            $view->show("data", $row);
        }
    }
}



/*--------------------------------------------------*/


function routerStateInTakingTable(
        $data
){
    $view = new \View("/_modules/routers/html_templates/routerStateInTakingTable/");
    $states = [
        "inTaking"
    ];
    $search = new \RouterSearch($data["params"], $states, $data["city"]);
    $result = $search->search();
    $number = 0;
    foreach ($result as $key => $value){
        $row = [];
        foreach($value as $k => $v){
            $row[$k] = cellCheck($k, $v);
        }
        $ticket = new \RouterTicket($row["mac"], "inTaking");
        $row["inTakingDays"] = floor((time() - (int)$ticket->getCurrentTicket())/86400);
        $router = new \RouterBase($row["mac"]);
        
        $negDays = (int)$router->getCStatus("negDays");
        $start = (int)$data["start"];
        $end = (int)$data["end"];
        
        if (($negDays >= $start) && ($negDays <= $end)){
            $comment = $ticket->getComments()[0];
            $author = cellCheck("author", $comment["author"]);
            $timeStamp = date("d.m.Y H:i:s", $comment["timeStamp"]);
            $text = substr($comment["text"], 0, 45);
            $row["lastComment"] = "[". $timeStamp. "] ". $author. " : ". $text;
            $row["number"] = $number;
            $number++;
            $view->show("data", $row);
        }
    }
}


/*--------------------------------------------------*/



try {
 
    
    $dec = new \Decoder();
    $str = $_GET["data"];
    //if(mb_detect_encoding($str) != "windows-1251") {
    //    $str = mb_convert_encoding($str, "windows-1251", mb_detect_encoding($str));
    //}
    $data = $dec->strToArray($str);
    
    
    if (isset($data["params"]["address"]) && ($data["params"]["address"])){
        $data["params"]["address"] = preg_replace("/[^0-9а-яА-ЯёЁa-zA-Z]+/", ".*", $data["params"]["address"]);
    }
    if (isset($data["params"]["name"]) && ($data["params"]["name"])){
        $data["params"]["name"] = preg_replace("/[^0-9а-яА-ЯёЁa-zA-Z]+/", ".*", $data["params"]["name"]);
    }
    if (isset($data["params"]["inCharge"]) && ($data["params"]["inCharge"])){
        
        $br = array_keys(objLoadBranch("/profiles/", false, true));
        foreach($br as $value){
            $obj = objLoad("/profiles/$value/profile.pro");
//            echo $data['params']['inCharge']. " ". $obj["uname"]. "<br>";
            
            if (preg_match(mb_strtolower("/{$data['params']['inCharge']}/i"), mb_strtolower($obj["uname"]))){
                
                $data["params"]["inCharge"] = $obj["login"];
                break;
            }
        }
    }
    //$city = ["", null];
    switch ($data["type"]) {
        
    case "routerIndex":
        
        routerIndexTable($data);
        
        break;
    case "routerStateInTaking":
        
        routerStateInTakingTable($data);
        
        break;
    case "routerStateInWork":
        routerStateInWorkTable($data);
        
        break;
    default:
        break;
}


} catch (Exception $exc) {
    echo $exc->getMessage();
}





































