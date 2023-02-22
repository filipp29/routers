
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






function searchButtonClick(){
    let $data = [];
    $data["params"] = [];
    $data["negDaysMin"] = document.getElementById("negDaysMin").value;
    $data["negDaysMax"] = document.getElementById("negDaysMax").value;
    $data["params"]["name"] = document.getElementById("name").value;
    $data["params"]["address"] = document.getElementById("address").value;
    $dec = new Decoder();
    console.log($dec.arrayToStr($data));
}

































