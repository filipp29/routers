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
        $author = $_GET["author"];
        
        $macNew = \RouterBase::getValidMac($macNew);
        if (strlen($macNew)!= 12){
            throw new Exception("Не верный MAC адрес ". $macNew);
        }
        $macNew = \RouterBase::getMacName($macNew);
        
        $obj = objLoad("/routers/byLogin/{$dnum}/user.info");
        if (isset($obj["mac"])){
            if ($obj["mac"]){
                if(($obj["mac"] != $mac)&&($obj["mac"])){
                    throw new Exception("{$dnum} уже установлен роутер {$obj['mac']} <!--_ERROR-->");
                }
            }
        }
        
        $chkNew = checkNew($macNew, $servEng);
        
        $error = $chkNew["status"];// + $chkOld["status"]; - $chkOld is undefined
        $message = ($chkNew["status"]) ? "Ошибка: " : "";
        $message .= $chkNew["message"]. " <br>";
        $data = [];
        $data["message"] = $message;
        if ($error > 0){
            $errorMessage = "Роутер не может быть установлен ";
            echo $errorMessage. "<!--_ERROR-->" .$message; 

        }
        else{
            
            $installed = new \RouterState("installed", ["dnum" => $dnum, "author" => $author, "installer" => $servEng]);
            $path = "/routers/byMac/";
            if (!objCheckExist($path. $macNew, 'raw')){
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
            echo "Роутер Установлен <!--_OK-->";
            
            
            
        }

    }
    catch (\Exception $e){
        echo $e->getMessage();
    }

}

/*--------------------------------------------------*/






check();












