<?php


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




/*--------------------------------------------------*/


function checkNew(
        $mac,
        $servEng
){
    $authPath = "/routers/byMac/";
    if (!objCheckExist($authPath. $mac. "/cstatus.info", "raw")){
        return ["message" => "MAC {$mac} не найден, будет добавлен в базу", "status" => 0];
    }
    else{
        $router = new \RouterBase($mac);
        $state = $router->getCStatus("curState");
        if (!\RouterState::checkChangeState($state, "installed")){
            return ["message" => "Роутер {$mac} не может быть установлен. Текущий статус роутера: {$state}", "status" => 1];
        }
        else if ($state == "atServEng"){
            $servEngCur = $router->getCStatus("inCharge");
            print_r($router->getCStatus());
            echo $servEng. " - ". $servEngCur. "!<br>";
            if ($servEng == $servEngCur){
                return ["message" => "Роутер {$mac} - OK", "status" => 0];
            }
            else{
                $uname = profileGetUsername($servEngCur);
                return ["message" => "Роутер {$mac} будет снят с сотрудника - {$uname}", "status" => 0];
            }
        }
        else if ($state == "store"){
            $storeMan = $router->getCStatus("inCharge");
            $uname = profileGetUsername($storeMan);
            return ["message" => "Роутер {$mac} будет снят со склада, ответственный - {$uname}", "status" => 0];
        }
    }
}


/*--------------------------------------------------*/

function check(){
    try{
        $macNew = $_GET["macNew"];
        $dnum = $_GET["dnum"];
        $servEng = $_GET["servEng"];
        

        $macNew = \RouterBase::getValidMac($macNew);
        if (strlen($macNew)!= 12){
            throw new Exception("Не верный MAC адрес ". $macNew);
        }
        $macNew = \RouterBase::getMacName($macNew);
        
        if (!objCheckExist("/routers/byLogin/{$dnum}/user.info", "raw")){
            throw new Exception("User {$dnum} is not exists");
        }
        $obj = objLoad("/routers/byLogin/{$dnum}/user.info");
        if ($obj["mac"]){
            if($obj["mac"] != $mac){
                throw new Exception("{$dnum} has MAC {$obj['mac']}");
            }
        }
        
        $chkNew = checkNew($macNew, $servEng);
        $error = $chkNew["status"] + $chkOld["status"];
        $message = ($chkNew["status"]) ? "Ошибка: " : "";
        $message .= $chkNew["message"]. " <br>";
        $view = new \View("/_modules/routers/html_templates/supInstallRouter/");
        $data = [];
        $data["message"] = $message;
        if ($error > 0){
            $errorMessage = "Роутер не может быть установлен";
            $data["error"] = $errorMessage;
            $view->show("header", $data);

        }
        else{
            $errorMessage = "Роутер может быть установлен";
            $data["error"] = $errorMessage;
            $data["macOld"] = $macOld;
            $data["macNew"] = $macNew;
            $data["dnum"] = $dnum;
            $data["servEng"] = $servEng;
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
        $macNew = $_GET["macNew"];
        $dnum = $_GET["dnum"];
        $servEng = $_GET["servEng"];
        $author = $_GET["author"];


        $macNew = \RouterBase::getValidMac($macNew);
        if (strlen($macNew)!= 12){
            throw new Exception("Не верный MAC адрес !". $macNew);
        }
        $macNew = \RouterBase::getMacName($macNew);

        $installed = new \RouterState("installed", ["dnum" => $dnum, "author" => $author, "installer" => $servEng]);
        $path = "/routers/byMac/";
        
        
        
        if (!objCheckExist($path. $macNew, $type)){
            \RouterBase::addRouter($macNew, $installed);
            $address = objLoad("/users/{$dnum}/user.vcard")["address"];
            if (preg_match("/Качар/i", $address)){
                $city = "kchr";
            }
            else if (preg_match("/Лисаковск/i", $address)){
                $city = "lsk";
            }
            else{
                $city = "kst";
            }
            $router = new \RouterBase($macNew);
            $router->setNegDays(0);
            $router->setCity($city);
        }
        else{
            $router = new \RouterBase($macNew);
            $router->addState($installed);
            $router->setNegDays(0);
        }
        echo "Роутер заменен";

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













