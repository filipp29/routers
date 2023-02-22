<?php


$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterState.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterTicket.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterSupport.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterReports.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterList.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterAlert.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Log.php';
//error_reporting(E_ALL); //Включаем вывод ошибок
//set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
//ini_set('display_errors', 1); //Еще один вывод ошибок
//ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)














class RouterBase {
    
    private $states = [];                                                                                                                            
    private $support;                                                                                                                                                                                  
    private $isState = false;
    static private $path = "/routers/";
    private $mac = null;
    private $cStatus = [
        "negDays" => null,
        "curState" => null,
        "ticket" => null,
        "city" => null,
        "mac" => null,
        "name" => null,
        "address" => null,
        "inCharge" => null,
        "dnum" => null,
        "comment" => null,
        "author" => null,
        "store" => null
    ];
    static private $storeName = [
        "chkalova_16" => "Чкалова 16",
        "mayakovskogo_120" => "Маяковского 120",
        "altinsarina_117" => "Алтынсарина 117",
        "gogol_62" => "Гоголя 62",
        "tekstilshik_18" => "Текстильщиков 18",
        "plaza" => "Плаза",
        "lisakovsk" => "Лисаковск",
        "kachar" => "Качар",
        "kzhbi" => "Склад КЖБИ",
        "ksk" => "Склад КСК",
        "centr" => "Склад центр"
    ];
    
    /*--------------------------------------------------*/
    
    
    private function connectToDb(){
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function __construct(
            $mac
    ) {
        
        $mac = self::getValidMac($mac);
        if (strlen($mac) != 12){
            throw new Exception("Wrong MAC");
        }
        $mac = self::getMacName($mac);
        if (!objCheckExist(self::$path. "byMac/". $mac. "/cstatus.info", "raw")){
            throw new Exception("MAC not found");
        }
        $this->mac = $mac;
        $params = objLoad(self::$path. "byMac/". $mac. "/cstatus.info");
        
        foreach($this->cStatus as $key => $value){
            if (array_key_exists($key, $params)){
                $this->cStatus[$key] = $params[$key];
            }
            else {
                $this->cStatus[$key] = "";
            }
        }
        
        
        
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getValidMac(
            $mac
    ){
        $result = '';
        for ($i = 0; $i<strlen($mac); $i++){
            if (preg_match("/[a-f0-9$]/i", $mac[$i])){
                $result .= $mac[$i];
            }
        }
        return $result;
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getMacList(
            $type,
            $mac = ""
    ){
        $mac = self::getValidMac($mac);
        $mac = "/{$mac}/i";
        $result = [];
        $macList = \RouterList::getMacList($type);
        foreach($macList as $key => $value){
            $buf = self::getValidMac($value);
            
            if (preg_match($mac, $buf)){
                $result[] = $value;
            }
        }
        return $result;
        
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    
   
    

    static public function checkNegative(
            $dnum
    ){
        $obj=objLoad("/users/{$dnum}/user.vcard", 'raw');
        if (((int)$obj["balance"]) < 0){
            return true;
        }
        else {
            return false;
        }
        
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getProfileName(
            $profile
    ){
        return objLoad("/profiles/{$profile}/profile.pro")["uname"];
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    static public function getStoreName(
            $store
    ){
        return self::$storeName[$store];
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getMacName(
            $mac
    ){
        $buf = self::getValidMac($mac);
        if (strlen($buf) != 12){
            throw new Exception("Wrong mac {$buf}");
        }
        $buf = strtoupper($buf);
        $result = "";
        for ($i = 0; $i < 12; $i++){
            if (($i % 2 == 0) && ($i > 0)){
                $result .= ":";
            }
            $result .= $buf[$i];
        }
        return $result;
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    static public function getMacByLogin(
            $login
    ){
        $path = self::$path. "byLogin/{$login}/user.info";
        return (isset(objLoad($path, "raw")["mac"])) ? objLoad($path, "raw")["mac"] : "";
    }
    
    
    
    
    
    
    /*--------------------------------------------------*/
    
    
    public function getNegDays(){
        return $this->cStatus["negDays"];
    }
    
    
    
    
    
    
    /*--------------------------------------------------*/
    
    
    public function getLastAuth(){
        $buf = str_split($this->mac, 2);
        $mac = $buf[0];
        foreach ($buf as $k => $v){
            if ($k == 0){
                continue;
            }
            $mac .= ":". $v;
        }
        $path = "/authlog/byMAC/". $mac. "/";
        $files = array_keys(objLoadBranch($path, true, false));
        if (count($files) == 0){
            return "";
        }
        rsort($files);
        $result = 0;
        $obj = objLoad($path. $files[0], "raw");
        foreach($obj as $key => $value){
            $buf = explode("_", $key);
            if (count($buf) > 1){
                $timeStamp = (int)$buf[1];
            }
            else{
                continue;
            }
            if ($timeStamp > $result){
                $result = $timeStamp;
            }
        }
        return (string)$result;
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function setNegDays(
            $days
    ){
        $this->cStatus["negDays"] = (string)$days;
        $path = self::$path. "byMac/". $this->mac. "/cstatus.info";
        objSave($path, "raw", $this->cStatus);
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function setCity(
            $city
    ){
        $this->cStatus["city"] = (string)$city;
        $path = self::$path. "byMac/". $this->mac. "/cstatus.info";
        objSave($path, "raw", $this->cStatus);
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    
    public function addAlert(
            $type,
            $value,
            $alertTime
    ){
        $alert = new \RouterAlert($this->mac, $this->getCStatus("curState"));
        $timeStamp = time();
        $this->setState();
        $stateTimeStamp = $this->states[0]->getTimeStamp();
        
        if ($alert->addAlert($type, $value, $timeStamp,$alertTime,$stateTimeStamp)){
            $text = $alert->getData($timeStamp)["text"];
            \RouterReports::addAlertJournal($this->mac, $timeStamp, $text, $alertTime, $type);
        }
        
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    public function deleteAllStates(){
        $path = self::$path. "byMac/". $this->mac. "/status/";
        $br = array_keys(objLoadBranch($path, true, false));
        foreach($br as $value){
            objKill($path. $value);
        }
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function addRouter(
            $mac,
            \RouterState $rState
    ){
        $mac = self::getValidMac($mac);
        if (strlen($mac)!=12){
            throw new Exception("Wrong Mac");
        }
        $mac = self::getMacName($mac);
        $data = [];
        $data["mac"] = $mac;
        $data["negDays"] = "0";
        $path = self::$path. "byMac/". $mac. "/cstatus.info";
        if (objCheckExist($path, "raw")){
            throw new Exception("MAC address exists");
        }
        
        objSave($path, "raw", $data);
        $data = [
            "checked" => "0"
        ];
        $path = self::$path. "byMac/". $mac. "/checked.info";
        objSave($path, "raw", $data);
        $router = new \RouterBase($mac);
        $router->addState($rState, true);
        
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    private function setState(){
        $this->states = [];
        $bs = array_keys(objLoadBranch(self::$path. "byMac/". $this->mac. "/status/", true, false));
        rsort($bs);
        
        for ($i = 0; $i < count($bs); $i++){
            $obj = objLoad(self::$path. "byMac/". $this->mac. "/status/". $bs[$i]);
            $state = $obj["state"];
            unset($obj["state"]);
            unset($obj["#e"]);
            $timeStamp = explode(".", $bs[$i])[0];
            $this->states[$i] = new RouterState($state,$obj,$bs);
            $this->states[$i]->setTimeStamp($timeStamp);
        }
        $this->support = new \RouterSupport($this->states);
        $this->isState = true;
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    public function isChecked(){
        $path = self::$path. "byMac/". $this->mac. "/checked.info";
        $obj = objLoad($path);
        return $obj["checked"];
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function setChecked(){
        $data = [
            "checked" => "1"
        ];
        $path = self::$path. "byMac/". $this->mac. "/checked.info";
        objSave($path, "raw", $data);
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    public function getCurState(){
        if (!$this->isState){
            $this->setState();
        }
        return $this->states[0];
    }
    
    
   
    
    
    /*--------------------------------------------------*/
    
    
    public function getAllStates(){
        if (!$this->isState){
            $this->setState();
        }
        return $this->states;
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    private function closeTicket(
            $end,
            $type,
            $timeStamp = null
    ){
        if ($timeStamp === null){
            $timeStamp = (string)time();
        }
        $ticket = new \RouterTicket($this->mac, $type);
        $ticket->closeTicket($end,$timeStamp);
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    private function openTicket(
            $start,
            $type,
            $timeStamp = null
            
    ){
        if ($timeStamp === null){
            $timeStamp = (string)time();
        }
//        $type = "inTaking";
        $ticket = new \RouterTicket($this->mac, $type);
        $ticket->openTicket($start,$timeStamp);
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    public function remove(
            $del
    ){
        $path = $path = self::$path. "byMac/". $this->mac. "/";
        echo $path."\n";
        if ($del){
            objUnlinkBranch($path);
        }
        if (isset($this->cStatus["dnum"])){
            $obj = objLoad(self::$path. "byLogin/{$dnum}/user.info");
            if ($obj["mac"] == $this->mac){
                $pathLogin = self::$path. "byLogin/{$dnum}/";
                echo $pathLogin. "\n";
                if ($del){
                    objUnlinkBranch($pathLogin);
                }
            }
        }
        \RouterList::remove($this->mac,$del);
        \RouterReports::remove($this->mac,$del);
        
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    public function addState(
            \RouterState $rState,
            $noCheck = false
    ){ 
        $stateAfter = $rState;
        $stateBefore = null;
        
        if (!$noCheck){
            if (!$this->isState){
                $this->setState();
            }
            if (!$this->states[0]->checkAfter($rState->getState())){
                throw new Exception("Wrong state ". $this->states[0]->getState(). "-". $rState->getState());
            }
            $stateBefore = $this->states[0];
        }
        if ($rState->getTimeStamp() == null){
            $rState->setTimeStamp(time());
        }
//        echo date("Y-m-d H:i:s",$rState->getTimeStamp());
        if ($stateBefore != null){
            $buf = $stateBefore->getState();
            $this->{$buf."_close"}($rState);
        }
        $buf = $rState->getState();
        
        $this->{$buf}($rState);
        $this->setState();
        if (($rState->getParams("installer") == "UNKNOUWN") && (isset($this->states[0]))){
            if ($this->states[0]->getParams("installer")){
                $rState->setParams(["installer" => $this->states[0]->getParams("installer")]);
            }
        }
        
        
        $data = $rState->getData();
        $file = $rState->getTimeStamp();
        $path = self::$path. "byMac/". $this->mac. "/status/". $file. ".state";
        objSave($path, "raw", $data);
        $path = self::$path. "byMac/". $this->mac. "/cstatus.info";
        
        objSave($path, "raw", $this->cStatus);
        
        $this->setState();
        
//        $this->setCity($city);
//        $this->setNegDays($negDays);
        
        $text = "Status changed ". $this->cStatus["curState"];
        $author = "SYSTEM";
        $this->addComment($text, $author,$file);
        \RouterReports::addStateJournal($this->mac,$stateAfter, $stateBefore);
        \RouterList::setType($this->mac, $rState->getState());
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    private function setCStatus(
            $params
    ){
        
        $params["mac"] = $this->mac;
        foreach($this->cStatus as $key => $value){
            
            if (array_key_exists($key, $params)){
                $this->cStatus[$key] = $params[$key];
            }
            else {
                $this->cStatus[$key] = "";
            }
            
        }
        $path = self::$path. "byMac/". $this->mac. "/cstatus.info";
        objSave($path, "raw", $this->cStatus);
        $obj = objLoad($path);
        objSave("/routers/store.temp", "raw",$obj);
        
        
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    private function installed(
            \RouterState $rState
    ){
        $dnum = $rState->getParams("dnum");
        $userData = objLoad("/users/{$dnum}/user.vcard", 'raw');
        $params = [];
        
        $params["curState"] = $rState->getState();
        $city = $this->getCStatus("city");
        $negDays = $this->getCStatus("negDays");
        $params["mac"] = $this->mac;
        $params["name"] = $userData["uname"];
        $params["address"] = $userData["address"];
        $params["dnum"] = $userData["dnum"];
        $path = self::$path. "byLogin/".$dnum."/user.info";
        $data["mac"] = $this->mac;
        objSave($path, "raw", $data);
        
        $this->setCStatus($params);
        $this->setCity($city);
        $this->setNegDays($negDays);
    }
    
    
    
    private function installed_close(){
        $path = self::$path. "byLogin/".$this->cStatus["dnum"]."/user.info";
        $data["mac"] = "";
        objSave($path, "raw", $data);
    }
    
    
    /*--------------------------------------------------*/
    
    
    private function inTaking(
            \RouterState $rState
    ){
        $dnum = $rState->getParams("dnum");
        $userData = objLoad("/users/{$dnum}/user.vcard", 'raw');
        $ticket = new \RouterTicket($this->mac, "inTaking");
        if (!$ticket->isOpened()){
            $this->openTicket($rState,"inTaking", $rState->getTimeStamp());
        }
        
        $params = [];
        $params["curState"] = $rState->getState();
        $city = $this->getCStatus("city");
        $negDays = $this->getCStatus("negDays");
        
        $params["mac"] = $this->mac;
        $params["name"] = $userData["uname"];
        $params["address"] = $userData["address"];
        $params["dnum"] = $userData["dnum"];
        
        $path = self::$path. "byLogin/".$dnum."/user.info";
        $data["mac"] = $this->mac;
        objSave($path, "raw", $data);
       
        $this->setCStatus($params);
       
        $this->setCity($city);
        $this->setNegDays($negDays);
        
    }
    
    
    private function inTaking_close(
            $rState
    ){
        $ticket = new \RouterTicket($this->mac, "inTaking");
        if ($ticket->isOpened()){
            $this->closeTicket($rState,"inTaking", $rState->getTimeStamp());
        }
        $path = self::$path. "byLogin/".$this->cStatus["dnum"]."/user.info";
        $data["mac"] = "";
        objSave($path, "raw", $data);
    }
    
    
    /*--------------------------------------------------*/
    
    
    private function atServEng(
            \RouterState $rState
    ){
        $params = [];
        $city = $this->cStatus["city"];
        $negDays = "0";
        $params["curState"] = $rState->getState();
        $params["city"] = $this->cStatus["city"];
        $params["mac"] = $this->mac;
        $params["inCharge"] = $rState->getParams("servEng");
        $this->setCStatus($params);
        $this->setCity($city);
        $this->setNegDays($negDays);
    }
    
    
    private function atServeng_close(){
        
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    private function toStore(
            \RouterState $rState
    ){
        $params = [];
        $city = $this->cStatus["city"];
        $negDays = "0";
        $params["curState"] = $rState->getState();
        $params["city"] = $this->cStatus["city"];
        $params["mac"] = $this->mac;
        $params["inCharge"] = $rState->getParams("servEng");
        $this->setCStatus($params);
        $this->setCity($city);
        $this->setNegDays($negDays);
    }
    
    
    private function toStore_close(){
        
    }
    
    /*--------------------------------------------------*/
    
    
    
    
    private function store(
            \RouterState $rState
    ){
        $params = [];
        $city = $this->cStatus["city"];
        $negDays = "0";
        $params["curState"] = $rState->getState();
//        $params["author"] = $rState->getParams("author");
        $params["store"] = $rState->getParams("store");
        
        $params["mac"] = $this->mac;
        $params["city"] = $this->cStatus["city"];
        $this->setCStatus($params);
        $this->setCity($city);
        $this->setNegDays($negDays);
    }
    
    private function store_close(){
        
    }
    
    /*--------------------------------------------------*/
    
    
    
    private function testing(
            \RouterState $rState
    ){
        $params = [];
        $city = $this->cStatus["city"];
        $negDays = "0";
        $params["curState"] = $rState->getState();
        $params["inCharge"] = $rState->getParams("tester");
        $params["mac"] = $this->mac;
        $params["city"] = $this->cStatus["city"];
        $this->setCStatus($params);
        $this->setCity($city);
        $this->setNegDays($negDays);
    }
    
    private function testing_close(){
        
    }
    
    /*--------------------------------------------------*/
    
    
    
    private function writeOff(
            \RouterState $rState
    ){
        $params = [];
        $city = $this->cStatus["city"];
        $negDays = "0";
        $params["mac"] = $this->mac;
        $params["city"] = $this->cStatus["city"];
        $params["curState"] = $rState->getState();
        $params["inCharge"] = $rState->getParams("profile");
        $params["comment"] = $rState->getParams("comment");
        $this->setCStatus($params);
        $this->setCity($city);
        $this->setNegDays($negDays);
    }
    
    private function writeOff_close(){
        
    }
    
    /*--------------------------------------------------*/
    
    
    private function delete(
            \RouterState $rState
    ){
        $params = [];
        $city = $this->cStatus["city"];
        $negDays = "0";
        $params["mac"] = $this->mac;
        $params["city"] = $this->cStatus["city"];
        $params["curState"] = $rState->getState();
        $params["inCharge"] = $rState->getParams("profile");
        $params["comment"] = $rState->getParams("comment");
        $params["author"] = $rState->getParams("author");
        $this->setCStatus($params);
        $this->setCity($city);
        $this->setNegDays($negDays);
    }
    private function delete_close(){
        
    }
    
    
    /*--------------------------------------------------*/
    
    private function lost(
            \RouterState $rState
    ){
        $params = [];
        $city = $this->cStatus["city"];
        $negDays = "0";
        $params["mac"] = $this->mac;
        $params["city"] = $this->cStatus["city"];
        $params["curState"] = $rState->getState();
        $params["inCharge"] = $rState->getParams("profile");
        $params["comment"] = $rState->getParams("comment");
        $this->setCStatus($params);
        $this->setCity($city);
        $this->setNegDays($negDays);
    }
    private function lost_close(){
        
    }
    
    /*--------------------------------------------------*/
    
    
    
    
     public function getCStatus(
             $key = null
    ){
        if (!$key){
            return $this->cStatus;
        }
        else {
            if ($key == "address"){
                $dnum = $this->cStatus["dnum"];
                return objLoad("/users/{$dnum}/user.vcard")["address"];
            }
            if ($key == "name"){
                $dnum = $this->cStatus["dnum"];
                return objLoad("/users/{$dnum}/user.vcard")["uname"];
            }
            return $this->cStatus[$key];
        }
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    public function check(
            $params
    ){
        
        $result = true;
        foreach($params as $key => $value){
            $pattern = "/{$value}/i";
            $pattern = mb_strtolower($pattern);
            
            $buf = mb_strtolower($this->cStatus[$key]);
            if ($key == "mac"){
                $buf = self::getValidMac($buf);
                $pattern = "/".self::getValidMac($pattern)."/i";
            }
            if (!preg_match($pattern, $buf)){
                $result = false;
                return $result;
            }
        }
        return $result;
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    public function addComment(
            $text,
            $author,
            $timeStamp = null
    ){
        if ($timeStamp === null){
            $timeStamp = time();
        }
        $path = self::$path. "byMac/". $this->mac. "/comments/". (string)$timeStamp. "/comment.cmt";
        $data = [];
        $data["text"] = $text;
        $data["author"] = $author;
        objSave($path, "raw", $data);
        
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    public function getComment(
            $index = -1
    ){
        $path = self::$path. "byMac/". $this->mac. "/comments/";
        $keys = array_keys(objLoadBranch($path, false, true));
        rsort($keys);
        if ($index >= 0 ){
            $key = $keys[$index];
            $data = objLoad($path. $key. "/comment.cmt", "raw");
            $data["timeStamp"] = $key;
            
        }
        else{
            for ($i = 0; $i < count($keys); $i++){
                $key = $keys[$i];
                $data[$i] = objLoad($path. $key. "/comment.cmt", "raw");
                $data[$i]["timeStamp"] = $key;
            }
        }
        return $data;
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    public function getSupport(
            $index = -1
    ){
        if (!$this->isState){
            $this->setState();
        }
        $this->support->setData();
        if ($index < 0){
            return $this->support->getData();
        }
        else {
            return $this->support->getData($index);
        }
    }
    
    
    /*--------------------------------------------------*/
    
    
   
    /*--------------------------------------------------*/
    
    
    
    
    /*
    private function find(
            $params = []
    ){
        $mac = "";
        
        
        
        
        
        foreach ($params as $key => $value ){
            
            if ($key === "mac"){
                $mac = self::getValidMac($value);
                $mac = "/{$mac}/i";
                unset($params["mac"]);
                continue;
            }
            if (is_string($params[$key])){
                $params[$key] = "/{$params[$key]}/i";
            }
        }
        
        
        
        $bs=array_keys(objLoadBranch(self::$path, false, true));
        $query = [];
        foreach ($bs as $item){
            if (preg_match($mac, $item)){
                $flag = true;
                $obj=objLoad(self::$path.$item.'/router.pro', 'raw');
                
                foreach ($params as $key => $value){
                    if (!preg_match($value, $obj[$key])){
                        $flag = false;
                        break;
                    }
                } 
                
                if ($flag){
                    $query[] = $obj;
                }
            }
        }
        
        
        $this->setData($query);
    }
    
    */
    
  
    
    /*--------------------------------------------------*/
    
 
    
    
    
    
    
    
    
    
    
    
    
}
 