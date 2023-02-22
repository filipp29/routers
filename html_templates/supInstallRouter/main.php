
    <div>
        <h2><?=$error?></h2>
        <h3><?=$message?></h3>
    </div>
    <div style="display: none">
        <div id="macOldChkd"><?=$macOld?></div>
        <div id="macNewChkd"><?=$macNew?></div>
        <div id="dnumChk"><?=$dnum?></div>
        <div id="servEngChk"><?=$servEng?></div>
    </div>
    <div>
        <button onclick="sendCheckedData()" style="min-width: 140px; height: 40px; margin-top: 0px; margin-right: 30px;">
            Отправить
        </button>
    </div>
    <script>
        function sendCheckedData(){
            let macOldChkd = document.getElementById("macOldChkd");
            let macNewChkd = document.getElementById("macNewChkd");
            let dnumChkd = document.getElementById("dnumChkd");
            let servEngChkd = document.getElementById("servEngChk");
            let data = "macOld="+macOldChkd+"&macNew="+macNewChkd+"&dnum="+dnumChkd+"&servEng="+servEngChkd+"&checked=on";
            xhrChkd = new XMLHttpRequest();
            xhrChkd.onload = function(){

            };
            xhrChkd.open("GET", "/_modules/routers/php/sup_change_router.php?"+data);
            xhrChkd.send();
        }
    </script>






