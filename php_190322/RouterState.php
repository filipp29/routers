<?php








//STATS �������
//    installed           ���������� � ��������
//        dnum            ����� ��������
//        servEng         ����� ���������� �������� ������������� ������
//        operator      ����� ���������� ������������� ������
///*--------------------------------------------------*/
//    inTaking            �� �������
//        dnum            ����� ��������
///*--------------------------------------------------*/
//    atServEng           � ���������� ��������
//        servEng         ����� ���������� �������� ���������� ������
///*--------------------------------------------------*/
//    toStore             �� ���� �� �����
//        servEng         ����� ���������� �������� ����������� ������
///*--------------------------------------------------*/
//    store               �� ������
//        giver           ����� ���������� ����������� ������ �� �����
//        store           ������������� ������
//        storeMan        ����� ���������� ���������� ������ �� �����
///*--------------------------------------------------*/
//    testing             �� ������������
//        giver           ����� ���������� ����������� ������ �� ������������
//        tester          ����� ���������� �������������� �� ������������
///*--------------------------------------------------*/
//    writeOff            ������
//        profile         ����� ���������� ���������� ������
//        reason          ������� ��������
///*--------------------------------------------------*/
//    lost                ������
//        profile         ����� ���������� ����������������� �����
//        comment         ����������� ����������




class RouterState {
    
    static private $STATS = [
        "installed" => [
            "params" => [
                "dnum",
//                "servEng",
//                "operator"
                "author"
            ],
            "before" => [
                "atServEng"
            ],
            "after" => [
                "inTaking",
                "atServEng"
            ]
        ],
        "inTaking" => [
            "params" => [
                "dnum",
                "author"
            ],
            "before" => [
                "installed"
            ],
            "after" => [
                "installed",
                "atServEng",
                "writeOff",
                "lost",
                "store"
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
                "testing"
            ],
            "after" => [
                "installed",
                "toStore",
                "testing"
            ]
        ],
        "toStore" => [
            "params" => [
                "servEng",
                "author"
            ],
            "before" => [
                "atServEng"
            ],
            "after" => [
                "store",
                "atServEng"
            ]
        ],
        "store" => [
            "params" => [
//                "giver",
                "store",
                "storeMan",
                "author"
            ],
            "before" => [
                "toStore",
                "testing",
                "inTaking"
            ],
            "after" => [
                "store",
                "atServEng",
                "testing"
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
                "atServEng"
            ],
            "after" => [
                "store",
                "atServEng",
                "writeOff"
            ]
        ],
        "writeOff" => [
            "params" => [
                "profile",
                "comment",
                "author"
            ],
            "before" => [
                "testing",
                "inTaking"
            ],
            "after" => [
                "store"
            ]
        ],
        "lost" => [
            "params" => [
                "profile",
                "comment",
                "author"
            ],
            "before" => [
                "inTaking"
            ],
            "after" => [
                "store",
                "inTaking",
                "testing"
            ]
        ]
    ];
    
    static private $stateName = [
        "installed" => "����������",
        "inTaking" => "�� �������",
        "atServEng" => "� ��������",
        "toStore" => "�������� �� �����",
        "store" => "��������",
        "testing" => "������������",
        "writeOff" => "������",
        "lost" => "������"
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
        if ($params["author"] == null){
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
            return $this->params[$key];
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
    
    
    /*��������� ����� �� ������� ������ ������� � ������ $stete
     * --------------------------------------------------*/
    
    public function checkAfter(
            $state
    ){
        return in_array(trim($state), self::$STATS[$this->state]["after"]);
    }
    
    
    /*��������� ����� �� ������ $state ������� � ������� ������
     * --------------------------------------------------*/
    
    public function checkBefore(
            $state
    ){
        return in_array(trim($state), self::$STATS[$this->state]["before"]);
    }
    
    
    /*��������� ����� �� ������ $before ������� � ������ $after
     * --------------------------------------------------*/
    
    static function checkChangeState(
            $before,
            $after
    ){
        return in_array($after, self::$STATS[$before]["after"]);
    }
    
    
    
}






















