<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';




class RouterReports {
    
    static private $path = "/routers/reports/";
    
    
    /*--------------------------------------------------*/
    
    
    static public function addStateJournal(
            $mac,
            $stateAfter,
            $stateBefore
    ){
        
        $timeStamp = $stateAfter->getTimeStamp();
        
        $path = self::$path.  (string)date("Ymd", $timeStamp). "/state_journal/{$mac}/";
//        if (!objCheckExist($path. "info.info","raw")){
//            objSave($path. "info.info", "raw", ["number" => "0"]);
//        }
//        $number = (int)objLoad($path. "info.info", "raw")["number"];
//        $number++;
//        objSave($path. "info.info", "raw", ["number" => (string)$number]);
        $path = self::$path.  (string)date("Ymd", $timeStamp). "/state_journal/{$mac}/{$timeStamp}/";
        $before = [];
        $after = $stateAfter->getData();
        if ($stateBefore == null){
            $before["state"] = "none";
        }
        else{
            $before = $stateBefore->getData();
        }
        objSave($path. "after.info", "raw", $after);
        objSave($path. "before.info", "raw", $before);
    }
    
    /*--------------------------------------------------*/
    
    static public function addAlertJournal(
            $mac,
            $timeStamp,
            $text,
            $alertTime,
            $type
    ){
        $path = self::$path. (string)date("Ymd", $timeStamp). "/alert_journal/{$type}/{$mac}/{$timeStamp}/";
        $result = [
            "timeStamp" => $alertTime,
            "text" => $text
        ];
        
        objSave($path. "alert.info", "raw", $result);
    }
    
    /*--------------------------------------------------*/
    
    
}
