<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом

error_reporting(E_ALL); //Включаем вывод ошибок
set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
ini_set('display_errors', 1); //Еще один вывод ошибок
ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)

require_once './Decoder.php';
require_once './RouterTicket.php';
require_once './RouterBase.php';
require_once './RouterState.php';
require_once './View.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/_lib/libProfiles.php';
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libIntPhonebook.php');



date_default_timezone_set("Asia/Almaty");




/*--------------------------------------------------*/


function showComments(
        $type,
        $timeStamp,
        $comments,
        $view
){
    $result = [
        "timeStamp" => $timeStamp,
        "ticketType" => $type
    ];
    $view->show("ticket_header",$result);
    foreach ($comments as $key => $value){
        $result = [];
        $result["author"] = profileGetUsername($value["author"]);
        $result["avatar"] = profileGetAvatar($value["author"]);
        $result["text"] = $value["text"];
        $result["timeStamp"] = $value["timeStamp"];
        if ($value["author"] === "SYSTEM"){
            $view->show("ticket_system", $result);
        }
        else{
            $view->show("ticket_comment", $result);
        }
    }
    $view->show("ticket_footer");
}


/*--------------------------------------------------*/


function inTakingForm(
        $mac
){
    $keyNames = [
        "address" => "Адрес абонента:",
        "mac" => "MAC адрес:",
        "dnum" => "Номер договора:",
        "curState" => "Статус:",
        "negDays" => "Дней в минусе:",
        "inTakingDays" => "Дней на изъятии:"
    ];
    $buttons = [
        [
            "name" => "О роутере",
            "func" => "getSimpleRouterForm('".$mac."')"
        ],
        [
            "name" => "В учет",
            "func" => "changeComment(toInstalled,'Установлен')"
        ],
        [
            "name" => "На склад",
            "func" => "changeStatusButtonClick('store')"
        ],
        [
            "name" => "Сервиснику",
            "func" => "changeStatusButtonClick('atServEng')"
        ],
        [
            "name" => "Утерян",
            "func" => "commentConfirmForm('changeComment(toLost,`Утерян`)')"
        ],
        [
            "name" => "Списан",
            "func" => "commentConfirmForm('changeComment(toWriteOff,`Списан`)')"
        ],
    ];
    $stateNames = \RouterState::getStateName();
    $type = "inTaking";
    
    
    $ticket = new \RouterTicket($mac, $type);
    $router = new \RouterBase($mac);
    $view = new \View("/_modules/routers/html_templates/ticketForm/");
    $data = $router->getCStatus();
    $timeStamp = $ticket->getCurrentTicket();
    $comments = $ticket->getComments($timeStamp);
    $routerInfo = $router->getCStatus();
    $routerInfo["inTakingDays"] = floor(((time() - (int)$timeStamp)/86400));
    $routerInfo["status"] = $routerInfo["curState"];
    $routerInfo["curState"] = $stateNames[$routerInfo["curState"]];
    $routerInfo["address"] = preg_replace("/.*?,/", "", $routerInfo["address"], 1);
    $routerInfo["address"] = preg_replace("/,+/", " , ", $routerInfo["address"]);
    $view->show("header");
    
    $view->show("router_header", ["uname" => $routerInfo["name"]]);
//    foreach($routerInfo as $k => $v){
//        $result = [];
//        if (key_exists($k, $keyNames)){
//            $result["keyName"] = $keyNames[$k];
//            $result["key"] = $k;
//            $result["value"] = $v;
//            $view->show("router_block",$result);
//        }
//    }
    foreach($keyNames as $k => $v){
        $result = [];
        $result["keyName"] = $v;
        $result["key"] = $k;
        $result["value"] = $routerInfo[$k];
        $view->show("router_block",$result);
    }
    $view->show("router_footer");
    
    
    
    
    
    $result = [];
    $result["dnum"] = $routerInfo["dnum"];
    $result["addr"] = $routerInfo["address"];
    $view->show("button_header", $result);
    foreach ($buttons as $k => $v){
        $result = [
            "buttonName" => $v["name"],
            "buttonFunc" => $v["func"]
        ];
        $view->show("button_data",$result);
    }
    $view->show("button_footer");
    
    
    
    $view->show("footer");
    
    $view->show("commentBox");
    
//    $comments = array_reverse($comments,true);
    showComments($type, $timeStamp,$comments, $view);
    
    
    
}



/*--------------------------------------------------*/


function inWorkForm(
        $mac
){
    $keyNames = [
        "address" => "Адрес абонента:",
        "mac" => "MAC адрес:",
        "dnum" => "Номер договора:",
        "curState" => "Статус:",
        "negDays" => "Дней в минусе:",
    ];
    $buttons = [
        [
            "name" => "О роутере",
            "func" => "getSimpleRouterForm('".$mac."')"
        ],
        [
            "name" => "В изъятие",
            "func" => "toInTaking()"
        ]
    ];
    $stateNames = \RouterState::getStateName();
    $type = "inWork";
    
    
    $ticket = new \RouterTicket($mac, $type);
    $router = new \RouterBase($mac);
    $view = new \View("/_modules/routers/html_templates/ticketForm/");
    $data = $router->getCStatus();
    $timeStamp = $ticket->getCurrentTicket();
    $comments = $ticket->getComments($timeStamp);
    $routerInfo = $router->getCStatus();
    $routerInfo["status"] = $routerInfo["curState"];
    $routerInfo["curState"] = $stateNames[$routerInfo["curState"]];
    $routerInfo["address"] = preg_replace("/.*?,/", "", $routerInfo["address"], 1);
    $routerInfo["address"] = preg_replace("/,+/", " , ", $routerInfo["address"]);
    $view->show("header");
    
    $view->show("router_header", ["uname" => $routerInfo["name"]]);
//    foreach($routerInfo as $k => $v){
//    for($i = 0; $i < count($routerInfo); $i++){  
//        
//        $result = [];
//        if (key_exists($k, $keyNames)){
//            $result["keyName"] = $keyNames[$k];
//            $result["key"] = $k;
//            $result["value"] = $v;
//            $view->show("router_block",$result);
//        }
//    }
    foreach($keyNames as $k => $v){
        $result = [];
        $result["keyName"] = $v;
        $result["key"] = $k;
        $result["value"] = $routerInfo[$k];
        $view->show("router_block",$result);
    }
    $view->show("router_footer");
    
    
    
    
    
    $result = [];
    $result["dnum"] = $routerInfo["dnum"];
    $result["addr"] = $routerInfo["address"];
    $view->show("button_header", $result);
    foreach ($buttons as $k => $v){
        $result = [
            "buttonName" => $v["name"],
            "buttonFunc" => $v["func"]
        ];
        $view->show("button_data",$result);
    }
    $view->show("button_footer");
    
    
    
    $view->show("footer");
    
    $view->show("commentBox");
    
//    $comments = array_reverse($comments,true);
    showComments($type, $timeStamp,$comments, $view);
    
    
    
}



/*--------------------------------------------------*/










$str = $_GET["data"];
$dec = new \Decoder();
$data = $dec->strToArray($str);

$mac = $data["mac"];
$type = $data["type"];




$mac = \RouterBase::getValidMac($mac);
if (strlen($mac) != 12){
    echo ("Не верный мак ".$mac);
}
$mac = \RouterBase::getMacName($mac);
if (!objCheckExist("/routers/byMac/". $mac. "/cstatus.info", "raw")){
    echo "MAC не найден". $mac;
}

try {
    
    $ticket = new \RouterTicket($mac, $type);
    if (!$ticket->isOpened()){
        throw new Exception("Нет открытого тикета");
    }
    
    switch ($type) {
    case "inTaking":
        inTakingForm($mac);
        break;
    
    case "inWork":
        inWorkForm($mac);
        break;

    default:
        break;
}
    
    
} catch (Exception $exc) {
    echo $exc->getMessage();
}




















