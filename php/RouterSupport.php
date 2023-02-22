<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');





class RouterSupport {
    
    private $users = [];
    private $requests = [];
    private $data = [];
    
    /*--------------------------------------------------*/
    
    
    public function __construct(
           $states
    ){
        $n = count( $states);
        for ($i = 0; $i < $n; $i++){
            if ($states[$i]->getState() == "installed"){
                if ($i > 0){
                    $end = $states[$i-1]->getTimeStamp();
                }
                else {
                    $end = time();
                }
                $this->users[] = [
                    "dnum" => $states[$i]->getParams("dnum"),
                    "start" => $states[$i]->getTimeStamp(),
                    "end" => $end
                ];
            }
        }
        
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    private function getName(
            $username
    ){
        $utmp=objLoad('/profiles/'.$username.'/profile.pro', "raw");
        if (array_key_exists('uname', $utmp)){
            return $utmp["uname"];
        }
        else 
        {
            return ($username);    
        }
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function setData(){
        
        for ($i = 0; $i < count($this->users); $i++){
            $dnum = $this->users[$i]["dnum"];
            $start = (int)$this->users[$i]["start"];
            $end = (int)$this->users[$i]["end"];
            $path = "/users/". $dnum. "/support/";
            $br = array_keys(objLoadBranch($path, true, false));
            rsort($br);
            foreach($br as $key => $value){
                $obj = objLoad($path. $value, "raw");
                $date = (int)$obj["inc_time"];
                if (($start <= $date)&&($date <= $end)){
                    $buf = [];
                    $buf["inc_time"] = $obj["inc_time"];
                    $buf["end_time"] = $obj["end_time"];
                    $buf["dnum"] = $obj["dnum"];
                    $buf["address"] = preg_replace("/,+/i", " , ", $obj["address"]);
                    $buf["text"] = $obj["text"];
                    $buf["resolution"] = $obj["resolution"];
                    
                    $buf["operator"] = $this->getName($obj["operator"]);
                    $buf["operator_avatar"] = profileGetAvatar($obj["operator"]);
                    $buf["executed"] = $this->getName($obj["executed"]);
                    $buf["executed_avatar"] = profileGetAvatar($obj["executed"]);
                    $this->data[] = $buf;
                }
            }
        }
        
        
    }
    
    
    
    
    
    
    
    /*--------------------------------------------------*/
    
    
    public function getData(
            $index = -1
    ){
        if ($index < 0){
            return $this->data;
        }
        else {
            return $this->data[$index];
        }
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
