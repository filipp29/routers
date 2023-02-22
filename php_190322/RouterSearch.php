<?php


$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';



class RouterSearch {
    
    
    private $states = [];
    private $city = [];
    private $params = [
        "name" => "",
        "address" => "",
        "mac" => "",
        "dnum" => "",
        "inCharge" => "",
    ];
    private $result = [];
    private $macList = [];
    
    
    
    
    
    /*--------------------------------------------------*/
    /* $params          ������������� ������ � ����������� ������
     * $states          ������ �������� ��� ������
     * $city            ������ ������� ��� ������ 
     * --------------------------------------------------*/
    
    
    public function __construct(
            $params, 
            $states, 
            $city,
            $ticket = null
    ) {
        foreach ($this->params as $key => $value){
            $this->params[$key] = ($params[$key] != null) ? $params[$key] : "";
        }
        $this->states = $states;
        $this->macList = $this->getMacsByState($states);
        if ($ticket != null){
            $this->macList = $this->filterMacByTicket($this->macList, $ticket);
        }
        $this->city = $city;
        
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    private function filterMacByTicket(
            $macList,
            $type
    ){
        $result = [];
        foreach($macList as $key => $value){
            $ticket = new \RouterTicket($value, $type);
            if ($ticket->isOpened()){
                $result[] = $value;
            }
        }
        return $result;
    }
    
    
    /*--------------------------------------------------*/
    
    private function getMacsByState(
            $states
    ){
        $result = [];
        foreach ($states as $key => $value){
            $data = \RouterBase::getMacList($value, $this->params["mac"]);
            foreach ($data as $k => $v){
                $result[] = $v;
            }
        }
        return $result;
        
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    public function search(){
        $result = [];
        foreach ($this->macList as $key => $value){
            $router = new \RouterBase($value);
            $flag = (($router->check($this->params)) && (in_array($router->getCStatus("city"),$this->city)));
            if ($flag){
                $result[] = $router->getCStatus();
            }
        }
        return $result;
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
