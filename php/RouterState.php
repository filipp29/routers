<?php








//STATS СТАТУСЫ
//    installed           установлен у абонента
//        dnum            номер договора
//        servEng         логин сервисного инженера установившего роутер
//        operator      логин диспетчера установившего роутер
///*--------------------------------------------------*/
//    inTaking            на изъятии
//        dnum            номер договора
///*--------------------------------------------------*/
//    atServEng           у сервисного инженера
//        servEng         логин сервисного инженера забравшего роутер
///*--------------------------------------------------*/
//    toStore             по пути на склад
//        servEng         логин сервисного инженера передавшего роутер
///*--------------------------------------------------*/
//    store               на складе
//        giver           логин сотрудника передавшего роутер на склад
//        store           идентификатор склада
//        storeMan        логин сотрудника принявшего роутер на склад
///*--------------------------------------------------*/
//    testing             на тестировании
//        giver           логин сотрудника передавшего роутер на тестирование
//        tester          логин сотрудника ответственного за тестирование
///*--------------------------------------------------*/
//    writeOff            списан
//        profile         логин сотрудника списавшего роутер
//        reason          причина списания
///*--------------------------------------------------*/
//    lost                утерян
//        profile         логин сотрудника констатировавшего утерю
//        comment         комментарий сотрудника
///*--------------------------------------------------*/
//
//    delete              помечен на удаление    
//        profile         логин сотрудника пометившего роутер
//        comment         комментарий
//
///*--------------------------------------------------*/




class RouterState {
    
    static private $STATS = [
        "installed" => [
            "params" => [
                "dnum",
                "installer",
//                "operator"
                "author"
            ],
            "before" => [
                "atServEng",
                "store",
                "delete"
            ],
            "after" => [
                "inTaking",
                "atServEng",
                "store",
                "writeOff",
                "delete"
            ]
        ],
        "inTaking" => [
            "params" => [
                "dnum",
                "installer",
                "author"
            ],
            "before" => [
                "installed",
                "delete"
            ],
            "after" => [
                "installed",
                "atServEng",
                "writeOff",
                "lost",
                "store",
                "delete"
            ]
        ],
        "atServEng" => [
            "params" => [
                "servEng",
                "author"
            ],
            "before" => [
                "installed",
                "inTaking",
                "toStore",
                "store",
                "testing",
                "delete",
                "atServEng"
            ],
            "after" => [
                "installed",
                "toStore",
                "testing",
                "delete",
                "store",
                "atServEng"
            ]
        ],
        "toStore" => [
            "params" => [
                "servEng",
                "author"
            ],
            "before" => [
                "atServEng",
                "delete"
            ],
            "after" => [
                "store",
                "atServEng",
                "delete"
            ]
        ],
        "store" => [
            "params" => [
//                "giver",
                "store",
//                "storeMan",
                "author"
            ],
            "before" => [
                "store",
                "toStore",
                "testing",
                "inTaking",
                "installed",
                "delete"
            ],
            "after" => [
                "store",
                "atServEng",
                "testing",
                "installed",
                "delete"
            ]
        ],
        "testing" => [
            "params" => [
//                "giver",
                "tester",
                "author"
            ],
            "before" => [
                "store",
                "toStore",
                "atServEng",
                "delete"
            ],
            "after" => [
                "store",
                "atServEng",
                "writeOff",
                "delete"
            ]
        ],
        "writeOff" => [
            "params" => [
//                "profile",
                "comment",
                "author"
            ],
            "before" => [
                "testing",
                "inTaking",
                "delete"
            ],
            "after" => [
                "store",
                "delete"
            ]
        ],
        "lost" => [
            "params" => [
//                "profile",
                "comment",
                "author"
            ],
            "before" => [
                "inTaking",
                "delete"
            ],
            "after" => [
                "store",
                "inTaking",
                "testing",
                "delete"
            ]
        ],
        "delete" => [
            "params" => [
//                "profile",
                "comment",
                "author"
            ],
            "before" => [
                "installed",
                "inTaking",
                "toStore",
                "store",
                "testing",
                "atServEng",
                "lost",
                "writeOff",
                "delete"
            ],
            "after" => [
                "installed",
                "inTaking",
                "toStore",
                "store",
                "testing",
                "atServEng",
                "lost",
                "writeOff",
                "delete"
            ]
        ]
    ];
    
    static private $stateName = [
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

    
    
    /*--------------------------------------------------*/
    
    private $state;
    private $params = [];
    private $hasParams = false;
    private $timeStamp = 0;
    
    
    /*--------------------------------------------------*/
    
    public function __construct(
            $state, 
            $params = null 
    
    ){
        
        if (!key_exists($state, self::$STATS)){
            throw new Exception("Wrong State ".$state);
        }
        
        $this->state = $state;
        foreach (self::$STATS[$state]["params"] as $key => $value){
            $this->params[$value] = "";
        }
        if ((!key_exists("author", $params)) || ($params["author"] == null)){
            $params["author"] = "UNKNOWN";
        }
        if ($params){
            $this->setParams($params);
        }
        $this->timeStamp = time();
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function setTimeStamp(
            $timeStamp
    ){
        $this->timeStamp = $timeStamp;
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function getTimeStamp(){
        return $this->timeStamp;
    }
    
    
    /*--------------------------------------------------*/
    
    
    public function getState(){
        return $this->state;
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    public function setParams(
            $params
    ){
        foreach ($params as $key => $value){
//            if (!key_exists($key, $this->params)){
//                print_r($params);
//                throw new Exception("Wrong param key ".$key);
//            }
//            $this->params[$key] = $value;
            if (key_exists($key, $this->params)){
                $this->params[$key] = $value;
            }
            
        }
        foreach ($this->params as $key => $value){
            if (!$value){
                if ($key == "installer"){
                    $this->params[$key] = "UNKNOUWN";
                    continue;
                }
                throw new Exception("Param ". $key. " has empty value");
            }
        }
        $this->hasParams = true;
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    public function getParams(
            $key = null
    ){
        if (!$this->hasParams){
            throw new Exception("Params are empty");
        }
        if ($key){
            if (key_exists($key, $this->params)){
                return $this->params[$key];
            }
        }
        else {
            return $this->params;
        }
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    public function getData(){
        $data = $this->getParams();
        $data["state"] = $this->state;
        return $data;
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    static public function getStateList(){
        return array_keys(self::$STATS);
    }
    
    
    /*--------------------------------------------------*/
    
    
    static public function getStateListAll(){
        return self::$STATS;
    }
    
    
    /*--------------------------------------------------*/
    
    
    static public function getStateName(){
        return self::$stateName;
    }
    
    
    /*Проверяет может ли текущий статус перейти в статус $stete
     * --------------------------------------------------*/
    
    public function checkAfter(
            $state
    ){
        return in_array(trim($state), self::$STATS[$this->state]["after"]);
    }
    
    
    /*Проверяет может ли статус $state перейти в текущий статус
     * --------------------------------------------------*/
    
    public function checkBefore(
            $state
    ){
        return in_array(trim($state), self::$STATS[$this->state]["before"]);
    }
    
    
    /*Проверяет может ли статус $before перейти в статус $after
     * --------------------------------------------------*/
    
    static function checkChangeState(
            $before,
            $after
    ){
        return in_array($after, self::$STATS[$before]["after"]);
    }
    
    
    
}