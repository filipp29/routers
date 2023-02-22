<?php

require_once './RouterBase.php';
require './Decoder.php';


$str = $_GET["data"];
$dec = new \Decoder();

$data = $dec->strToArray($str);

$mac = $data["mac"];
$mac = \RouterBase::getValidMac($mac);
if (strlen($mac) != 12){
    throw new Exception("Не верный мак ".$mac);
}
$mac = \RouterBase::getMacName($mac);
$author = $data["author"];
$text = $data["text"];
try{
    $router = new \RouterBase($mac);
    $router->addComment($text, $author);
} catch (Exception $ex) {
    echo $ex->getMessage();
}
echo "Комментарий добавлен";





