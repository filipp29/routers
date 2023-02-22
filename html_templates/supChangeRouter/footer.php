<div style="display: none">
    <div id="macOldChkd"><?=$macOld?></div>
    <div id="macNewChkd"><?=$macNew?></div>
    <div id="dnumChkd"><?=$dnum?></div>
    <div id="servEngChkd"><?=$servEng?></div>
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
        let macOldChkd = document.getElementById("macOldChkd").textContent;
        let macNewChkd = document.getElementById("macNewChkd").textContent;
        let dnumChkd = document.getElementById("dnumChkd").textContent;
        let servEngChkd = document.getElementById("servEngChkd").textContent;
        let data = "macOld="+macOldChkd+"&macNew="+macNewChkd+"&dnum="+dnumChkd+"&servEng="+servEngChkd+"&checked=on";
        xhrChkd = new XMLHttpRequest();
        xhrChkd.onload = function(){
            if (xhrChkd.status == 200){
                document.getElementById("xhrMessage").innerHTML = xhrChkd.responseText;
            }
        };
        console.log(data);
        xhrChkd.open("GET", "/_modules/routers/php/sup_change_router.php?"+data);
        xhrChkd.send();
    }
</script>