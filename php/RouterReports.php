<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';



class RouterReports {
    
    static private $path = "/routers/reports/";
    private $type = null;
    private $params = [];
    private $start = null;
    private $end = null;
    /*--------------------------------------------------*/
    
    
    public function __construct(
            $type,
            $params = []
    ){
        $this->type = $type;
        $this->setParams($params);
        if (isset($params["start"]) && ($params["start"])){
            $this->start = $params["start"];
            unset($this->params["start"]);
        }
        if (isset($params["end"]) && ($params["end"])){
            $this->end = $params["end"];
            unset($this->params["end"]);
        }
    }   
    
    
    
    
    /*--------------------------------------------------*/
    
    
    static public function addDailyReport(
            $timeStamp = null
    ){  
        if (!$timeStamp){
            $timeStamp = time();
        }
        $date = date("Ymd",$timeStamp);
        $lastTimeStamp = array_keys(objLoadBranch(self::$path. "{$date}/dailyReport/", false, true));
        rsort($lastTimeStamp);
        if ((int)$timeStamp - (int)$lastTimeStamp[0] > 21600){
            $stateList = array_keys(objLoadBranch("/routers/list/" , false, true));
            foreach($stateList as $state){
                $macList = array_keys(objLoadBranch("/routers/list/". $state. "/" , false, true));
                $path = self::$path. $date. "/". "dailyReport/".  $timeStamp."/". $state. ".info";
                objSave( $path , "raw", $macList);
            }
        }
        
    }
    
    
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
        objSave($path.  "after.info", "raw", $after);
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
    
    
    static public function checkCurrentAlert(){
        $date = date("Ymd",time());
        $path = self::$path. (string)date("Ymd", time()). "/alert_journal/";
        $typeList = array_keys(objLoadBranch($path, false, true));
        
        foreach ($typeList as $type){
            $macList = array_keys(objLoadBranch($path. "{$type}/", false, true));
            foreach($macList as $mac){
                $alertList = array_keys(objLoadBranch($path. "{$type}/{$mac}/"));
                $router = new \RouterBase($mac);
                $curState = $router->getCurState();
                $stateTime = $curState->getTimeStamp();
                foreach($alertList as $alert){
                    $obj = objLoad($path. "{$type}/{$mac}/{$alert}/alert.info");
                    if ((int)$stateTime > (int)$obj["timeStamp"]){
                        objUnlinkBranch($path. "{$type}/{$mac}/{$alert}/");
                        echo $path. "{$mac}/{$alert}/alert.info\n";
                    }
                }
            }
        }
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function setDateEnd(
            $timeStamp
    ){
        $this->end = (int)date("Y-m-d",(int)$timeStamp);
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function setDateStart(
            $timeStamp
    ){
        $this->start = (int)date("Y-m-d",(int)$timeStamp);
    }
    
    /*--------------------------------------------------*/
    
    
    public function setParams(
            $params
    ){
        $this->params = $params;
    }
    
    
    
    
    
    /*--------------------------------------------------*/
    
    
    public function getReport(){
        $buf = $this->type;
        return $this->{$buf}();
    }
    
    
    /*--------------------------------------------------*/
    
    private function alert_journal(){
        $result = [];
        
        $date = date("Ymd",time());
            
        $path = self::$path. (string)$date. "/alert_journal/";
        $br = array_keys(objLoadBranch($path, false, true));
        rsort($br);
        foreach($br as $type){
            $macList = array_keys(objLoadBranch($path. $type. "/", false, true));
            foreach($macList as $mac){
                $timeList = array_keys(objLoadBranch($path. $type. "/". $mac. "/", false, true));
                foreach($timeList as $timeStamp){
                    $buf = objLoad($path. $type. "/". $mac. "/". $timeStamp. "/alert.info","raw");
                    $buf["mac"] = $mac;
                    $buf["type"] = $type;
                    $result[] = $buf;
                }
            }
        }
        
        
//        if ($this->end){
//            $end = new DateTime($this->end);
//        }
//        else{
//            $end = new DateTime((int)date("Y-m-d",time()));
//        }
//        if ($this->start){
//            $start = new DateTime($this->start);
//        }
//        else{
//            $start = new DateTime("2008-01-01");
//        }
//        $interval = new DateInterval('P1D');
//        $period = new DatePeriod($start, $interval, $end);
//        foreach($period as $value){
//            $date = $value->format("Ymd");
//            
//            $path = self::$path. (string)$date. "/alert_journal/";
//            $br = array_keys(objLoadBranch($path, false, true));
//            rsort($br);
//            foreach($br as $type){
//                $macList = array_keys(objLoadBranch($path. $type. "/", false, true));
//                foreach($macList as $mac){
//                    $timeList = array_keys(objLoadBranch($path. $type. "/". $mac. "/", false, true));
//                    foreach($timeList as $timeStamp){
//                        $buf = objLoad($path. $type. "/". $mac. "/". $timeStamp. "/alert.info","raw");
//                        $buf["mac"] = $mac;
//                        $buf["type"] = $type;
//                        $result[] = $buf;
//                    }
//                }
//            }
//        }
        return $result;
    }
    
    
    /*--------------------------------------------------*/
    
    
    private function dailyReport(){
        $result = [];
        if ($this->end){
            $end = date("Ymd", $this->end);
        }
        else{
            $end = date("Ymd",time());
        }
        if ($this->start){
            $start = date("Ymd", $this->start);
        }
        else{
            $start = "";
        }
        $br = array_keys(objLoadBranch(self::$path. $end. "/dailyReport/", false, true));
        rsort($br);
        $path = self::$path. $end. "/dailyReport/". $br[0]. "/";
        $states = array_keys(objLoadBranch($path, true, false));
        foreach($states as $state){
            $buf = preg_replace("/\..+$/i", "", $state);
            $result["end"][$buf] = count(objLoad($path. $state,"raw"))-1;
            if ($result["end"][$buf] < 0) $result["end"][$buf] = 0;
        }
        if ($start){
            $br = array_keys(objLoadBranch(self::$path. $start. "/dailyReport/", false, true));
            rsort($br);
            $path = self::$path. $start. "/dailyReport/". $br[0]. "/";
            $states = array_keys(objLoadBranch($path, true, false));
            foreach($states as $state){
                $buf = preg_replace("/\..+$/i", "", $state);
                $result["start"][$buf] = count(objLoad($path. $state,"raw"))-1;
                if ($result["start"][$buf] < 0) $result["start"][$buf] = 0;
            }
        }
        return $result;
        
    }
    
    
    /*--------------------------------------------------*/
    
    
    private function stateJournal(){
        $result = [];
        $params = $this->params["state"];
        if ($this->end){
            $end = new DateTime();
            $end->setTimestamp((int)$this->end);
        }
        else{
            $end = new DateTime((int)date("Y-m-d",time()));
        }
        if ($this->start){
            $start = new DateTime();
            $start->setTimestamp((int)$this->start);
        }
        else{
            $start = new DateTime("2015-01-01");
        }
        
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);
        foreach($period as $value){
            $date = $value->format("Ymd");
            
            $path = self::$path. $date. "/state_journal/";
            $macList = array_keys(objLoadBranch($path, false, true));
            
            foreach($macList as $mac){
                $router = new \RouterBase($mac);
                if ($router->getCStatus("city") != $this->params["city"]){
                    continue;
                }
                $timeList = array_keys(objLoadBranch($path. "{$mac}/", false, true));
                
                rsort($timeList);
                foreach ($timeList as $time){
                    
                    $after = objLoad($path. "{$mac}/{$time}/after.info","raw");
                    if (!in_array($after["state"],$params)){
                        continue;
                    }
                    $timeAfter = date("H:i:s", $time);
                    $textAfter = "";
                    $before = objLoad($path. "{$mac}/{$time}/before.info","raw");
                    $textBefore = $before["state"];
                    $servEng = null;
                    if ($this->params["type"] == "status"){
                        switch($after["state"]){
                            case "installed":
                                $textAfter .= ": № договора: {$after["dnum"]}; Установил: ". profileGetUsername($after["installer"]). "; Диспетчер: ". profileGetUsername($after["author"]);
                                break;
                            case "inTaking":
                                $textAfter .= ": Автор: ". profileGetUsername($after["author"]);
                                break;
                            case "atServEng":
                                $textAfter .= ": Сервисный инженер: ". profileGetUsername($after["servEng"]). "; Диспетчер: ". profileGetUsername($after["author"]);
                                break;
                            case "toStore":
                                $textAfter .= ": Сервисный инженер: ". profileGetUsername($after["servEng"]);
                                break;
                            case "store":
                                $textAfter .= ": Склад: ". $after["store"]. "; Ответственный: ". profileGetUsername($after["author"]);
                                break;
                            case "writeOff":
                                $textAfter .= ": Автор: ". profileGetUsername($after["author"]);
                                break;
                            case "lost":
                                $textAfter .= ": Автор: ". profileGetUsername($after["author"]);
                                break;

                            default:
                                break;
                        }
                        switch($before["state"]){
                            case "installed":
                                $textBefore .= ": № договора: {$before["dnum"]}; Установил: ". profileGetUsername($before["installer"]). "; Диспетчер: ". profileGetUsername($before["author"]);
                                break;
                            case "inTaking":
                                $textBefore .= ": Автор: ". profileGetUsername($before["author"]);
                                break;
                            case "atServEng":
                                $textBefore .= ": Сервисный инженер: ". profileGetUsername($before["servEng"]). "; Диспетчер: ". profileGetUsername($before["author"]);
                                break;
                            case "toStore":
                                $textBefore .= ": Сервисный инженер: ". profileGetUsername($before["servEng"]);
                                break;
                            case "store":
                                $textBefore .= ": Склад: ". $before["store"]. "; Ответственный: ". profileGetUsername($before["author"]);
                                break;
                            case "writeOff":
                                $textBefore .= ": Автор: ". profileGetUsername($before["author"]);
                                break;
                            case "lost":
                                $textBefore .= ": Автор: ". profileGetUsername($before["author"]);
                                break;

                            default:
                                break;
                        }
//                    echo "<pre>";
//                    print_r($this->params);
//                    echo "</pre>";
                    
                        $result[$after["state"]][$date][$mac][$time]["text"] = $textAfter;
                        $result[$after["state"]][$date][$mac][$time]["time"] = $timeAfter;
                        $result[$after["state"]][$date][$mac][$time]["before"] = $textBefore;
                    }
                    if ($this->params["type"] == "servEng"){
                        switch($after["state"]):
                            case "installed":
                                $profile = $after["installer"];
                                $result[$profile]["installed"][$mac][$date."_".$timeAfter] = ": № договора: {$after["dnum"]}; Диспетчер: ". profileGetUsername($after["author"]);
                                break;
                            case "atServEng":
                                $profile = $after["servEng"];
                                $result[$profile]["atServEng"][$mac][$date."_".$timeAfter]= ": № договора: {$before["dnum"]}; Диспетчер: ". profileGetUsername($after["author"]);
                                break;
                        endswitch;
                    }
                }
            }
        }   
        return $result;
        
    }
    
    
    /*--------------------------------------------------*/
    
    static public function getServEngRouterList(){
        
    }
    
    
    /*--------------------------------------------------*/
    
    static public function remove(
            $mac,
            $del
    ){
        $dateList = array_keys(objLoadBranch(self::$path, false, true));
        foreach($dateList as $date){
            $pathDate = self::$path. "{$date}/";
            
            $typeList = array_keys(objLoadBranch($pathDate,false,true));
            foreach($typeList as $type){
                $pathType = $pathDate. "{$type}/";
                $macList = array_keys(objLoadBranch($pathType, false, true));
                if (in_array($mac, $macList)){
                    $pathMac = $pathType. "{$mac}/";
                    echo $pathMac. "\n";
                    if ($del){
                        objUnlinkBranch($pathMac);
                    }
                }
            }
        }
    }
    
}   

























