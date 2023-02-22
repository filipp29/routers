<?php




$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';



class RouterAuth {
    
    
    
    
    /*--------------------------------------------------*/
    
   
    static private function getLast(
            $obj
    ){
        $result = [];
        $max = 0;
        foreach($obj as $key => $value){
            $buf = explode('_', $key);
            if ((int)$buf[1] >= $max){
                $result[$buf[0]] = $value;
                $max = (int)$buf[1];
            }
        }
        return $result;
    }
    
    
    /*--------------------------------------------------*/
    
    
    static public function getLastByMac(
            $mac
    ){
        $buf = \RouterBase::getValidMac($mac);
        if (strlen($buf) != 12){
            throw new Exception("Wrong mac {$buf}");
        }
        $mac = "";
        for ($i = 0; $i < 12; $i++){
            if (($i % 2 == 0) && ($i > 0)){
                $mac .= ":";
            }
            $mac .= $buf[$i];
        }
        $path = "/authlog/byMAC/".$mac. "/";
        echo $path. "\n";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth not found");
        }
        rsort($br);
        $obj = objLoad($path. $br[0]);
        return self::getLast($obj);
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getLastByDnum(
            $dnum
    ){
        $path = "/authlog/byLogin/".$dnum. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth {$dnum} not found");
        }
        rsort($br);
        $obj = objLoad($path. $br[0]);
        return self::getLast($obj);
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    static public function getLastByBoth(
            $mac,
            $dnum
    ){
        $buf = \RouterBase::getValidMac($mac);
        if (strlen($buf) != 12){
            throw new Exception("Wrong mac {$buf}");
        }
        $mac = "";
        for ($i = 0; $i < 12; $i++){
            if (($i % 2 == 0) && ($i > 0)){
                $mac .= ":";
            }
            $mac .= $buf[$i];
        }
        $mac = strtoupper($mac);
        $path = "/authlog/byLogin/".$dnum. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth {$dnum} not found");
        }
        rsort($br);
        $max = -1;
        for ($i = 0; $i < count($br); $i++){
            $obj = objLoad($path. $br[$i]);
            $result = [];
            
            foreach($obj as $key => $value){
                $buf = explode("_", $key);
                $result[$buf[1]][$buf[0]] = $value;
                if (($buf[0] == "mac") &&($value == $mac)){
                    $max = (int)$buf[1];
                }
            }
            
            if ($max >= 0 ){
                return $result[$max];
            }
        }
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
}


//$result = "none";
//$type = readline("Type: ");
//if ($type == "mac"){
//    $mac = readline("MAC: ");
//    $result = \RouterAuth::getLastByMac($mac);
//}
//else if ($type == "dnum"){
//    $dnum = readline("Dnum: ");
//    $result = \RouterAuth::getLastByDnum($dnum);
//}
//else if ($type == "both"){
//    $dnum = readline("Dnum: ");
//    $mac = readline("MAC: ");
//    $result = \RouterAuth::getLastByBoth($mac, $dnum);
//}
//print_r($result);
