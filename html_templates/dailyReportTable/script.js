




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


















