<?php


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
        $macNew = $_GET["macNew"];
        $dnum = $_GET["dnum"];
        $servEng = $_GET["servEng"];
        $author = $_GET["author"];
        
        $macNew = \RouterBase::getValidMac($macNew);
        if (strlen($macNew)!= 12){
            throw new Exception("�� ������ MAC ����� ". $macNew);
        }
        $macNew = \RouterBase::getMacName($macNew);
        
        $obj = objLoad("/routers/byLogin/{$dnum}/user.info");
        if (isset($obj["mac"])){
            if ($obj["mac"]){
                if(($obj["mac"] != $mac)&&($obj["mac"])){
                    throw new Exception("{$dnum} ��� ���������� ������ {$obj['mac']} <!--_ERROR-->");
                }
            }
        }
        
        $chkNew = checkNew($macNew, $servEng);
        
        $error = $chkNew["status"];// + $chkOld["status"]; - $chkOld is undefined
        $message = ($chkNew["status"]) ? "������: " : "";
        $message .= $chkNew["message"]. " <br>";
        $data = [];
        $data["message"] = $message;
        if ($error > 0){
            $errorMessage = "������ �� ����� ���� ���������� ";
            echo $errorMessage. "<!--_ERROR-->" .$message; 

        }
        else{
            
            $installed = new \RouterState("installed", ["dnum" => $dnum, "author" => $author, "installer" => $servEng]);
            $path = "/routers/byMac/";
            if (!objCheckExist($path. $macNew, 'raw')){
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
            echo "������ ���������� <!--_OK-->";
            
            
            
        }

    }
    catch (\Exception $e){
        echo $e->getMessage();
    }

}

/*--------------------------------------------------*/






check();












