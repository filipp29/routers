<div style="display: none">
    <div id="macChkd"><?=$mac?></div>
    <div id="dnumChkd"><?=$dnum?></div>
    <div id="servEngChkd"><?=$servEng?></div>
    <div id="authorChkd"><?=$author?></div>
</div>
<div>
    <button onclick="sendCheckedData()" style="min-width: 140px; height: 40px; margin-top: 0px; margin-right: 30px;">
        Отправить
    </button>
</div>
<div id="xhrMessage">

</div>
<script>
    function sendCheckedData(){
        let macChkd = document.getElementById("macChkd").textContent;
        let dnumChkd = document.getElementById("dnumChkd").textContent;
        let servEngChkd = document.getElementById("servEngChkd").textContent;
        let authorChkd = document.getElementById("authorChkd").textContent;
        let data = "mac="+macChkd+"&dnum="+dnumChkd+"&servEng="+servEngChkd+"&author="+authorChkd+"&checked=on";
        xhrChkd = new XMLHttpRequest();
        xhrChkd.onload = function(){
            if (xhrChkd.status == 200){
                document.getElementById("xhrMessage").innerHTML = xhrChkd.responseText;
            }
        };
        console.log(data);
        xhrChkd.open("GET", "/_modules/routers/php/sup_take_router.php?"+data);
        xhrChkd.send();
    }
</script>