<?php

//
//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/View.php';



/*--------------------------------------------------*/

function checkOld(
        $mac,
        $dnum
){
    $stateNames = \RouterState::getStateName();
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
            $dnumCur = $router->getCStatus("dnum");
            if ($dnum == $dnumCur){
                return ["message" => "Роутер {$mac} - OK", "status" => 0];
            }
            else{
                return["Роутер {$mac} находится в состоянии изъятия у абонента - {$dnumCur}", "status" => 1];
            }
        }
        else {
            return ["message" => "Роутер {$mac} находится в статусе: {$stateNames[$state]}", "status" => 1];
        }
        
    }
}


/*--------------------------------------------------*/





/*--------------------------------------------------*/

function check(){
    try{
        $mac = $_GET["mac"];
        $cityList = [
            "chkalova_16" => "kst",
            "mayakovskogo_120" => "kst",
            "altinsarina_117" => "kst",
            "gogol_62" => "kst",
            "tekstilshik_18" => "kst",
            "plaza" => "kst",
            "lisakovsk" => "lsk",
            "kachar" => "kchr"
        ];
        $storeList = [
            "chkalova_16" => "Чкалова 16",
            "mayakovskogo_120" => "Маяковского 120",
            "altinsarina_117" => "Алтынсарина 117",
            "gogol_62" => "Гоголя 62",
            "tekstilshik_18" => "Текстильщиков 18",
            "plaza" => "Плаза",
            "lisakovsk" => "Лисаковск",
            "kachar" => "Качар"
        ];
        $dnum = trim($_GET["dnum"]);
        isset($_GET["servEng"]) ? $servEng = trim($_GET["servEng"]) : $servEng = null;
        isset($_GET["store"]) ? $store = trim($_GET["store"]) : $store = null;
        $author = trim($_GET["author"]);
        $mac = \RouterBase::getValidMac($mac);
        if (strlen($mac)!= 12){
            throw new Exception("Не верный MAC адрес ". $mac);
        }
        $mac = \RouterBase::getMacName($mac);

        $chkOld = checkOld($mac, $dnum);
        
        $error = $chkOld["status"];
        $message = '';
        $message .= ($chkOld["status"]) ? "Ошибка: " : "";
        $message .= $chkOld["message"]. " ";
        $data = [];
        $data["message"] = $message;
        if ($error > 0){
            $errorMessage = "Роутер не может быть принят ";
            echo $errorMessage."<!--_ERROR-->". $message;

        }
        else{
            
            
            if ($servEng){
                $atServEng = new \RouterState("atServEng", ["servEng" => $servEng, "author" => $author]);
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
            }else if ($store){
                
                $atServEng = new \RouterState("store", ["store" => $store, "author" => $author]);
                $city = $cityList[$store];
            }
            $path = "/routers/byMac/";
            $curMac = objLoad("/routers/byLogin/{$dnum}/user.info","raw")["mac"];
            if ($curMac != $mac){
                $writeOff = new \RouterState("writeOff", ["author" => $author, "comment" => "Абонент {$dnum} вернул другой роутер {$mac}"]);
                $router = new \RouterBase($curMac);
                $router->addState($writeOff);
                $router->setNegDays(0);
            }

            if (!objCheckExist($path. $mac, 'raw')){
                \RouterBase::addRouter($mac, $atServEng);
                
                $router = new \RouterBase($mac);
                $router->setNegDays(0);
                $router->setCity($city);
            }
            else{
                $router = new \RouterBase($mac);
                $router->addState($atServEng);
                $router->setNegDays(0);
            }
            echo "Роутер принят <!--_OK-->";
            
            
            
        }

    }
    catch (\Exception $e){
        echo $e->getMessage();
    }

}

/*--------------------------------------------------*/


//function checked(){
//    try{
//        $mac = $_GET["mac"];
//        $dnum = $_GET["dnum"];
//        $servEng = $_GET["servEng"];
//        $author = $_GET["author"];
//        echo $servEng;
//        $mac = \RouterBase::getValidMac($mac);
//        if (strlen($mac)!= 12){
//            throw new Exception("Не верный MAC адрес ". $mac);
//        }
//        $mac = \RouterBase::getMacName($mac);
//
//
//        $atServEng = new \RouterState("atServEng", ["servEng" => $servEng, "author" => $author]);
//        $path = "/routers/byMac/";
//        $curMac = objLoad("/routers/byLogin/{$dnum}/user.info","raw")["mac"];
//        if ($curMac != $macOld){
//            $writeOff = new \RouterState("writeOff", ["author" => $author, "comment" => "Абонент {$dnum} вернул другой роутер {$macOld}"]);
//            $router = new \RouterBase($curMac);
//            $router->addState($writeOff);
//            $router->setNegDays(0);
//        }
//        
//        if (!objCheckExist($path. $mac, $type)){
//            \RouterBase::addRouter($mac, $atServEng);
//            $address = objLoad("/profiles/{$servEng}/profile.pro")["areas"];
//            if (preg_match("/Качар/i", $address)){
//                $city = "kchr";
//            }
//            else if (preg_match("/Лисаковск/i", $address)){
//                $city = "lsk";
//            }
//            else{
//                $city = "kst";
//            }
//            $router = new \RouterBase($mac);
//            $router->setNegDays(0);
//            $router->setCity($city);
//        }
//        else{
//            $router = new \RouterBase($mac);
//            $router->addState($atServEng);
//            $router->setNegDays(0);
//        }
//        echo "Роутер принят";
//
//    }
//    catch (\Exception $e){
//        echo $e->getMessage();
//    }
//}



check();













