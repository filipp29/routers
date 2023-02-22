<?php


$adminList = [
//    "filipp",
    "elenao",
    "izus"
];


$html = $_GET["page"];
//if (($html != "2stat") && (!in_array($_COOKIE["login"],$adminList))){
//    echo "<h1> Доступ закрыт </h1>";
//}
//else{
    require_once "../html/{$html}.html";
//}