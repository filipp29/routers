<?php






class RouterAlert {
    private $mac = null;
    private $state = null;
    private $alert = null;
    private $path = null;
    static private $text =[
        "changeDnum" => [
            "installed" => "Произвольная смена местоположения: ",
            "inTaking" => "Роутер в розыске авторизовался: ",
            "store" => "Роутер со склада авторизовался: ",
            "writeOff" => "Списанный роутер авторизовался: ",
            "lost" => "Утерянный роутер авторизовался: ",
            "atServEng" => "Роутер на балансе сервисного инженера авторизовался: "
        ],
        "changeRouter" => [
            "installed" => "У абонента длительное время авторизовывается другой MAC: ",
            "inTaking" => "У абонента длительное время авторизовывается другой MAC: "
        ]
    ];
    
    /*--------------------------------------------------*/
    
    
    
    public function __construct(
            $mac,
            $state
    ){
        $this->mac = $mac;
        $this->state = $state;
        $this->path = "/routers/byMac/". $this->mac. "/alert/". $state. "/";
        $this->setAlert();
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    static public function getText(){
        return self::$text;
    }
    
    
    /*--------------------------------------------------*/
    
    
    private function setAlert(){
        $br = array_keys(objLoadBranch($this->path, false, true));
        rsort($br);
        $this->alert = $br;
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function getAlert(
            $index = -1
    ){
        if ($index < 0){
            return $this->alert;
        }
        else{
            return $this->alert[$index];
        }
    }
    
    /*--------------------------------------------------*/
    
    
    public function getData(
            $alert
    ){
        $path = $this->path. $alert. "/alert.info";
        return objLoad($path);
    }
    
    
    /*--------------------------------------------------*/
    
    public function addAlert(
            $type,
            $value,
            $timeStamp,
            $alertTime,
            $stateTimeStamp
    ){
         
        $text = self::$text[$type]["$this->state"]. $value;
        $curTimeStamp = $this->getData($this->alert[0])["timeStamp"];
        if (!$curTimeStamp){
            $curTimeStamp = 0;
        }
        $alertTime = intval($alertTime);
        echo "{$alertTime} - {$stateTimeStamp}";
        if ($alertTime < $stateTimeStamp){
            
            return false;
        }
        if (!$this->alert[0]){
            $bufTime = 0;
        }
        else{
            $bufTime = $this->alert[0];
        }
        $date1 = date("Ymd",$timeStamp);
        $date2 = date("Ymd",$bufTime);
        $hour = floor(((int)$timeStamp - (int)$curTimeStamp)/3600);
        $data = [
            "timeStamp" => $alertTime,
            "text" => $text
        ];
        echo "\n\n{$date1} - {$date2} = {$alertTime} - {$curTimeStamp}\n\n";
        if (($date1 != $date2) && ($alertTime >= $curTimeStamp)){
            objSave($this->path. $timeStamp. "/alert.info", "raw", $data);
            return true;
        }
        return false;
        
    }
    
    
    
}















