<?php


//error_reporting(E_ALL); //�������� ����� ������
//set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
//ini_set('display_errors', 1); //��� ���� ����� ������
//ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)

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
            throw new Exception("�� ����� �������� ". $value);
        }
    }
    if ($data["timeStamp"] != null){
        $timeStamp = $data["timeStamp"];
    }
    else{
        $timeStamp = time();
    }
}
catch (\Exception $e){
    echo $e->getMessage();
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
    if ($rout == null){
        \RouterBase::addRouter($mac, $state);
        $router = new \RouterBase($mac);
        $router->setCity($data["city"]);
        $router->setNegDays($data["negDays"]);
        $cStatus = $router->getCStatus();
        echo "������ ������� ��������<br>";
        echo $dec->arrayToStr($cStatus);
    }
    else{
        echo "MAC ����������";
    }
    
}
catch (\Exception $e){
    echo $e->getMessage();
}


















