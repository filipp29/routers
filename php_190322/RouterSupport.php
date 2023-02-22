<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД






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
                    $buf["dnum"] = $obj["dnum"];
                    $buf["address"] = preg_replace("/,+/i", " , ", $obj["address"]);
                    $buf["text"] = $obj["text"];
                    $buf["resolution"] = $obj["resolution"];
                    $buf["operator"] = objLoad("/profiles/". $obj["operator"]. "/profile.pro","raw")["uname"];
                    $buf["executed"] = objLoad("/profiles/". $obj["executed"]. "/profile.pro","raw")["uname"];
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
