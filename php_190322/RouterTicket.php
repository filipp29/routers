<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД


class RouterTicket {
    
    private $mac = null;
    private $type = null;
    private $path = "/routers/";
    private $tickets = [];
    
    
    /*--------------------------------------------------*/
    
    public function __construct(
            $mac,
            $type
    ){
        
        $this->mac = $mac;
        $this->type = $type;
        $this->path = $this->path. "byMac/{$mac}/tickets/{$type}/";
        $this->setTickets();
        
        
    }
    
    
    /*--------------------------------------------------*/
    
    
    private function setTickets(){
        $obj = array_keys(objLoadBranch($this->path, false, true));
        rsort($obj);
        $this->tickets = $obj;
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    public function getCurrentTicket(){
        return $this->tickets[0];
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function getAllTickets(){
        return $this->tickets;
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function getData(
            $timeStamp = null
    ){
        if ($timeStamp === null){
            $timeStamp = $this->tickets[0];
        }
        $data["start"] = objLoad($this->path. $timeStamp. "/start.info","raw");
        $data["timeStamp"] = $timeStamp;
        $data["end"] = objLoad($this->path. $timeStamp. "/end.info","raw");
        return $data;
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function getComments(
            $timeStamp = null
    ){
        
        if ($timeStamp === null){
            $timeStamp = $this->tickets[0];
        }
        $path = $this->path. $timeStamp. "/comments/";
        $keys = array_keys(objLoadBranch($path,false,true));
        rsort($keys);
        $data = [];
        for ($i = 0; $i < count($keys); $i++){
            $data[$i] = objLoad($path. $keys[$i]. "/comment.cmt", "raw");
            $data[$i]["timeStamp"] = $keys[$i];
        }
        return $data;
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function addComment(
            $text,
            $author,
            $timeStamp = null
    ){
        if ($timeStamp === null){
            $timeStamp = $this->tickets[0];
        }
        $commentTime = (string)time();
        if (objCheckExist($this->path. $timeStamp. "/comments/". $commentTime. "/comment.cmt", "raw")){
            $commentTime = (string)(time()+2);
        }
        $path = $this->path. $timeStamp. "/comments/". $commentTime. "/comment.cmt";
        $data = [];
        $data["text"] = $text;
        $data["author"] = $author;
        objSave($path, "raw", $data);
        
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    public function isOpened(){
        if (count($this->tickets) == 0){
            return false;
        }
        return !(objCheckExist($this->path. $this->tickets[0]. "/end.info", "raw"));
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function openTicket(
            $start,
            $timeStamp = null
    ){
        if ($this->isOpened()){
            throw new Exception("Current ticket ". $this->tickets[0]. " is opened");
        }
        if ($timeStamp === null){
            $timeStamp = (string)time();
        }
        $path = $this->path. $timeStamp. "/start.info";
        objSave($path,"raw",$start->getData());
        $this->setTickets();
        $text = "Ticket opened";
        $author = "SYSTEM";
        $this->addComment($text, $author, $timeStamp);
        
        
    }
    
    /*--------------------------------------------------*/
    
    
    public function closeTicket(
            $end,
            $timeStamp = null
    ){
        if (!$this->isOpened()){
            throw new Exception("Current ticket ". $this->tickets[0]. " is closed");
        }
        if ($timeStamp === null){
            $timeStamp = (string)time();
        }
        $path = $this->path. $this->getCurrentTicket(). "/end.info";
        objSave($path,"raw",$end->getData());
        $this->setTickets();
        $text = "Ticket closed";
        $author = "SYSTEM";
        $this->addComment($text, $author, $this->getCurrentTicket());
    }
    
    
    
}





















