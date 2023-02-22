<?php


error_reporting(E_ALL); //Включаем вывод ошибок
set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
ini_set('display_errors', 1); //Еще один вывод ошибок
ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once '../php/RouterBase.php';
require_once './RouterState.php';
require_once './Decoder.php';



$dec = new \Decoder();
$str = $_GET["data"];

try{
    $data = $dec->strToArray($str);
    $mac = \RouterBase::getValidMac($data["mac"]);
    if (strlen($mac)!= 12){
        throw new Exception("Не верный MAC адрес ". $data["mac"]);
    }
    $mac = \RouterBase::getMacName($mac);
    $stateList = \RouterState::getStateListAll();
    if (!key_exists($data["state"], $stateList)){
        throw new Exception("Не верный статус ". $data["state"]);
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
            throw new Exception("Не задан параметр ". $value);
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
        echo "Статус роутера изменен на ". \RouterState::getStateName()[$data["state"]];
    }
    else{
        echo "MAC не найден";
    }
    
}
catch (\Exception $e){
    echo $e->getMessage();
}


















