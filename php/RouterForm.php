<?php
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterSupport.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterTicket.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php';

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
        "comment" => "Комментарий",
        "author" => "Автор",
        "store" => "Склад",
        "inCharge" => "Ответственный"
        
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
        "comment",
        "store",
        "inCharge"
    ];
    private $inChargeList = [
        "chkalova_16" => "vlad",
        "mayakovskogo_120" => "vlad",
        "altinsarina_117" => "talgat",
        "gogol_62" => "talgat",
        "tekstilshik_18" => "nikita",
        "plaza" => "nikita",
        "lisakovsk" => "kairat",
        "kachar" => "alex",
        "kzhbi" => "vlad",
        "ksk" => "nikita",
        "centr" => "talgat"
    ];
    private $stateName = [
        "installed" => "Установлен",
        "inTaking" => "На изъятии",
        "atServEng" => "У инженера",
        "toStore" => "Передача на склад",
        "store" => "Хранение",
        "testing" => "Тестирование",
        "writeOff" => "Списан",
        "lost" => "Утерян",
        "delete" => "На удаление"
    ];
    private $storeName = [
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
        if (($key == "inCharge") || ($key == "author")){
            return \RouterBase::getProfileName($value);
        }
        if ($key == "lastAuth"){
            return date("d.m.Y H:i:s", (int)$value);
        }
        if ($key == "curState"){
            return $this->stateName[$value];
        }
        if ($key == "address"){
            $value = preg_replace("/.*?,/", "", $value, 1);
            return preg_replace("/,,*/", " , ", $value);
        }
        if ($key == "store"){
            return \RouterBase::getStoreName($value);
        }
        return $value;
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function show(){
        require '../html_templates/header.php';
        $this->getForm1();
        $this->getForm2();
        $this->getForm3();
        $this->getForm4();
        $this->getForm5();
        $this->getForm6();
        
    }
    
    
    /*--------------------------------------------------*/
    
    private function stateButton(
            $state
    ){
        $nameList = [
            "store" => "На склад",
            "installed" => "Установить",
            "atServEng" => "Сервисному инженеру",
            "testing" => "На тестирование",
            "writeOff" => "Списать",
            "delete" => "На удаление",
            "updateState" => "Обновить"
        ];
        $stateName = $nameList[$state];
        require '../html_templates/routerForm1/buttonBlock_data.php';
    }
    
    
    /*--------------------------------------------------*/
    
    public function showFooter(){
        require '../html_templates/routerForm1/buttonBlock_header.php';
        $curState = $this->router->getCStatus("curState");
        switch ($curState) {
            case "installed":
                $this->stateButton("store");
                $this->stateButton("atServEng");
                $this->stateButton("delete");
                break;
            case "atServEng":
                $this->stateButton("updateState");
                $this->stateButton("store");
                $this->stateButton("installed");
//                $this->stateButton("testing");
                $this->stateButton("delete");
                break;
            case "store":
                $this->stateButton("store");
                $this->stateButton("testing");
                $this->stateButton("atServEng");
                $this->stateButton("delete");
                break;
            case "testing":
                $this->stateButton("store");
                $this->stateButton("writeOff");
                $this->stateButton("delete");
                break;
            case "writeOff":
                $this->stateButton("store");
                $this->stateButton("delete");
                break;
            case "lost":
                $this->stateButton("store");
                $this->stateButton("atServEng");
                $this->stateButton("delete");
                break;

            default:
                break;
        }
        require '../html_templates/routerForm1/closeButton.php';
        require '../html_templates/routerForm1/buttonBlock_footer.php';
    }
    
    /*--------------------------------------------------*/
    
    
    public function showSimpleFooter(){
        require '../html_templates/routerForm1/buttonBlock_header.php';
        
        require '../html_templates/routerForm1/simpleCloseButton.php';
        require '../html_templates/routerForm1/buttonBlock_footer.php';
    }
    
    
    /*--------------------------------------------------*/
    
    private function getForm1(){
        $data = $this->router->getCStatus();
        $data["lastAuth"] = $this->router->getLastAuth();
        require '../html_templates/routerForm1/main_header.php';
        
        foreach ($this->paramKeys as $k){
            $v = $data[$k];
            if ($v){
                if ($k == "store"){
                    $key = "Ответственный";
                    $value = $this->getValue("inCharge", $this->inChargeList[$v]);
                    require '../html_templates/routerForm1/data_header.php';
                    require '../html_templates/routerForm1/data_body.php';
                    require '../html_templates/routerForm1/data_footer.php';
                }
                $value = $this->getValue($k, $v);
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
            require '../html_templates/routerForm2/data_body1.php';
            if ($data["executed"] != null){
                require '../html_templates/routerForm2/data_body2.php';
            }
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
        require '../html_templates/routerForm4/commentBox.php';
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
    
    
    private function getForm6(){
        $states = $this->router->getAllStates();
        require '../html_templates/routerForm6/main_header.php';
        foreach($states as $state){
            $params = $state->getParams();
            $status = $state->getState();
            $text = $this->stateName[$status]. " [". date("d.m.Y H:i:s",$state->getTimeStamp()). "] ";
            switch ($status) {
                case "installed":
                    $text .= "Договор: {$params['dnum']}; Установил: ". profileGetUsername($params["installer"]). "; Диспетчер: ". profileGetUsername($params["author"]);
                    break;
                case "inTaking":
                    $text .= "Автор: ". profileGetUsername($params["author"]);
                    break;
                case "atServEng":
                    $text .= "Сервисный инженер: ". profileGetUsername($params["servEng"]). "; Автор: ". profileGetUsername($params["author"]);
                    break;
                case "toStore":
                    $text .= "Сервисный инженер: ". profileGetUsername($params["servEng"]);
                    break;
                case "store":
                    $text .= "Склад: ". $this->getValue("store",$params["store"]). "; Автор: ". profileGetUsername($params["author"]);
                    break;
                case "writeOff":
                    $text .= "Автор: ". profileGetUsername($params["author"]);
                    break;
                case "lost":
                    $text .= "Автор: ". profileGetUsername($params["author"]);
                    break;
                case "delete":
                    $text .= "Автор: ". profileGetUsername($params["author"]);
                    break;
                
                default:
                    break;
            }
            require '../html_templates/routerForm6/data.php';
        }
        require '../html_templates/routerForm6/main_footer.php';
    }
    
    
    
}