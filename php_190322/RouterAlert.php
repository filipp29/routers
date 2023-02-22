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
            "lost" => "Утерянный роутер авторизовался: "
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
            $alertTime
    ){
         
        $text = self::$text[$type]["$this->state"]. $value;
        $curTimeStamp = $this->getData($this->alert[0])["timeStamp"];
        $hour = floor(((int)$timeStamp - (int)$curTimeStamp)/3600);
        $data = [
            "timeStamp" => $alertTime,
            "text" => $text
        ];
        if ((abs($hour) > 24) && ($alertTime > $curTimeStamp)){
            objSave($this->path. $timeStamp. "/alert.info", "raw", $data);
            return true;
        }
        return false;
        
    }
    
    
    
}
















