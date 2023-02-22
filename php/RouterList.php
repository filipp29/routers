<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД



class RouterList {
    static private $path = "/routers/list/";
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getMacList(
            $type
    ){
        $typeList = \RouterState::getStateList();
        if (!in_array($type, $typeList)){
            throw new Exception("Wrong type ". $type);
        }
        return array_keys(objLoadBranch(self::$path. $type. "/",false,true));
        
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    static public function getType(
            $mac
    ){
        $br = array_keys(objLoadBranch(self::$path, false, true));
        foreach($br as $key => $value){
            $path = self::$path. $value. "/{$mac}/router.info";
            if (objCheckExist($path, "raw")){
                return $value;
            }
        }
        return "none";
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    static public function setType(
            $mac,
            $type
    ){
        
        $typeList = \RouterState::getStateList();
        if (!in_array($type, $typeList)){
            throw new Exception("Wrong type ". $type);
        }
        $prev = self::getType($mac);
        if ($prev != "none"){
            objUnlinkBranch(self::$path. $prev. "/{$mac}/");
        }
        objSave(self::$path. $type. "/{$mac}/router.info", "raw", ["timeStamp" => time()]);
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    static public function remove(
            $mac,
            $del
    ){
        
        $typeList = array_keys(objLoadBranch(self::$path, false, true));
        
        foreach($typeList as $type){
            
            $path = self::$path. "{$type}/";
            $macList = array_keys(objLoadBranch($path, false, true));
            if (in_array($mac, $macList)){
                $pathMac = $path. "{$mac}/";
                echo $pathMac. "\n";
                if ($del){
                    objUnlinkBranch($pathMac);
                }
            }
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
}
