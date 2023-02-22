    <div>
            <button onclick="<?=$action?>" style="min-width: 140px; height: 40px; margin-top: 0px; margin-right: 30px;">
            Отправить
        </button>
    </div>
    <script>
        function getOptionValue(
                id
        ){
            return document.getElementById(id).options[document.getElementById(id).selectedIndex].value
        }
        function changeRouter(){
            let macOld = getOptionValue("macOld");
            let macNew = getOptionValue("macNew");
            if (macOld == macNew){
                return;
            }
            let dnum = document.getElementById("dnum").textContent;
            let servEng = getOptionValue("servEng");
            let data = "macOld="+macOld+"&macNew="+macNew+"&dnum="+dnum+"&servEng="+servEng;
            xhrChkd = new XMLHttpRequest();
            xhrChkd.onload = function(){
                if (xhrChkd.status == 200){
                    document.body.innerHTML = xhrChkd.responseText;
                }
            };
//            xhrChkd.open("GET", "/_modules/routers/php/sup_change_router.php?"+data);
//            xhrChkd.send();
            document.location = "/_modules/routers/php/sup_change_router.php?"+data;
        }
        
        
        function takeRouter(){
            let macOld = getOptionValue("macOld");
            let dnum = document.getElementById("dnum").textContent;
            let servEng = getOptionValue("servEng");
            let data = "mac="+macOld+"&dnum="+dnum+"&servEng="+servEng;
            xhrChkd = new XMLHttpRequest();
            xhrChkd.onload = function(){
                if (xhrChkd.status == 200){
                    document.body.innerHTML = xhrChkd.responseText;
                }
            };
//            xhrChkd.open("GET", "/_modules/routers/php/sup_take_router.php?"+data);
//            xhrChkd.send();
            document.location = "/_modules/routers/php/sup_take_router.php?"+data;
        }
        
        function installRouter(){
            let macNew = getOptionValue("macNew");
            let dnum = document.getElementById("dnum").textContent;
            let servEng = getOptionValue("servEng");
            let data = "&macNew="+macNew+"&dnum="+dnum+"&servEng="+servEng;
            xhrChkd = new XMLHttpRequest();
            xhrChkd.onload = function(){
                if (xhrChkd.status == 200){
                    document.body.innerHTML = xhrChkd.responseText;
                }
            };
//            xhrChkd.open("GET", "/_modules/routers/php/sup_install_router.php?"+data);
//            xhrChkd.send();
            document.location = "/_modules/routers/php/sup_install_router.php?"+data;
        }
    </script>