<?php

//
//error_reporting(E_ALL); //�������� ����� ������
//set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
//ini_set('display_errors', 1); //��� ���� ����� ������
//ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
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
            $dnumCur = $router->getCStatus("dnum");
            if ($dnum == $dnumCur){
                return ["message" => "������ {$mac} - OK", "status" => 0];
            }
            else{
                return["������ {$mac} ��������� � ��������� ������� � �������� - {$dnumCur}", "status" => 1];
            }
        }
        else {
            return ["message" => "������ {$mac} ��������� � �������: {$stateNames[$state]}", "status" => 1];
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
            "chkalova_16" => "������� 16",
            "mayakovskogo_120" => "����������� 120",
            "altinsarina_117" => "����������� 117",
            "gogol_62" => "������ 62",
            "tekstilshik_18" => "������������� 18",
            "plaza" => "�����",
            "lisakovsk" => "���������",
            "kachar" => "�����"
        ];
        $dnum = trim($_GET["dnum"]);
        isset($_GET["servEng"]) ? $servEng = trim($_GET["servEng"]) : $servEng = null;
        isset($_GET["store"]) ? $store = trim($_GET["store"]) : $store = null;
        $author = trim($_GET["author"]);
        $mac = \RouterBase::getValidMac($mac);
        if (strlen($mac)!= 12){
            throw new Exception("�� ������ MAC ����� ". $mac);
        }
        $mac = \RouterBase::getMacName($mac);

        $chkOld = checkOld($mac, $dnum);
        
        $error = $chkOld["status"];
        $message = '';
        $message .= ($chkOld["status"]) ? "������: " : "";
        $message .= $chkOld["message"]. " ";
        $data = [];
        $data["message"] = $message;
        if ($error > 0){
            $errorMessage = "������ �� ����� ���� ������ ";
            echo $errorMessage."<!--_ERROR-->". $message;

        }
        else{
            
            
            if ($servEng){
                $atServEng = new \RouterState("atServEng", ["servEng" => $servEng, "author" => $author]);
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
            }else if ($store){
                
                $atServEng = new \RouterState("store", ["store" => $store, "author" => $author]);
                $city = $cityList[$store];
            }
            $path = "/routers/byMac/";
            $curMac = objLoad("/routers/byLogin/{$dnum}/user.info","raw")["mac"];
            if ($curMac != $mac){
                $writeOff = new \RouterState("writeOff", ["author" => $author, "comment" => "������� {$dnum} ������ ������ ������ {$mac}"]);
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
            echo "������ ������ <!--_OK-->";
            
            
            
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
//            throw new Exception("�� ������ MAC ����� ". $mac);
//        }
//        $mac = \RouterBase::getMacName($mac);
//
//
//        $atServEng = new \RouterState("atServEng", ["servEng" => $servEng, "author" => $author]);
//        $path = "/routers/byMac/";
//        $curMac = objLoad("/routers/byLogin/{$dnum}/user.info","raw")["mac"];
//        if ($curMac != $macOld){
//            $writeOff = new \RouterState("writeOff", ["author" => $author, "comment" => "������� {$dnum} ������ ������ ������ {$macOld}"]);
//            $router = new \RouterBase($curMac);
//            $router->addState($writeOff);
//            $router->setNegDays(0);
//        }
//        
//        if (!objCheckExist($path. $mac, $type)){
//            \RouterBase::addRouter($mac, $atServEng);
//            $address = objLoad("/profiles/{$servEng}/profile.pro")["areas"];
//            if (preg_match("/�����/i", $address)){
//                $city = "kchr";
//            }
//            else if (preg_match("/���������/i", $address)){
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
//        echo "������ ������";
//
//    }
//    catch (\Exception $e){
//        echo $e->getMessage();
//    }
//}



check();













