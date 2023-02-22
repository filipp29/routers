<?php


error_reporting(E_ALL); //�������� ����� ������
set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
ini_set('display_errors', 1); //��� ���� ����� ������
ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once '../php/RouterBase.php';
require_once './RouterState.php';
require_once './Decoder.php';



$dec = new \Decoder();
$str = $_GET["data"];

try{
    $data = $dec->strToArray($str);
    $mac = \RouterBase::getValidMac($data["mac"]);
    if (strlen($mac)!= 12){
        throw new Exception("�� ������ MAC ����� ". $data["mac"]);
    }
    $mac = \RouterBase::getMacName($mac);
    $stateList = \RouterState::getStateListAll();
    if (!key_exists($data["state"], $stateList)){
        throw new Exception("�� ������ ������ ". $data["state"]);
    }
    $paramKeys = $stateList[$data["state"]]["params"];
    $params = [];
    
    foreach($paramKeys as $key => $value){
        if (key_exists($value, $data["params"])){
            $params[$value] = $data["params"][$value];
        }
        else{
            if ($value == "installer"){
                $params[$value] = "UNKNOUWN";
                continue;
            }
            throw new Exception("�� ����� �������� ". $value);
        }
    }
    if (isset($params["dnum"])){
        if (!objCheckExist("/routers/byLogin/{$params['dnum']}/user.info", "raw")){
            throw new Exception("User {$params['dnum']} is not exists");
        }
        $obj = objLoad("/routers/byLogin/{$params['dnum']}/user.info");
        if ($obj["mac"]){
            if($obj["mac"] != $mac){
                throw new Exception("{$params['dnum']} has MAC {$obj['mac']}");
            }
        }
    }
    if (isset($data["timeStamp"])){
        $timeStamp = $data["timeStamp"];
    }
    else{
        $timeStamp = time();
    }
}
catch (\Exception $e){
    echo $e->getMessage();
    exit();
}

if (objCheckExist("/routers/byMac/". $mac. "/cstatus.info", "raw")){
    $rout = new \RouterBase($mac);
}
else{
    $rout = null;
}

try{
    $state = new \RouterState($data["state"], $params);
    $state->setTimeStamp($timeStamp);
    if ($rout != null){
//        if (!$data["city"]){
//            $data["city"] = $rout->getCStatus("city");
//        }
//        if (!$data["negDays"]){
//            $data["negDays"] = $rout->getCStatus("negDays");
//        }
        $rout->addState($state);
        $router = new \RouterBase($mac);
        if ((isset($data["city"])) && ($data["city"])){
            $router->setCity($data["city"]);
        }
//        $router->setCity($data["city"]);
//        $router->setNegDays($data["negDays"]);
        $cStatus = $router->getCStatus();
        echo "������ ������� ������� �� ". \RouterState::getStateName()[$data["state"]];
    }
    else{
        echo "MAC �� ������";
    }
    
}
catch (\Exception $e){
    echo $e->getMessage();
}


















