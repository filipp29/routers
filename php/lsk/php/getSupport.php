<?php



$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php');


function srt(
        $a,
        $b
){
    return (int)$b["inc_time"] - (int)$a["inc_time"];
}



$dnum = $_GET["dnum"];


$br = array_keys(objLoadBranch("/users/{$dnum}/support/", true, false));
$support = [];
foreach($br as $value){
    $obj = objLoad("/users/{$dnum}/support/{$value}");
    $support[] = $obj;
}

usort($support, "srt");

foreach($support as $value){
    $text = profileGetUsername($value["operator"])."<br>".$value["text"];
    $resolution = profileGetUsername($value["executed"])."<br>".$value["resolution"];
    echo "<tr>";
    echo "<td>";
    echo date("d.m.Y H:i:s",$value["inc_time"]);
    echo "</td>";
    echo "<td>";
    echo $text;
    echo "</td>";
    echo "<td>";
    echo $resolution;
    echo "</td>";
    echo "</tr>";
}













