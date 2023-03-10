<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //????????? ???????? ????? (?????, ?????? ???? ???????? ? ?????????? ????????

//error_reporting(E_ALL); //???????? ????? ??????
//set_time_limit(600); //????????????? ???????????? ????? ?????? ??????? ? ????????
//ini_set('display_errors', 1); //??? ???? ????? ??????
//ini_set('memory_limit', '512M'); //????????????? ??????????? ?????? ?? ?????? (512??)

require_once './Decoder.php';
require_once './RouterTicket.php';
require_once './RouterBase.php';
require_once './RouterState.php';
require_once './View.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/_lib/libProfiles.php';



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
        "mac" => "MAC ?????:",
        "dnum" => "????? ????????:",
        "name" => "??? ????????:",
        "address" => "????? ????????:",
        "curState" => "??????:",
        "negDays" => "???? ? ??????:",
        "inTakingDays" => "???? ?? ???????:"
    ];
    $buttons = [
        [
            "name" => "??????? ? ????",
            "func" => "changeComment(toInstalled,'??????????')"
        ],
        [
            "name" => "?????",
            "func" => "confirmForm('changeComment(toStore,`?? ??????`)')"
        ],
        [
            "name" => "??????",
            "func" => "commentConfirmForm('changeComment(toLost,`??????`)')"
        ],
        [
            "name" => "??????",
            "func" => "commentConfirmForm('changeComment(toWriteOff,`??????`)')"
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
    $routerInfo["curState"] = $stateNames[$routerInfo["curState"]];
    $routerInfo["address"] = preg_replace("/.*?,/", "", $routerInfo["address"], 1);
    $routerInfo["address"] = preg_replace("/,+/", " , ", $routerInfo["address"]);
    $view->show("header");
    $view->show("router_header");
    foreach($routerInfo as $k => $v){
        $result = [];
        if (key_exists($k, $keyNames)){
            $result["keyName"] = $keyNames[$k];
            $result["key"] = $k;
            $result["value"] = $v;
            $view->show("router_block",$result);
        }
    }
    $view->show("router_footer");
    
    
    
    $comments = array_reverse($comments,true);
    showComments($type, $timeStamp,$comments, $view);
    
    $view->show("commentBox");
    
    $view->show("button_header");
    foreach ($buttons as $k => $v){
        $result = [
            "buttonName" => $v["name"],
            "buttonFunc" => $v["func"]
        ];
        $view->show("button_data",$result);
    }
    $view->show("button_footer");
    
    
    
    $view->show("footer");
}



/*--------------------------------------------------*/


function inWorkForm(
        $mac
){
    $keyNames = [
        "mac" => "MAC ?????:",
        "dnum" => "????? ????????:",
        "name" => "??? ????????:",
        "address" => "????? ????????:",
        "curState" => "??????:",
        "negDays" => "???? ? ??????:",
    ];
    $buttons = [
        [
            "name" => "? ???????",
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
    $routerInfo["inTakingDays"] = floor(((time() - (int)$timeStamp)/86400));
    $routerInfo["curState"] = $stateNames[$routerInfo["curState"]];
    $routerInfo["address"] = preg_replace("/.*?,/", "", $routerInfo["address"], 1);
    $routerInfo["address"] = preg_replace("/,+/", " , ", $routerInfo["address"]);
    $view->show("header");
    $view->show("router_header");
    foreach($routerInfo as $k => $v){
        $result = [];
        if (key_exists($k, $keyNames)){
            $result["keyName"] = $keyNames[$k];
            $result["key"] = $k;
            $result["value"] = $v;
            $view->show("router_block",$result);
        }
    }
    $view->show("router_footer");
    
    
    
    $comments = array_reverse($comments,true);
    showComments($type, $timeStamp,$comments, $view);
    
    $view->show("commentBox");
    
    $view->show("button_header");
    foreach ($buttons as $k => $v){
        $result = [
            "buttonName" => $v["name"],
            "buttonFunc" => $v["func"]
        ];
        $view->show("button_data",$result);
    }
    $view->show("button_footer");
    
    
    
    $view->show("footer");
}



/*--------------------------------------------------*/










$str = $_GET["data"];
$dec = new \Decoder();
$data = $dec->strToArray($str);

$mac = $data["mac"];
$type = $data["type"];




$mac = \RouterBase::getValidMac($mac);
if (strlen($mac) != 12){
    echo ("?? ?????? ??? ".$mac);
}

if (!objCheckExist("/routers/byMac/". $mac. "/cstatus.info", "raw")){
    echo "MAC ?? ??????". $mac;
}

try {
    
    $ticket = new \RouterTicket($mac, $type);
    if (!$ticket->isOpened()){
        throw new Exception("??? ????????? ??????");
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




























