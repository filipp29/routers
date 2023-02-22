

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



















function getForm(id){
    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        if (xhr.status == 200){
            let dec = new Decoder();
            let data = dec.strToArray(xhr.responseText);
            console.log(data);
            for(let key in data){
                console.log(key);
                document.getElementById(key).innerHTML = data[key];
                
            }
        }
    };
    xhr.open("GET","/_modules/routers/php/lsk/php/getVcard.php?dnum="+id);
    xhr.send();
}
/*---------------------------------------------------------------------------*/
function getAuth(id){
    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        if (xhr.status == 200){
            document.getElementById("authTbody").innerHTML = xhr.responseText;
            document.getElementById("getButton").removeAttribute("disabled");
            document.getElementById("checkedButton").removeAttribute("disabled");
        }
    };
    xhr.open("GET","/_modules/routers/php/lsk/php/getAuth.php?dnum="+id);
    xhr.send();
    document.getElementById("authTbody").innerHTML = "Загрузка";
}

/*---------------------------------------------------------------------------*/

function getSupport(id){
    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        if (xhr.status == 200){
            document.getElementById("supTbody").innerHTML = xhr.responseText;
        }
    };
    xhr.open("GET","/_modules/routers/php/lsk/php/getSupport.php?dnum="+id);
    xhr.send();
    document.getElementById("supTbody").innerHTML = "Загрузка";
}

/*---------------------------------------------------------------------------*/

function checkedDnum(){
    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        if(xhr.status == 200){
            getAll();
        }
    };
    let dnum = document.getElementById("id").value;
    xhr.open("GET","/_modules/routers/php/lsk/php/checked.php?dnum="+dnum);
    xhr.send();
}

/*---------------------------------------------------------------------------*/
function getAll(){
    document.getElementById("getButton").setAttribute("disabled","disabled");
    document.getElementById("checkedButton").setAttribute("disabled","disabled");
    let values = Array.from(document.getElementsByClassName("value"));
    for(let el of values){
        el.textContent = "";
    }
    document.getElementById("message").value = "";
    document.getElementById("id").value = "";
    document.getElementById("supTbody").innerHTML = "";
    document.getElementById("authTbody").innerHTML = "";
    let getDnum = new Promise(function(callable){
        let xhr = new XMLHttpRequest();
        xhr.onload = function(){
            if (xhr.status == 200){
                console.log(xhr.responseText);
               callable(xhr.responseText); 
            }
        };
        xhr.open("GET","/_modules/routers/php/lsk/php/getDnum.php");
        xhr.send();
    });
    
    getDnum.then((id) => {
        document.getElementById("id").value = id;
        getForm(id);
        getAuth(id);
        getSupport(id);
    });
}


/*---------------------------------------------------------------------------*/


function linkRouter(
        mac
){
    let str = "?dnum="+document.getElementById("id").value;
    str += "&macNew="+mac;
    str += "&servEng=SYSTEM";
    str += "&author=SYSTEM";
    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        if (xhr.status == 200){
            document.getElementById("message").value = xhr.responseText;
            let regex = /_ERROR/;
            if (!regex.test(xhr.responseText)){
                checkedDnum();
            }
        }
        if (xhr.status == 500){
            console.log(xhr.responseText);
        }
    };
    console.log(str);
    document.getElementById("message").value = "Загрузка";
    xhr.open("GET","/_modules/routers/php/support/sup_install_router.php"+str);
    xhr.send();
}