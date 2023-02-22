<?php

//
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
    $authPath = "/routers/byMac/";
    if (!objCheckExist($authPath. $mac. "/cstatus.info", "raw")){
        return ["message" => "MAC {$mac} �� ������, ����� �������� � ����", "status" => 0];
    }
    else{
        $router = new \RouterBase($mac);
        $state = $router->getCStatus("curState");
        if ($state == "installed"){
            $dnumCur = $router->getCStatus("dnum");
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





/*--------------------------------------------------*/

function check(){
    try{
        $mac = $_GET["mac"];
        
        $dnum = trim($_GET["dnum"]);
        $servEng = trim($_GET["servEng"]);
        $author = trim($_GET["author"]);
        $mac = \RouterBase::getValidMac($mac);
        if (strlen($mac)!= 12){
            throw new Exception("�� ������ MAC ����� ". $mac);
        }
        $mac = \RouterBase::getMacName($mac);

        $chkOld = checkOld($mac, $dnum);
        
        $error = $chkOld["status"];
        
        $message .= ($chkOld["status"]) ? "������: " : "";
        $message .= $chkOld["message"]. " ";
        $view = new \View("/_modules/routers/html_templates/supTakeRouter/");
        $data = [];
        $data["message"] = $message;
        if ($error > 0){
            $errorMessage = "������ �� ����� ���� ������";
            $data["error"] = $errorMessage;
            $view->show("header", $data);

        }
        else{
            $errorMessage = "������ ����� ���� ������";
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
            throw new Exception("�� ������ MAC ����� ". $mac);
        }
        $mac = \RouterBase::getMacName($mac);


        $atServEng = new \RouterState("atServEng", ["servEng" => $servEng, "author" => $author]);
        $path = "/routers/byMac/";
        $curMac = objLoad("/routers/byLogin/{$dnum}/user.info","raw")["mac"];
        if ($curMac != $macOld){
            $writeOff = new \RouterState("writeOff", ["author" => $author, "comment" => "������� {$dnum} ������ ������ ������ {$macOld}"]);
            $router = new \RouterBase($curMac);
            $router->addState($writeOff);
            $router->setNegDays(0);
        }
        
        if (!objCheckExist($path. $mac, $type)){
            \RouterBase::addRouter($mac, $atServEng);
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
            $router = new \RouterBase($mac);
            $router->setNegDays(0);
            $router->setCity($city);
        }
        else{
            $router = new \RouterBase($mac);
            $router->addState($atServEng);
            $router->setNegDays(0);
        }
        echo "������ ������";

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













