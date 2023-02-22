<?php


//error_reporting(E_ALL); //�������� ����� ������
//set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
//ini_set('display_errors', 1); //��� ���� ����� ������
//ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
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
    $dnum = trim($dnum);
    $authPath = "/routers/byMac/";
    if (!objCheckExist($authPath. $mac. "/cstatus.info", "raw")){
        return ["message" => "MAC {$mac} �� ������, ����� �������� � ����", "status" => 0];
    }
    else{
        $router = new \RouterBase($mac);
        $state = $router->getCStatus("curState");
        if ($state == "installed"){
            $dnumCur = $router->getCStatus("dnum");
//            echo "!".$dnum. "! !". $dnumCur. "!<br>";
            if ($dnum == $dnumCur){
                return ["message" => "������ {$mac} - OK", "status" => 0];
            }
            else{
                return ["message" => "������ {$mac} �������� �� ������ ��������� - {$dnumCur}", "status" => 1];
            }
        }
        else if ($state == "inTaking"){
            if ($dnum == $dnumCur){
                return ["message" => "������ {$mac} - OK", "status" => 0];
            }
            else{
                return["������ {$mac} ��������� � ��������� ������� � �������� - {$dnumCur}", "status" => 1];
            }
        }
        else {
            return ["message" => "������ {$mac} ��������� � �������: {$state}", "status" => 1];
        }
        
    }
}


/*--------------------------------------------------*/


function checkNew(
        $mac,
        $servEng
){
    $authPath = "/routers/byMac/";
    if (!objCheckExist($authPath. $mac. "/cstatus.info", "raw")){
        return ["message" => "MAC {$mac} �� ������, ����� �������� � ����", "status" => 0];
    }
    else{
        $router = new \RouterBase($mac);
        $state = $router->getCStatus("curState");
        if (!\RouterState::checkChangeState($state, "installed")){
            return ["message" => "������ {$mac} �� ����� ���� ����������. ������� ������ �������: {$state}", "status" => 1];
        }
        else if ($state == "atServEng"){
            $servEngCur = $router->getCStatus("inCharge");
//            print_r($router->getCStatus());
//            echo $servEng. " - ". $servEngCur. "!<br>";
            if ($servEng == $servEngCur){
                return ["message" => "������ {$mac} - OK", "status" => 0];
            }
            else{
                $uname = profileGetUsername($servEngCur);
                return ["message" => "������ {$mac} ����� ���� � ���������� - {$uname}", "status" => 0];
            }
        }
        else if ($state == "store"){
            $storeMan = $router->getCStatus("inCharge");
            $uname = profileGetUsername($storeMan);
            return ["message" => "������ {$mac} ����� ���� �� ������, ������������� - {$uname}", "status" => 0];
        }
    }
}


/*--------------------------------------------------*/

function check(){
    try{
        $macOld = $_GET["macOld"];
        $macNew = $_GET["macNew"];
        $dnum = $_GET["dnum"];
        $servEng = $_GET["servEng"];

        $macOld = \RouterBase::getValidMac($macOld);
        if (strlen($macOld)!= 12){
            throw new Exception("�� ������ MAC ����� ". $macOld);
        }
        $macOld = \RouterBase::getMacName($macOld);

        $macNew = \RouterBase::getValidMac($macNew);
        if (strlen($macNew)!= 12){
            throw new Exception("�� ������ MAC ����� ". $macNew);
        }
        $macNew = \RouterBase::getMacName($macNew);

        $chkOld = checkOld($macOld, $dnum);
        $chkNew = checkNew($macNew, $servEng);
        $error = $chkNew["status"] + $chkOld["status"];
        $message = ($chkNew["status"]) ? "������: " : "";
        $message .= $chkNew["message"]. " <br>";
        $message .= ($chkOld["status"]) ? "������: " : "";
        $message .= $chkOld["message"]. " ";
        $view = new \View("/_modules/routers/html_templates/supChangeRouter/");
        $data = [];
        $data["message"] = $message;
        if ($error > 0){
            $errorMessage = "������ �� ����� ���� �������";
            $data["error"] = $errorMessage;
            $view->show("header", $data);

        }
        else{
            $errorMessage = "������ ����� ���� �������";
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
        $macOld = $_GET["macOld"];
        $macNew = $_GET["macNew"];
        $dnum = $_GET["dnum"];
        $servEng = $_GET["servEng"];
        $author = $_GET["author"];

        $macOld = \RouterBase::getValidMac($macOld);
        if (strlen($macOld)!= 12){
            throw new Exception("�� ������ MAC ����� ". $macOld);
        }
        $macOld = \RouterBase::getMacName($macOld);

        $macNew = \RouterBase::getValidMac($macNew);
        if (strlen($macNew)!= 12){
            throw new Exception("�� ������ MAC ����� ". $macNew);
        }
        $macNew = \RouterBase::getMacName($macNew);

        $installed = new \RouterState("installed", ["dnum" => $dnum, "author" => $author, "installer" => $servEng]);
        $atServEng = new \RouterState("atServEng", ["servEng" => $servEng, "author" => $author]);
        $path = "/routers/byMac/";
        $curMac = objLoad("/routers/byLogin/{$dnum}/user.info","raw")["mac"];
        if ($curMac != $macOld){
            $writeOff = new \RouterState("writeOff", ["author" => $author, "comment" => "������� {$dnum} ������ ������ ������ {$macOld}"]);
            $router = new \RouterBase($curMac);
            $router->addState($writeOff);
            $router->setNegDays(0);
        }
        if (!objCheckExist($path. $macOld, $type)){
            \RouterBase::addRouter($macOld, $atServEng);
            $address = objLoad("/profiles/{$servEng}/profile.pro")["areas"];
            if (preg_match("/�����/i", $address)){
                $city = "kchr";
            }
            else if (preg_match("/���������/i", $address)){
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
            if (preg_match("/�����/i", $address)){
                $city = "kchr";
            }
            else if (preg_match("/���������/i", $address)){
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
        echo "������ �������";

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













