
    <div>
        <div id="dnum">
            <?=$dnum?>
        </div>
        <div>
            <?=$text?>
        </div>
        <select id="macOld">
            <option value="<?=$value?>"><?=$name?></option>
        </select>
        <div>
            Новый MAC адрес
        </div>
        <select id="macNew">
            <option value="<?=$mac?>"><?=$mac?></option>
        </select>
        <div>
            Сервисный инженер
        </div>
        <select id="servEng">
            <option value="<?=$login?>"><?=$name?></option>
        </select>
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






