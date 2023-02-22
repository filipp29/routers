

/*---------------------------------------------------------------------------*/
/* ##########################################################################
 * ##########################################################################
 * ##########################################################################
 * ##########################################################################
 * ##########################################################################
 * 
 * DECODER CLASS
 * 
 * ##########################################################################
 * ##########################################################################
 * ##########################################################################
 * ---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/


class Decoder{
    
    str = "";
    ar = {};
    count = 0;
    
    
    constructor(){
        this.count = 0;
    }
    
    decode(
            elem
    ){
        
        let $reg = /(\{.*?\})/;
        let $regV = /(..*?\{)/;
        let $result = {};
        let $flag = true;
        while($flag){
            
            if (!this.str){
                $flag = false;
                break;
            }
            
            this.count++;
            if (this.count > 10000){
                $flag = false;
                break;
            }
            
            if (this.str[0] === "{"){
                if (this.str[1] === "/"){
                    this.str = this.str.replace($reg,"");
                    $flag = false;
                    if ((typeof($result) == "object") && (Object.keys($result).length == 0)){
                        $result = "";
                    }
                    break;
                }
                else{
                    let $index = this.str.match($reg)[0];
                    $index = $index.substr(1,$index.length-2);
                    this.str = this.str.replace($reg,"");
                    $result[$index] = this.decode($index);
                }
            }
            else{
                $result = this.str.match($regV)[0];
                $result =$result.substr(0,$result.length -1);
                this.str = this.str.replace($regV,"{");
                
            }
            
        }
        return $result;
        
    }
    
    
    strToArray(
            $str
    ){
        this.str = $str;
        return this.decode("");
    }
    
    
    encode(
            $key,
            $value
    ){
        $key = String($key);
        $key = $key.trim();
        
        let $result = "{"+$key+"}";
        if (typeof($value) == "object"){
            for (let $k in $value){
                $result += this.encode($k,$value[$k]);
            }
        }
        else{
            $value = String($value);
            $value = $value.trim();
            $result += $value;
        }
        $result += "{/"+$key+"}";
        return $result;
    }
    
    arrayToStr(
            $ar
    ){
        
        this.ar = $ar;
        let $result = "";
        for(let $k in $ar){
            $result += this.encode($k,$ar[$k]);
        }
        return $result;
    }
    
    
    
}


/*---------------------------------------------------------------------------*/
/* ##########################################################################
 * ##########################################################################
 * ##########################################################################
 * ##########################################################################
 * ##########################################################################
 * 
 * 
 * ##########################################################################
 * ##########################################################################
 * ##########################################################################
 * ---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/






function selectOnChange(
        $item
){
    let $str =  document.location.pathname;
    $str = $str.split("?");
    document.location = $str+"?state="+$item.options[$item.selectedIndex].value;
    
}

function saveButtonClick(){
    let $data = {};
    let $timeStamp = null;
    let $time = null;
    let $params = {};
    $data["mac"] = document.getElementById("mac").value;
    $state = document.getElementById("state");
    $data["state"] = $state.options[$state.selectedIndex].value;
    let $buf = document.getElementById("date").value;
    $buf = $buf.split(/[^0-9]/);
    $buf[1] = Number($buf[1]) - 1;
    if ($buf.length == 6){
        $time = new Date($buf[0],$buf[1],$buf[2],$buf[3],$buf[4],$buf[5]);
        $data["timeStamp"] = $time.getTime()/1000;
    }
    else if ($buf.length == 3){
        $time = new Date($buf[0],$buf[1],$buf[2]);
        $data["timeStamp"] = $time.getTime()/1000;
    }
    else{
        $data["timeStamp"] = Date.now()/1000;
    }
    
    $count = document.getElementById("count").textContent;
    for(let $i = 0; $i < $count; $i++){
        let $key = document.getElementById("key"+String($i)).textContent;
        let $value = document.getElementById("value"+String($i)).value;
        $params[$key] = $value;
    }
    $data["params"] = $params;
    $data["city"] = document.getElementById("city").value;
    $data["negDays"] = document.getElementById("negDays").value;
    let $dec = new Decoder();
    let $result = $dec.arrayToStr($data);
    document.location = "/_modules/routers/php/add_router.php?data="+$result;
}

