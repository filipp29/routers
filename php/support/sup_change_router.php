<?php


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
    $dnum = trim($dnum);
    $authPath = "/routers/byMac/";
    if (!objCheckExist($authPath. $mac. "/cstatus.info", "raw")){
        return ["message" => "MAC {$mac} не найден, будет добавлен в базу", "status" => 0];
    }
    else{
        $router = new \RouterBase($mac);
        $state = $router->getCStatus("curState");
        if ($state == "installed"){
            $dnumCur = $router->getCStatus("dnum");
//            echo "!".$dnum. "! !". $dnumCur. "!<br>";
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
            return ["message" => "Роутер {$mac} находится в статусе: {$stateNames[$state]}", "status" => 1];
        }
        
    }
}


/*--------------------------------------------------*/


function checkNew(
        $mac,
        $servEng
){
    $stateNames = \RouterState::getStateName();
    $authPath = "/routers/byMac/";
    if (!objCheckExist($authPath. $mac. "/cstatus.info", "raw")){
        return ["message" => "MAC {$mac} не найден, будет добавлен в базу", "status" => 0];
    }
    else{
        $router = new \RouterBase($mac);
        $state = $router->getCStatus("curState");
        if (!\RouterState::checkChangeState($state, "installed")){
            return ["message" => "Роутер {$mac} не может быть установлен. Текущий статус роутера: {$stateNames[$state]}", "status" => 1];
        }
        else if ($state == "atServEng"){
            $servEngCur = $router->getCStatus("inCharge");
//            print_r($router->getCStatus());
//            echo $servEng. " - ". $servEngCur. "!<br>";
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
        $macOld = $_GET["macOld"];
        $macNew = $_GET["macNew"];
        if ($macOld == $macNew){
            echo "Внимание, уважаемый диспетчер, вы пытаетесь привязать абоненту роутер, который там и так уже привязан. Пожалуйста проверьте, что вы все правильно делаете. Если вы считаете что делаете все верно попробуйте перезагрузить карточку абонента. Если не поможет сообщите в поддержку. <!--_ERROR-->";
            exit();
        }
        $dnum = $_GET["dnum"];
        $servEng = $_GET["servEng"];
        $author = $_GET["author"];
        $macOld = \RouterBase::getValidMac($macOld);
        if (strlen($macOld)!= 12){
            throw new Exception("Не верный MAC адрес ". $macOld);
        }
        $macOld = \RouterBase::getMacName($macOld);

        $macNew = \RouterBase::getValidMac($macNew);
        if (strlen($macNew)!= 12){
            throw new Exception("Не верный MAC адрес ". $macNew);
        }
        $macNew = \RouterBase::getMacName($macNew);

        $chkOld = checkOld($macOld, $dnum);
        $chkNew = checkNew($macNew, $servEng);
        $error = $chkNew["status"] + $chkOld["status"];
        $message = ($chkNew["status"]) ? "Ошибка: " : "";
        $message .= $chkNew["message"]. " <br>";
        $message .= ($chkOld["status"]) ? "Ошибка: " : "";
        $message .= $chkOld["message"]. " ";
        $data = [];
        $data["message"] = $message;
        if ($error > 0){
            $errorMessage = "Роутер не может быть земенен ";
            echo $errorMessage. "<!--_ERROR-->". $message;

        }
        else{
            $installed = new \RouterState("installed", ["dnum" => $dnum, "author" => $author, "installer" => $servEng]);
            $atServEng = new \RouterState("atServEng", ["servEng" => $servEng, "author" => $author]);
            $path = "/routers/byMac/";
            $curMac = objLoad("/routers/byLogin/{$dnum}/user.info","raw")["mac"];
            if ($curMac != $macOld){
                $writeOff = new \RouterState("writeOff", ["author" => $author, "comment" => "Абонент {$dnum} вернул другой роутер {$macOld}"]);
                $router = new \RouterBase($curMac);
                $router->addState($writeOff);
                $router->setNegDays(0);
            }
            if (!objCheckExist($path. $macOld, $type)){
                \RouterBase::addRouter($macOld, $atServEng);
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
                $router = new \RouterBase($macOld);
                $router->setNegDays(0);
                $router->setCity($city);
            }
            else{
                $router = new \RouterBase($macOld);
                $router->addState($atServEng);
                $router->setNegDays(0);
            }

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
            echo "Роутер заменен <!--_OK-->";
        }

    }
    catch (\Exception $e){
        echo $e->getMessage();
    }

}

/*--------------------------------------------------*/


function checked(){
    try{
        $macOld = $_GET["macOld"];
        $macNew = $_GET["macNew"];
        $dnum = $_GET["dnum"];
        $servEng = $_GET["servEng"];
        $author = $_GET["author"];

        $macOld = \RouterBase::getValidMac($macOld);
        if (strlen($macOld)!= 12){
            throw new Exception("Не верный MAC адрес ". $macOld);
        }
        $macOld = \RouterBase::getMacName($macOld);

        $macNew = \RouterBase::getValidMac($macNew);
        if (strlen($macNew)!= 12){
            throw new Exception("Не верный MAC адрес ". $macNew);
        }
        $macNew = \RouterBase::getMacName($macNew);

        $installed = new \RouterState("installed", ["dnum" => $dnum, "author" => $author, "installer" => $servEng]);
        $atServEng = new \RouterState("atServEng", ["servEng" => $servEng, "author" => $author]);
        $path = "/routers/byMac/";
        $curMac = objLoad("/routers/byLogin/{$dnum}/user.info","raw")["mac"];
        if ($curMac != $macOld){
            $writeOff = new \RouterState("writeOff", ["author" => $author, "comment" => "Абонент {$dnum} вернул другой роутер {$macOld}"]);
            $router = new \RouterBase($curMac);
            $router->addState($writeOff);
            $router->setNegDays(0);
        }
        if (!objCheckExist($path. $macOld, $type)){
            \RouterBase::addRouter($macOld, $atServEng);
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
            $router = new \RouterBase($macOld);
            $router->setNegDays(0);
            $router->setCity($city);
        }
        else{
            $router = new \RouterBase($macOld);
            $router->addState($atServEng);
            $router->setNegDays(0);
        }
        
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




    check();













