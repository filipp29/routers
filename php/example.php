<?php


$adminList = [
//    "filipp",
    "elenao",
    "izus"
];


$html = $_GET["page"];
//if (($html != "2stat") && (!in_array($_COOKIE["login"],$adminList))){
//    echo "<h1> ������ ������ </h1>";
//}
//else{
    require_once "../html/{$html}.html";
//}