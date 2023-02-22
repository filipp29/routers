//<script>
  



/*---------------------------------------------------------------------------*/



/*---------------------------------------------------------------------------*/

 

function demoTicket(){
    var xhr=new XMLHttpRequest();
    xhr.onreadystatechange=function()
    {
        if (xhr.readyState==4 && xhr.status==200){
            var ret=xhr.responseText;
            getById('rtrTicketViewer').innerHTML=ret;
        }
    }

    xhr.open("POST","/_modules/routers/html_old/demoticket.php",true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    xhr.send('param1=value&param2=value&chtougodno=kakugodno');
    msgblock('Информация о тикете', '<div id="rtrTicketViewer"></div>');
}


function rtrDialNum($num){
    var xhr=new XMLHttpRequest();
    xhr.onreadystatechange=function()
    {
        if (xhr.readyState==4 && xhr.status==200){
            var ret=xhr.responseText;
            //alert(ret);
        }
    }

    xhr.open("POST","/_utils/dialer/dial.php",true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    xhr.send('int=&ext='+encodeURIComponent($num)+'&via=routers');
}

/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/




function loadPage(){
    if ($xhrSidebar.status === 200){
        getById("workfield").innerHTML = $xhrSidebar.responseText;
        
    }
}

    function rtrScreenLock(){
        rtrScreenUnlock();
        var $lockDiv=document.createElement("div");
        $lockDiv.id='uplLockScreenDiv';
        $lockDiv.style.display='table';
        $lockDiv.style.backgroundColor='rgba(0, 0, 0, 0.7)';
        $lockDiv.style.position='absolute';
        $lockDiv.style.top='0px';
        $lockDiv.style.left='0px';
        $lockDiv.style.width='100%';
        $lockDiv.style.height='100%';
        $lockDiv.style.zIndex='9999';
        var $topRowDiv=document.createElement("div");
        $topRowDiv.style.display='table-row';
        $topRowDiv.style.height='33%';

        var $cell1=document.createElement("div");
        $cell1.style.display='table-cell';
        var $cell2=document.createElement("div");
        $cell2.style.display='table-cell';
        var $cell3=document.createElement("div");
        $cell3.style.display='table-cell';
        $topRowDiv.appendChild($cell1);
        $topRowDiv.appendChild($cell2);
        $topRowDiv.appendChild($cell3);

        var $middleRowDiv=document.createElement("div");
        $middleRowDiv.style.display='table-row';
        $middleRowDiv.style.height='33%';
        var $cell4=document.createElement("div");
        $cell4.style.display='table-cell';
        $cell4.style.width='33%';
        var $cell5=document.createElement("div");
        $cell5.style.display='table-cell';
        $cell5.style.width='33%';
        $cell5.style.color='#fff';
        $cell5.style.fontSize='21px';
        $cell5.style.textAlign='center';
        $cell5.style.verticalAlign='middle';
        $cell5.innerHTML='<img src="/_modules/uplink/img/pulse.gif"/><br/>Выполняется операция...';
        var $cell6=document.createElement("div");
        $cell6.style.display='table-cell';
        $cell6.style.width='33%';
        $middleRowDiv.appendChild($cell4);
        $middleRowDiv.appendChild($cell5);
        $middleRowDiv.appendChild($cell6);

        var $bottomRowDiv=document.createElement("div");
        $bottomRowDiv.style.display='table-row';
        $bottomRowDiv.style.height='33%';
        var $cell7=document.createElement("div");
        $cell7.style.display='table-cell';
        var $cell8=document.createElement("div");
        $cell8.style.display='table-cell';
        var $cell9=document.createElement("div");
        $cell9.style.display='table-cell';
        $bottomRowDiv.appendChild($cell7);
        $bottomRowDiv.appendChild($cell8);
        $bottomRowDiv.appendChild($cell9);

        $lockDiv.appendChild($topRowDiv);
        $lockDiv.appendChild($middleRowDiv);
        $lockDiv.appendChild($bottomRowDiv);

        document.body.appendChild($lockDiv);
    }
    
    function rtrScreenUnlock(){
        var $ls=getById('uplLockScreenDiv');
        if ($ls!=undefined){
            document.body.removeChild($ls);
        }
    }



/*---------------------------------------------------------------------------*/

    function clickOn($tabname, $module){
        //var randID=Math.floor(Math.random() * Math.floor(100000));
        //getById('workfield').innerHTML='<div id="wf_'+randID+'" style="width: 100%;"></div>';
        //include_dom('/_ui/html2var.js.php?var=wf_'+randID+'&url=/_modules/'+$module+'/html/'+$tabname+'.html');
        $xhrSidebar.onload = function(){
            if ($xhrSidebar.status === 200){
                getById("workfield").innerHTML = $xhrSidebar.responseText;
                include_dom("/_modules/routers/html/"+$tabname+".html.js");
            }
        }
        $xhrSidebar.open("GET", "/_modules/routers/php/example.php?page="+$tabname);
        $xhrSidebar.send("");
        
        var btnList=getById('sidebarButtons').getElementsByClassName('tabBtn_a');
        for ($i=0; $i<btnList.length; $i++){
            getById(btnList[$i].id).className="tabBtn";
        }
        getById('listBtn_'+$tabname).className="tabBtn_a";
    }
    
cls();
var wrapper = document.getElementById('wrapper');
var sidebar = document.createElement('div');
sidebar.className='sidebar';
sidebar.id='sidebar';
sidebar.style.float='left';
var workfield = document.createElement('div');
workfield.id='workfield';
workfield.className='workfield';
workfield.style.height='100%';
workfield.style.marginLeft='64px';
workfield.style.paddingLeft='10px';
workfield.style.paddingTop='48px';
workfield.style.boxSizing='border-box';
workfield.style.overflowY='auto';
wrapper.appendChild(sidebar);
wrapper.appendChild(workfield);
    
var sbhttp=new XMLHttpRequest();
sbhttp.onreadystatechange=function()
{
    if (sbhttp.readyState==4 && sbhttp.status==200){
        if (document.getElementById('sidebar')!=undefined){
            document.getElementById('sidebar').innerHTML=sbhttp.responseText;
            $xhrSidebar = new XMLHttpRequest();
            $xhrSidebar.onload = loadPage;
            let page = explode("_",document.getElementById("sidebarButtons").childNodes[1].id)[1];
//            console.log(document.getElementById("sidebarButtons").childNodes[1]);
            $xhrSidebar.open("GET", "/_modules/routers/php/example.php?page="+page);
            $xhrSidebar.send("");
        }
        
    }
};
sbhttp.open("POST","/_modules/routers/helpers/sidebarGen.php",true);
sbhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
sbhttp.send('');



//include_dom('/_ui/html2var.js.php?var=workfield&url=/_modules/routers/html/index.html');


/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/












delete($tableHeader);

var $tableHeader = [
    "mac",
    "curState",
    "address",
    "dnum",
    "name",
    "inCharge"
];







/*---------------------------------------------------------------------------*/




function resetRouterSearch(){
    
    $tbody = document.getElementById("tbody");
    let $params = [];
    let $city = [];
    let $states = [];
    initParams($params);
    initStates($states);
    initCity($city);
    for(let $key in $params){
        $params[$key].value = "";
    }
    for(let $key in $city){
        $city[$key].checked = false;
    }
    for(let $key in $states){
        $states[$key].checked = false;
    }
    let $row = null;
    while($row = $tbody.rows[0]){
        $tbody.removeChild($row);
    }
    
}




/*---------------------------------------------------------------------------*/


function resetTicketSearch(){
    
    let $city = [];
    
    let $params = [];
    let $tbody1 = document.getElementById("ticketTbody1");
    let $tbody2 = document.getElementById("ticketTbody2");
    $params["name"] = document.getElementById("nameInput");
    $params["address"]= document.getElementById("addressInput");
    $params["dayNegFrom"] = document.getElementById("dayNegFromInput");
    $params["dayNegTo"] = document.getElementById("dayNegToInput");
    
    
    initCity($city);
    
    for(let $key in $params){
        $params[$key].value = "";
    }
    for(let $key in $city){
        $city[$key].checked = false;
    }
    let $row = null;
    while($row = $tbody1.rows[0]){
        $tbody1.removeChild($row);
    }
    while($row = $tbody2.rows[0]){
        $tbody2.removeChild($row);
    }
    
}


/*---------------------------------------------------------------------------*/


function findButtonClick(){
    if (checkList()){
        let $params = [];
        let $city = [];
        let $states = [];
        initParams($params);
        initStates($states);
        initCity($city);
        let $fog = document.getElementById("messageBox");
        let $loading = document.getElementById("messageLoading");

        let $json = {
            "params" : {},
            "city" : [],
            "states" : [],
            "type" : "routerIndex"
        };
        for(let $key in $params){
            if ($params[$key].value){
                $json["params"][$key] = $params[$key].value;
            }
        }
        for (let $key in $city){
            if ($city[$key].checked){
                $json["city"][$json["city"].length] = $key;
            }
        }
        for (let $key in $states){
            if ($states[$key].checked){
                $json["states"][$json["states"].length] = $key;
            }
        }
        let $dec = new Decoder();
        let $data = $dec.arrayToStr($json);
        $xhr.onload = function(){
            fillTable("tbody",this);
            let states = [];
            initStates(states);
            for (let key in states){
                let count = document.getElementById(key+"_count");
                if (count){
                    count = Number(count.textContent);
                }
                else{
                    count = 0;
                }
                if (count > 0){
                    document.getElementById(key+"Count").textContent = count;
                }
                else{
                    document.getElementById(key+"Count").textContent = "";
                }
            }
            let cities = [];
            initCity(cities);
            for (let key in cities){
                let count = document.getElementById(key+"_count");
                if (count){
                    count = Number(count.textContent);
                }
                else{
                    count = 0;
                }
                if (count > 0){
                    document.getElementById(key+"Count").textContent = count;
                }
                else{
                    document.getElementById(key+"Count").textContent = "";
                }
            }
            rtrScreenUnlock();
//            $fog.classList.add("hidden");
//            $loading.classList.add("hidden");
        };
        console.log("GET", "/_modules/routers/php/search.php?"+"data="+$data);
        $xhr.open("GET", "/_modules/routers/php/search.php?"+"data="+$data);
        $xhr.send();
        rtrScreenLock();
//        $fog.classList.remove("hidden");
//        $loading.classList.remove("hidden");
    }
}



/*---------------------------------------------------------------------------*/


function messageButtonClick(){
    fUnMsgBlock("messageBlock");
}


/*---------------------------------------------------------------------------*/


function tSearchButtonClick(){
    if (checkCityList()){
        
        let $params = [];
        let $city = [];
        let $start = Number(document.getElementById("dayNegFromInput").value);
        let $end = Number(document.getElementById("dayNegToInput").value);
        if ($end == 0){
            $end = 100000;
        }
        $params["name"] = document.getElementById("nameInput");
        $params["address"] = document.getElementById("addressInput");
        $params["dnum"] = document.getElementById("dnumInput");
        initCity($city);
        let $fog = document.getElementById("messageBox");
        let $loading = document.getElementById("messageLoading");

        let $json = {
            "params" : {},
            "city" : [],
            "states" : [],
            "type" : "routerStateInTaking",
            "start" : $start,
            "end" : $end
        };
        for(let $key in $params){
            if ($params[$key].value){
                $json["params"][$key] = $params[$key].value;
            }
        }
        for (let $key in $city){
            if ($city[$key].checked){
                $json["city"][$json["city"].length] = $key;
            }
        }
        let $dec = new Decoder();
        
        let $data = $dec.arrayToStr($json);
        
        $xhr.onload = function(){
            fillTable("ticketTbody2",this);
            let inTaking = document.getElementById("tButtonInTaking");
            let count = Array.from(document.getElementById("ticketTbody2").rows).length;
            inTaking.textContent += " : " + count;
        };
        $xhr.open("GET", "/_modules/routers/php/search.php?"+"data="+$data);
        $xhr.send();
        $json["type"] = "routerStateInWork";
        $data = $dec.arrayToStr($json);
        $xhr2.onload = function(){
            fillTable("ticketTbody1",this);
            let inWork = document.getElementById("tButtonInWork");
            let count = Array.from(document.getElementById("ticketTbody1").rows).length;
            inWork.textContent += " : " + count;
//            $fog.classList.add("hidden");
//            $loading.classList.add("hidden");
            rtrScreenUnlock();
        };
        $xhr2.open("GET", "/_modules/routers/php/search.php?"+"data="+$data);
        $xhr2.send();
        rtrScreenLock();
//        $fog.classList.remove("hidden");
//        $loading.classList.remove("hidden");
    }
}




/*---------------------------------------------------------------------------*/


function showMessage(
        $text
){
    let $message = document.createElement("div");
    let $messageText = document.createElement("div");
    let $messageButton = document.createElement("button");
    $message.classList.add("message");
    $messageText.classList.add("text");
    $messageText.innerHTML = $text;
    $messageButton.setAttribute("onclick","messageButtonClick()");
    $messageButton.textContent = "OK";
    $message.appendChild($messageText);
    $message.appendChild($messageButton);
    console.log($message);
    fMsgBlock("",$message.outerHTML,280,"messageBlock");
    
    
}


/*---------------------------------------------------------------------------*/


function checkCityList(){
    let $city = [];
    initCity($city);
    let $fog = document.getElementById("messageBox");
    let $message = document.getElementById("message");
    let $messageText = document.getElementById("messageText");
    
    $cityFlag = false;
    for (let $key in $city){
        if ($city[$key].checked){
            $cityFlag = true;
        }
        
    }
    if (!$cityFlag){
        $buf = "Не выбрано значение<br>Город";
        showMessage($buf);
        return false;
    }
    return true;
    
}


/*---------------------------------------------------------------------------*/

function checkList(){
    let $params = [];
    let $city = [];
    let $states = [];
    initParams($params);
    initStates($states);
    initCity($city);
    let $fog = document.getElementById("messageBox");
    let $message = document.getElementById("message");
    let $messageText = document.getElementById("messageText");
    
    $statesFlag = false;
    for (let $key in $states){
        if ($states[$key].checked){
            $statesFlag = true;
        }
    }
    $cityFlag = false;
    for (let $key in $city){
        if ($city[$key].checked){
            $cityFlag = true;
        }
        
    }
    $buf = {};
    $buf["state"] = $statesFlag;
    $buf["city"] = $cityFlag;
    $result = "";
    if ($buf["city"] === false){
        $result += "город ";
    }
    if ($buf["state"] === false){
        $result += "статус";
    }
    if ($result !== ""){
        $text = "Не выбрано значение<br>"+$result;
        showMessage($text);
        return false;
    }
    return true;
    
}




/*---------------------------------------------------------------------------*/

var $stateName = {
    "installed" : "Установлен",
    "inTaking" : "На изъятии",
    "atServEng" : "У инженера",
    "toStore" : "Передача на склад",
    "store" : "Хранение",
    "testing" : "Тестирование",
    "writeOff" : "Списан",
    "lost" : "Утерян"
};



function cellCheck(
        $type,
        $value
){
    if ($value == null){
        return "";
    }
    if ($type === "lastComment"){
        $comment = "["
        return ;
    }
    if ($type === "curState"){
        return $stateName[$value];
    }
    if ($type === "address"){
        $reg = /.*?,/;
        $value = $value.replace($reg,""); 
        $reg = /,,*/g;
        return $value.replace($reg," , ");
    }
    return $value;
}




/*---------------------------------------------------------------------------*/



function routerFormOnload(){
    if ($xhrRouter.status === 200){
        let $fog = document.getElementById("messageBox");
        let $loading = document.getElementById("messageLoading");
        let $data = $xhrRouter.responseText;
        fMsgBlock("Информация о роутере", $data,700,"routerForm");
        
        
        $fog.classList.add("hidden");
        $loading.classList.add("hidden");
    }
}


/*---------------------------------------------------------------------------*/


function routerFormClose(){
    let $routerFormBlock = document.getElementById("routerFormBlock");
    $routerFormBlock.classList.add("hidden");
}


/*---------------------------------------------------------------------------*/


function getRouterFormClick(
        $index,
        $tbId
){
    let $fog = document.getElementById("messageBox");
    let $loading = document.getElementById("messageLoading");
    let $tbody = document.getElementById($tbId);
    let $mac = $tbody.rows[$index].cells[0].textContent;
    
    $xhrRouter.open("GET", "/_modules/routers/php/get_router_form.php?"+"mac="+$mac);
    $xhrRouter.send();
    $fog.classList.remove("hidden");
    $loading.classList.remove("hidden");
}



/*---------------------------------------------------------------------------*/


function getSimpleRouterForm(
        mac
){
    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        if (xhr.status == 200){
            fMsgBlock("информация о роутере", xhr.responseText, 700, "routerForm");
            rtrScreenUnlock();
            
        }
    }
    xhr.open("GET", "/_modules/routers/php/get_simple_router_form.php?mac="+mac);
    xhr.send();
    rtrScreenLock();
}


/*---------------------------------------------------------------------------*/


function showTicketForm(
        $xhr

){
    if ($xhr.status === 200){
        let $fog = document.getElementById("messageBox");
        let $loading = document.getElementById("messageLoading");
        let $data = $xhr.responseText;
        msgblock("Информация о тикете", $data);
        
        
        $fog.classList.add("hidden");
        $loading.classList.add("hidden");
    }
}


/*---------------------------------------------------------------------------*/


function getTicketFormClick(
        $index,
        $tbId,
        $type
){
    $tableIndex = $index;
    $tableId = $tbId;
    let $fog = document.getElementById("messageBox");
    let $loading = document.getElementById("messageLoading");
    let $tbody = document.getElementById($tbId);
    let $mac = $tbody.rows[$index].cells[0].textContent;
    $xhr.onload = function(){
        showTicketForm(this);
    }
    $dec = new Decoder();
    $data = [];
    $data["mac"] = $mac;
    $data["type"] = $type;
    $result = $dec.arrayToStr($data);
    $xhr.open("GET", "/_modules/routers/php/get_ticket_form.php?"+"data="+$result);
    console.log("/_modules/routers/php/get_ticket_form.php?"+"data="+$result);
    $xhr.send();
    $fog.classList.remove("hidden");
    $loading.classList.remove("hidden");
}


/*---------------------------------------------------------------------------*/


function tableButtonClick(
        $tbId
){
    if ($tbId === "inWork"){
        document.getElementById("tableBox1").classList.remove("hidden");
        document.getElementById("tableBox2").classList.add("hidden");
        document.getElementById("tButtonInWork").classList.add("selected");
        document.getElementById("tButtonInTaking").classList.remove("selected");
    }
    if ($tbId === "inTaking"){
        document.getElementById("tableBox2").classList.remove("hidden");
        document.getElementById("tableBox1").classList.add("hidden");
        document.getElementById("tButtonInWork").classList.remove("selected");
        document.getElementById("tButtonInTaking").classList.add("selected");
    }
}


/*---------------------------------------------------------------------------*/


function addRow(
        $tbody,
        $data,
        $index = -1,
        $tHeader = null
        
){
    if ($tHeader == null){
        $tHeader = $tableHeader;
    }
    $row = document.createElement("tr");
    $row.classList.add("tr");
    if ($index >= 0){
        $row.setAttribute("onclick",'tableRowClick('+$index+')');
    }
    for (let $i = 0; $i<$tHeader.length; $i++){
        $cell = document.createElement("td");
        $cell.classList.add("td");
        let $index = $tHeader[$i];
        $cell.textContent = cellCheck($index, $data[$index]);
        $row.appendChild($cell);
    }
    $tbody.appendChild($row);
    
}



/*---------------------------------------------------------------------------*/



function selectAll(
        $param
){
    $checkList = [];
    if ($param === "city" ){
        initCity($checkList);
    }
    else if($param === "states"){
        initStates($checkList);
    }
    $val = true;
    for (let $key in $checkList){
        if ($checkList[$key].checked){
            $val = false;
        }
    }
    for (let $key in $checkList){
        $checkList[$key].checked = $val;
    }
}



/*---------------------------------------------------------------------------*/


function fillTable(
        $tbId,
        $xhr
){

    let $fog = document.getElementById("messageBox");
    let $loading = document.getElementById("messageLoading");
    if ($xhr.status === 200){
        let $data = $xhr.responseText;
        let $tbody = document.getElementById($tbId);
        $tbody.innerHTML = $data;
        
    }
    
}


/*---------------------------------------------------------------------------*/


function tSearchOnload(){
    let $fog = document.getElementById("messageBox");
    let $loading = document.getElementById("messageLoading");
    if ($xhr.status === 200){
        let $data = $xhr.responseText;
        let $tbody = document.getElementById("ticketTbody2");
        $tbody.innerHTML = $data;
    }
    $fog.classList.add("hidden");
    $loading.classList.add("hidden");
}


/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/


function initParams(
        $params
){
    $params["name"] = document.getElementById("nameInput");
    $params["address"] = document.getElementById("addressInput");
    $params["mac"] = document.getElementById("macInput");
    $params["dnum"] = document.getElementById("dnumInput");
    $params["inCharge"] = document.getElementById("inChargeInput");
}
function initStates(
        $states
){
    $states["installed"] = document.getElementById("installedCheck");
    $states["inTaking"] = document.getElementById("inTakingCheck");
    $states["atServEng"] = document.getElementById("atServEngCheck");
    $states["toStore"] = document.getElementById("toStoreCheck");
    $states["store"] = document.getElementById("storeCheck");
    $states["testing"] = document.getElementById("testingCheck");
    $states["writeOff"] = document.getElementById("writeOffCheck");
    $states["lost"] = document.getElementById("lostCheck");
    $states["delete"] = document.getElementById("deleteCheck");
}


function initCity(
        $city
){
    $city["lsk"] = document.getElementById("lskCheck");
    $city["kst"] = document.getElementById("kstCheck");
    $city["kchr"] = document.getElementById("kchrCheck");
}


function initAll(){
    let $fog = document.getElementById("messageBox");
    let $message = document.getElementById("message");
    let $messageText = document.getElementById("messageText");
    let $messageButton = document.getElementById("messageButton");
    let $loading = document.getElementById("messageLoading");


    let $tbody = document.getElementById("tbody");
}





var $xhrRouter = new XMLHttpRequest();
$xhrRouter.onload = routerFormOnload;

var $xhr = new XMLHttpRequest();
var $xhr2 = new XMLHttpRequest();
var $xhr3 = new XMLHttpRequest();





/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/





function menuElemClick(
        $index
){
    for (let $i = 1; $i < 7; $i++){
        document.getElementById("routerMenuElem"+String($i)).classList.remove("selected");
        document.getElementById("routerForm"+String($i)).classList.add("hidden");
    }
    document.getElementById("routerMenuElem"+String($index)).classList.add("selected");
    document.getElementById("routerForm"+String($index)).classList.remove("hidden");
}







/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*
 * fMsgBlock
 * 
 * ---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/




function fUnMsgBlock(
        $blockId = 'msgbboxdiv'
){
    $obj=document.getElementById($blockId);
    if ($obj!=undefined){
        document.body.removeChild($obj);
    }
}

function fMsgBlock(
        $title, 
        $content, 
        $height = '700', 
        $blockId = 'msgbboxdiv',
        $fog = false,
        $onclose = ''
){
    
    
    var msgbback = document.createElement('div');
    msgbback.style.position='absolute';
    msgbback.id=$blockId;
    msgbback.style.backgroundColor='rgba(255, 255, 255, 0.7)';
    msgbback.style.left='0';
    msgbback.style.top='0';
    msgbback.style.width='100%';
    msgbback.style.height='100%';
    msgbback.style.zIndex='4000';
    
    var msgbdiv = document.createElement('div');  
    
    msgbdiv.className='msgbboxClass';    
    msgbdiv.style.position='absolute';
    msgbdiv.style.boxSizing='border-box';
    msgbdiv.style.height=$height+'px';
    msgbdiv.style.width='1000px';
    msgbdiv.style.display='table';
    msgbdiv.style.zIndex='4050';
    msgbdiv.style.top='50%';
    msgbdiv.style.marginTop='-'+($height/2)+'px';
    msgbdiv.style.left='50%';
    msgbdiv.style.marginLeft='-500px';
    
    //msgbdiv.style.backgroundColor='#f00';
    //msgdiv.className='msgboxClass';    
    
    var msgbdiv_hdr = document.createElement('div');
    msgbdiv_hdr.style.display='table-row';
    msgbdiv_hdr.style.height='35px';
    msgbdiv_hdr.className='msgbboxClass_header';
    msgbdiv_hdr.style.fontSize='22px';
    msgbdiv_hdr.innerHTML='&nbsp;&nbsp;'+$title+'<div class="msgboxClass_close" onclick="fUnMsgBlock(\''+$blockId+'\'); '+$onclose+'">X</div><div style="float: none;"></div>';

    var msgbdiv_txt = document.createElement('div');
    msgbdiv_txt.style.display='table-row';
    msgbdiv_txt.style.fontSize='14px';
    msgbdiv_txt.innerHTML='<div class="msgboxClass_content" style="height: '+($height-35)+'px; min-height: '+($height-35)+'px; max-height: '+($height-35)+'px;"><div style="padding: 10px;">'+$content+'</div></div>';
    if (!$fog){
        msgbdiv.appendChild(msgbdiv_hdr);
    }
    msgbdiv.appendChild(msgbdiv_txt);
    
    msgbback.appendChild(msgbdiv);
    
    document.body.appendChild(msgbback);
}


/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/




/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*
 * alertForm
 * 
 * ---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/



function alertButtonClick(){
    
    $tbody = document.getElementById("alertTbody");
    $xhr.onload = function(){
        if ($xhr.status === 200){
            $tbody.innerHTML = $xhr.responseText;
            rtrScreenUnlock();
        }
            
    };
    
    $xhr.open("GET", "/_modules/routers/php/get_alertTable.php");
    $xhr.send();
    rtrScreenLock();
    
}




/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/










/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*
 * TicketForm
 * 
 * ---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/


function changeComment(
        $func,
        $status
        
){
    let $type = document.getElementById("ticketType").textContent;
    let $author = getCookie("login");
    let $text = "[ Изменил статус на '"+$status+"' ]";
    let $mac = document.getElementById("router_mac").textContent;
    $xhr2.onload = function(){
        if ($xhr2.status === 200){
            $func();
        }
    };
    let $data = {
        "mac" : $mac,
        "type" : $type,
        "author" : $author,
        "text" : $text
    };
    let $result = $dec.arrayToStr($data);
    $xhr2.open("GET","/_modules/routers/php/add_ticketComment.php?data="+$result);
    $xhr2.send();
    
    
}


/*---------------------------------------------------------------------------*/



function closeCommentForm(
        $blockId
){
    $commentFormText = document.getElementById("commentFormText").value;
    fUnMsgBlock($blockId);
}



/*---------------------------------------------------------------------------*/


function toLost(){
    let $author = getCookie("login");
    let $mac = document.getElementById("router_mac").textContent;
    let $text = $commentFormText;
    
    $xhr.onload = function(){
        if ($xhr.status === 200){
                unmsgblock();
                fMsgBlock("",$xhr.responseText,100,"commentMessageBlock");
                fUnMsgBlock("loading");
                tSearchButtonClick();
            }
    };
    let $data = {
        "params" : {
            "author" : $author,
            "profile" : $author,
            "comment" : $text
        },
        "mac" : $mac,
        "state" : "lost"
    };
    let $dec = new Decoder();
    let $result = $dec.arrayToStr($data);
    $xhr.open("GET", "/_modules/routers/php/change_router.php?data="+$result);
    $xhr.send();
    fMsgBlock("","Загрузка",100, "loading",true);
    
}



/*---------------------------------------------------------------------------*/


function toWriteOff(){
    let $author = getCookie("login");
    let $mac = document.getElementById("router_mac").textContent;
    let $text = $commentFormText;
    
    $xhr.onload = function(){
        if ($xhr.status === 200){
                unmsgblock();
                fMsgBlock("",$xhr.responseText,100,"commentMessageBlock");
                fUnMsgBlock("loading");
                tSearchButtonClick();
            }
    };
    let $data = {
        "params" : {
            "author" : $author,
            "profile" : $author,
            "comment" : $text
        },
        "mac" : $mac,
        "state" : "writeOff"
    };
    let $dec = new Decoder();
    let $result = $dec.arrayToStr($data);
    $xhr.open("GET", "/_modules/routers/php/change_router.php?data="+$result);
    $xhr.send();
    fMsgBlock("","Загрузка",100, "loading",true);
    
}



/*---------------------------------------------------------------------------*/


function toInstalled(){
    let $author = getCookie("login");
    let $mac = document.getElementById("router_mac").textContent;
    let $dnum = document.getElementById("router_dnum").textContent;
    
    $xhr.onload = function(){
        if ($xhr.status === 200){
            fMsgBlock("",$xhr.responseText,100,"commentMessageBlock");
            unmsgblock();
            tSearchButtonClick();
            fUnMsgBlock("loading");
        }
    };
    let $data = {
        "params" : {
            "dnum" : $dnum,
            "author" : $author
        },
        "mac" : $mac,
        "state" : "installed"
    };
    let $dec = new Decoder();
    let $result = $dec.arrayToStr($data);
    $xhr.open("GET", "/_modules/routers/php/change_router.php?data="+$result);
    $xhr.send();
    fMsgBlock("","Загрузка",100, "loading",true);
}




/*---------------------------------------------------------------------------*/


function toInTaking(){
    let $mac = document.getElementById("router_mac").textContent;
    let $dnum = document.getElementById("router_dnum").textContent;
    let $author = getCookie("login");
    $xhr.onload = function(){
        if ($xhr.status === 200){
            fMsgBlock("",$xhr.responseText,100,"commentMessageBlock");
            unmsgblock();
            tSearchButtonClick();
        }
    };
    $data = {
        "params" : {
            "dnum" : $dnum,
            "author" : $author
        },
        "mac" : $mac,
        "state" : "inTaking"
    };
    $dec = new Decoder();
    $result = $dec.arrayToStr($data);
    console.log($result);
    $xhr.open("GET", "/_modules/routers/php/change_router.php?data="+$result);
    $xhr.send();
}



/*---------------------------------------------------------------------------*/


function toStore(){
    let $author = getCookie("login");
    let $mac = document.getElementById("router_mac").textContent;
    $xhr.onload = function(){
        if ($xhr.status === 200){
            fMsgBlock("",$xhr.responseText,100,"commentMessageBlock");
            unmsgblock();
            tSearchButtonClick();
        }
    };
    $data = {
        "params" : {
            "store" : "Склад",
            "storeMan" : "Кладовщик",
            "author" : $author
        },
        "mac" : $mac,
        "state" : "store"
    };
    $dec = new Decoder();
    $result = $dec.arrayToStr($data);
    $xhr.open("GET", "/_modules/routers/php/change_router.php?data="+$result);
    $xhr.send();
}


/*---------------------------------------------------------------------------*/



function confirmForm(
        $callback
){
    $xhr.onload = function(){
        if ($xhr.status === 200){
            fMsgBlock("",$xhr.responseText,250,"confirmForm");
        }
    };
    $xhr.open("GET", "/_modules/routers/php/get_confirmForm.php?funcName="+$callback);
    $xhr.send();
}



/*---------------------------------------------------------------------------*/


function commentConfirmForm(
        $callback
){
    $xhr.onload = function(){
        if ($xhr.status === 200){
            fMsgBlock("",$xhr.responseText,500,"commentConfirmForm");
        }
    };
    $xhr.open("GET", "/_modules/routers/php/get_commentConfirmForm.php?funcName="+$callback);
    $xhr.send();
}


/*---------------------------------------------------------------------------*/



function addTicketCommentButtonClick(){
   
    let $author = getCookie("login");
    let $text = document.getElementById("commentInput").value;
    let $fog = document.getElementById("messageBox");
    let $message = document.getElementById("message");
    let $messageText = document.getElementById("messageText");
    let $mac = document.getElementById("router_mac").textContent;
    let $type = document.getElementById("ticketType").textContent;
    let $dec = new Decoder();
    $text = $text.trim();
    if (!$text){
        fMsgBlock("","Поле комментария пустое",100,"commentMessageBlock");
    }
    else{
        $xhr.onload = function(){
            if ($xhr.status === 200){
                
                fMsgBlock("",$xhr.responseText,100,"commentMessageBlock");
                let $tbody = document.getElementById($tableId);
                $tbody.rows[$tableIndex].cells[3].textContent = document.getElementById("newComment").textContent;
                fUnMsgBlock("commentMessageBlock");
                unmsgblock();
                getTicketFormClick($tableIndex,$tableId,$type);
            }
            
        };
        let $data = {
            "mac" : $mac,
            "type" : $type,
            "author" : $author,
            "text" : $text
        };
        let $result = $dec.arrayToStr($data);
        $xhr.open("GET","/_modules/routers/php/add_ticketComment.php?data="+$result);
        $xhr.send();
    }
}

/*---------------------------------------------------------------------------*/

function addCommentButtonClick(){
   
    let $author = getCookie("login");
    let $text = document.getElementById("commentInput").value;
    let $fog = document.getElementById("messageBox");
    let $message = document.getElementById("message");
    let $messageText = document.getElementById("messageText");
    let $mac = document.getElementById("router_mac").textContent;
    let $dec = new Decoder();
    $text = $text.trim();
    if (!$text){
        fMsgBlock("","Поле комментария пустое",100,"commentMessageBlock");
    }
    else{
        $xhr.onload = function(){
            if ($xhr.status === 200){
                
//                fMsgBlock("",$xhr.responseText,100,"commentMessageBlock");
//                findButtonClick();
                
                fUnMsgBlock("routerForm");
                $xhrRouter.open("GET", "/_modules/routers/php/get_simple_router_form.php?"+"mac="+$mac);
                $xhrRouter.send();
                
            }
            
        };
        let $data = {
            "mac" : $mac,
            "author" : $author,
            "text" : $text
        };
        let $result = $dec.arrayToStr($data);
        console.log($result);
        $xhr.open("GET","/_modules/routers/php/add_comment.php?data="+$result);
        $xhr.send();
    }
}


/*---------------------------------------------------------------------------*/


function reportButtonClick(){
    
    let start = document.getElementById("dateStart").value;
    let end = document.getElementById("dateEnd").value;
    let city = document.getElementById("city");
    city = city.options[city.selectedIndex].value;
    let type = document.getElementById("reportType");
    type = type.options[type.selectedIndex].value;
    console.log(city);
    console.log(type);
    start = Date.parse(start);
    end = Date.parse(end);
    if ((!start)||(!end)){
        showMessage("Выберите период времени");
        return;
    }
    let data = [];
    data["start"] = (start/1000);
    data["end"] = (end/1000);
    data["city"] = city;
    data["type"] = type;
    let dec = new Decoder();
    result = dec.arrayToStr(data);
    $xhr.onload = function(){
        if ($xhr.status == 200){
            document.getElementById("reportData").innerHTML = $xhr.responseText;
            rtrScreenUnlock();
        }
    }
    $xhr.open("GET", "/_modules/routers/php/get_state_report.php?data="+result);
    $xhr.send();
    rtrScreenLock();
}




/*---------------------------------------------------------------------------*/




function tableOpenClose(
        id
){
    let elem = document.getElementById(id);
    if (elem.getAttribute("devstate") == "close"){
        let list = document.getElementsByClassName(id);
        for(i = 0; i < list.length; i++){
            list[i].classList.add("hidden");
            list[i].setAttribute("devstate","close");
        }
        list = document.getElementsByClassName(id+"_child");
        for(i = 0; i < list.length; i++){
            list[i].classList.remove("hidden");
            list[i].setAttribute("devstate","close");
        }
        elem.setAttribute("devstate","open");
    }
    else{
        let list = document.getElementsByClassName(id);
        for(i = 0; i < list.length; i++){
            list[i].classList.add("hidden");
            list[i].setAttribute("devstate","close");
        }
        list = document.getElementsByClassName(id+"_child");
        for(i = 0; i < list.length; i++){
            list[i].classList.add("hidden");
            list[i].setAttribute("devstate","close");
        }
        elem.setAttribute("devstate","close");
    }
}


/*---------------------------------------------------------------------------*/


function changeStatusButtonClick(
        state
){
    let author = getCookie("login");
    let mac = document.getElementById("router_mac").textContent.trim();
    let data = "mac="+mac+"&author="+author+"&state="+state;
    if (state == "updateState"){
        $xhr.onload = function(){
            if ($xhr.status == 200){
                fMsgBlock("Статус обновлен",$xhr.responseText,450,"stateChange");
                rtrScreenUnlock();
            }
        };
        $xhr.open("GET", "/_modules/routers/php/updateState.php?mac="+mac);
        $xhr.send();
        rtrScreenLock();
    }
    else{
        $xhr.onload = function(){
            if ($xhr.status == 200){
                fMsgBlock("Изменить статус",$xhr.responseText,450,"stateChange");
                rtrScreenUnlock();
            }
        };
        $xhr.open("GET", "/_modules/routers/php/get_change_router_form.php?"+data);
        console.log("/_modules/routers/php/get_change_router_form.php?"+data);
        $xhr.send();
        rtrScreenLock();
    }
}



/*---------------------------------------------------------------------------*/



function confirmButtonClick(){
    let selectList = [
        "installer",
        "servEng",
        "tester"
    ];
    let mac = document.getElementById("mac").textContent.trim();
    let state = document.getElementById("state").textContent.trim();
    let count = Number(document.getElementById("count").textContent);

    let params = {};
    for (i = 0; i < count-1; i++){
        let key = document.getElementById("key"+i).getAttribute("devid");
        let value = "";
        if (selectList.includes(key)){
            let select = document.getElementById("value"+i);
            value = select.options[select.selectedIndex].value;
        }
        else {
            value = document.getElementById("value"+i).value;
        }   
        params[key] = value;
    }
    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        if (xhr.status == 200){
            showMessage(xhr.responseText);
            rtrScreenUnlock();
            fUnMsgBlock("stateChange");
            fUnMsgBlock("routerForm");
            findButtonClick();
        }
    }
    let dec = new Decoder();
    let data = {};
    data["params"] = params;
    data["mac"] = mac;
    data["state"] = state;
    data["params"]["author"] = getCookie("login");
    let result = dec.arrayToStr(data);
    console.log(result);
    xhr.open("GET","/_modules/routers/php/change_router.php?data="+result);
    xhr.send();
    rtrScreenLock();
}



/*---------------------------------------------------------------------------*/




$commentFormText = "";
$tableIndex = -1;
$tableId = "";








