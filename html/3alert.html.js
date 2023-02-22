$tbody = document.getElementById("alertTbody");
if ($tbody){
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