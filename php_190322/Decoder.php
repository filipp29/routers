<?php




class Decoder{
    
    private $str = "";
    private $ar = [];
    private $count = 0;
    
    
    
    
    /*--------------------------------------------------*/
    
    
    public function __construct() {
        $this->count = 0;
    }
    
    /*--------------------------------------------------*/
    
    private function decode(
            $elem
    ){
        $result = [];
        $flag = true;
        
        while($flag){
            if (!$this->str){
                $flag = false;
                break;
            }
            $this->count++;
            if ($this->count > 10000){
                $flag = false;
                break;
            }
            if ($this->str[0] === "{"){
                if ($this->str[1] === "/"){
                    $this->str = preg_replace("/(\{.*?\})/", "", $this->str, 1);
                    $flag = false;
                    if ((is_array($result)) && (count($result) == 0)){
                        $result = "";
                    } 
                    break;
                }
                else  {
                    $buf = [];
                    preg_match("/(\{.*?\})/", $this->str, $buf);
                    $index = substr($buf[1], 1, strlen($buf[1])-2);
                    $this->str = preg_replace("/(\{.*?\})/", "", $this->str, 1);
                    $result[$index] = $this->decode($index);
                }
            }
            else{
                $buf = [];
                preg_match("/(..*?\{)/", $this->str, $buf);
                $result = substr($buf[1], 0, strlen($buf[1])-1);
                $this->str = preg_replace("/(..*?\{)/", "{", $this->str, 1);
            }
        }
        
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    
    public function strToArray(
            $str
    ){
        $this->str = $str;
        return $this->decode("");
    }
    
    /*--------------------------------------------------*/

    
    private function encode(
            $key,
            $value
    ){
        $result = "{{$key}}";
        if (is_array($value)){
            foreach($value as $k => $v){
                $result .= $this->encode($k, $v);
            }
        }
        else{
            $result .= $value;
        }
        $result .= "{/{$key}}";
        return $result;
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    public function arrayToStr(
            $ar
    ){
        $this->ar = $ar;
        $result = "";
        foreach ($ar as $k => $v){
            $result .= $this->encode($k, $v);
        }
        return $result;
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    
}






//$str = ("<worker><name>Коля</name><age>25</age><salary>1000</salary></worker>");












