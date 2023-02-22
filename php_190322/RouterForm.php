<?php
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterSupport.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterTicket.php';

date_default_timezone_set("Asia/Almaty");



class RouterForm {
    private $mac;
    private $params = [];
    private $router;
    private $paramNames = [
        "negDays" => "Дней в минусе",
        "curState" => "Статус",
        "ticket" => "Тикет",
        "mac" => "MAC адрес",
        "name" => "ФИО абонента",
        "address" => "Адрес абонента",
        "inCharge" => "Ответственный",
        "dnum" => "Номер договора",
        "lastAuth" => "Последняя авторизация",
        "comment" => "Комментарий"
        
    ];
    private $paramKeys = [
        "mac",
        "address",
        "name",
        "dnum",
        "negDays",
        "inCharge",
        "lastAuth",
        "curState",
        "comment"
    ];
    
    private $stateName = [
        "installed" => "Установлен",
        "inTaking" => "На изъятии",
        "atServEng" => "У инженера",
        "toStore" => "Передача на склад",
        "store" => "Хранение",
        "testing" => "Тестирование",
        "writeOff" => "Списан",
        "lost" => "Утерян"
    ];
    
    /*--------------------------------------------------*/
    
    public function __construct(
            $mac
    ){
        if (strlen(\RouterBase::getValidMac($mac)) != 12){
            throw new Exception("Invalid mac");
        }
        $this->mac = $mac;
        $this->router = new \RouterBase($mac);

    }
    
    
    /*--------------------------------------------------*/
    
    
    private function getValue(
            $key,
            $value
    ){
        
        if (!$value){
            return "";
        }
        if ($key == "inCharge"){
            return \RouterBase::getProfileName($value);
        }
        if ($key == "lastAuth"){
            return date("Y-d-m H:i:s", (int)$value);
        }
        if ($key == "curState"){
            return $this->stateName[$value];
        }
        if ($key == "address"){
            $value = preg_replace("/.*?,/", "", $value, 1);
            return preg_replace("/,,*/", " , ", $value);
        }
        return $value;
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function show(){
        $this->getForm1();
        $this->getForm2();
        $this->getForm3();
        $this->getForm4();
        $this->getForm5();
    }
    
    
    /*--------------------------------------------------*/
    
    
    private function getForm1(){
        $data = $this->router->getCStatus();
        $data["lastAuth"] = $this->router->getLastAuth();
        require '../html_templates/routerForm1/main_header.php';
        
        foreach ($this->paramKeys as $k){
            $value = $data[$k];
            if ($value){
                $value = $this->getValue($k, $value);
                $key = $this->paramNames[$k];
                require '../html_templates/routerForm1/data_header.php';
                require '../html_templates/routerForm1/data_body.php';
                require '../html_templates/routerForm1/data_footer.php';
            }
        }
        
        require '../html_templates/routerForm1/main_footer.php';
    }
    
    
    
    /*--------------------------------------------------*/
    
    private function getForm2(){
        $support = $this->router->getSupport();
        require '../html_templates/routerForm2/main_header.php';
        foreach($support as $key => $value){
            $data = $value;
            require '../html_templates/routerForm2/data_header.php';
            require '../html_templates/routerForm2/data_body.php';
            require '../html_templates/routerForm2/data_footer.php';
        }
        require '../html_templates/routerForm2/main_footer.php';
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    private function getForm3(){
        require '../html_templates/routerForm3/main_header.php';
        $ticket = new \RouterTicket($this->mac, "inTaking");
        $ticketKeys = $ticket->getAllTickets();
        foreach($ticketKeys as $k => $v){
            
            $comment = $ticket->getComments($v);
            $comment = array_reverse($comment, true);
//            $date = $comment[0]["timeStamp"];
            $date = $v;
            require '../html_templates/routerForm3/data_header.php';
            foreach ($comment as $key => $data){
                if ($data["author"] == "SYSTEM"){
                    require '../html_templates/routerForm3/data_system.php';
                }
                else{
                    $data["author"] = \RouterBase::getProfileName($data["author"]);
                    require '../html_templates/routerForm3/data_comment.php';
                }
            }
            require '../html_templates/routerForm3/data_footer.php';
        }
        require '../html_templates/routerForm3/main_footer.php';
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    private function getForm4(){
        $comment = $this->router->getComment();
        require '../html_templates/routerForm4/main_header.php';
        foreach($comment as $key => $data){
            if ($data["author"] != "SYSTEM"){
                $data["author"] = \RouterBase::getProfileName($data["author"]);
            }
            require '../html_templates/routerForm4/data.php';
        }
        require '../html_templates/routerForm4/main_footer.php';
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    private function getForm5(){
        require '../html_templates/routerForm5/main_header.php';
        
        require '../html_templates/routerForm5/data.php';
        
        require '../html_templates/routerForm5/main_footer.php';
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    
}
