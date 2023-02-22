        <input type="button" name="сохранить" value="Сохранить" id="save" onclick="confirmButtonClick()">
        <script>

            function confirmButtonClick(){
                let selectList = [
                    "installer",
                    "servEng",
                    "tester"
                ];
                let mac = document.getElementById("mac").textContent;
                let state = document.getElementById("state").textContent;
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
                xhr.onload = ()=>{
                    if (xhr.status == 200){
                        alert(xhr.responseText);
                    }
                }
                let dec = new Decoder();
                let data = {};
                data["params"] = params;
                data["mac"] = mac;
                data["state"] = state;
                data["author"] = "filipp";
                let result = dec.arrayToStr(data);
                console.log(result);
//                xhr.open("GET","/_modules/router/php/change_router.php?data="+result);
            }
        </script>
        
