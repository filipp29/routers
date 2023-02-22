<?php

//
//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once '../php/RouterBase.php';
require_once './RouterState.php';
require_once './Decoder.php';
require_once './View.php';



/*--------------------------------------------------*/

function checkOld(
        $mac,
        $dnum
){
    $authPath = "/routers/byMac/";
    if (!objCheckExist($authPath. $mac. "/cstatus.info", "raw")){
        return ["message" => "MAC {$mac} не найден, будет добавлен в базу", "status" => 0];
    }
    else{
        $router = new \RouterBase($mac);
        $state = $router->getCStatus("curState");
        if ($state == "installed"){
            $dnumCur = $router->getCStatus("dnum");
            if ($dnum == $dnumCur){
                return ["message" => "Роутер {$mac} - OK", "status" => 0];
            }
            else{
                return ["message" => "Роутер {$mac} числится за другим абонентом - {$dnumCur}", "status" => 1];
            }
        }
        else if ($state == "inTaking"){
            if ($dnum == $dnumCur){
                return ["message" => "Роутер {$mac} - OK", "status" => 0];
            }
            else{
                return["Роутер {$mac} находится в состоянии изъятия у абонента - {$dnumCur}", "status" => 1];
            }
        }
        else {
            return ["message" => "Роутер {$mac} находится в статусе: {$state}", "status" => 1];
        }
        
    }
}


/*--------------------------------------------------*/





/*--------------------------------------------------*/

function check(){
    try{
        $mac = $_GET["mac"];
        
        $dnum = trim($_GET["dnum"]);
        $servEng = trim($_GET["servEng"]);
        $author = trim($_GET["author"]);
        $mac = \RouterBase::getValidMac($mac);
        if (strlen($mac)!= 12){
            throw new Exception("Не верный MAC адрес ". $mac);
        }
        $mac = \RouterBase::getMacName($mac);

        $chkOld = checkOld($mac, $dnum);
        
        $error = $chkOld["status"];
        
        $message .= ($chkOld["status"]) ? "Ошибка: " : "";
        $message .= $chkOld["message"]. " ";
        $view = new \View("/_modules/routers/html_templates/supTakeRouter/");
        $data = [];
        $data["message"] = $message;
        if ($error > 0){
            $errorMessage = "Роутер не может быть принят";
            $data["error"] = $errorMessage;
            $view->show("header", $data);

        }
        else{
            $errorMessage = "Роутер может быть принят";
            $data["error"] = $errorMessage;
            $data["mac"] = $mac;
            $data["dnum"] = $dnum;
            $data["servEng"] = $servEng;
            $data["author"] = $author;
            $view->show("header", $data);
            $view->show("footer", $data);
        }

    }
    catch (\Exception $e){
        echo $e->getMessage();
    }

}

/*--------------------------------------------------*/


function checked(){
    try{
        $mac = $_GET["mac"];
        $dnum = $_GET["dnum"];
        $servEng = $_GET["servEng"];
        $author = $_GET["author"];
        echo $servEng;
        $mac = \RouterBase::getValidMac($mac);
        if (strlen($mac)!= 12){
            throw new Exception("Не верный MAC адрес ". $mac);
        }
        $mac = \RouterBase::getMacName($mac);


        $atServEng = new \RouterState("atServEng", ["servEng" => $servEng, "author" => $author]);
        $path = "/routers/byMac/";
        $curMac = objLoad("/routers/byLogin/{$dnum}/user.info","raw")["mac"];
        if ($curMac != $macOld){
            $writeOff = new \RouterState("writeOff", ["author" => $author, "comment" => "Абонент {$dnum} вернул другой роутер {$macOld}"]);
            $router = new \RouterBase($curMac);
            $router->addState($writeOff);
            $router->setNegDays(0);
        }
        
        if (!objCheckExist($path. $mac, $type)){
            \RouterBase::addRouter($mac, $atServEng);
            $address = objLoad("/profiles/{$servEng}/profile.pro")["areas"];
            if (preg_match("/Качар/i", $address)){
                $city = "kchr";
            }
            else if (preg_match("/Лисаковск/i", $address)){
                $city = "lsk";
            }
            else{
                $city = "kst";
            }
            $router = new \RouterBase($mac);
            $router->setNegDays(0);
            $router->setCity($city);
        }
        else{
            $router = new \RouterBase($mac);
            $router->addState($atServEng);
            $router->setNegDays(0);
        }
        echo "Роутер принят";

    }
    catch (\Exception $e){
        echo $e->getMessage();
    }
}



$checked = $_GET["checked"];

if ($checked){
    checked();
}
else{
    check();
}













