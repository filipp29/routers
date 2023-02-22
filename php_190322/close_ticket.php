<?php




$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once '../php/RouterBase.php';
require_once './RouterState.php';
require_once './Decoder.php';



$dec = new \Decoder();
$str = $_GET["data"];

$data = $dec->strToArray($str);




try {
    
    $ticket = new \RouterTicket($data["mac"], $data["type"]);
    if ($ticket->isOpened()){
        $router= new \RouterBase($data["mac"]);
        $end = $router->getCurState();
        $ticket->closeTicket($end);
        echo "Тикет успешно закрыт";
        
    }
    else{
        echo "Нет открытого тикета ". $data["type"];
    }
    
    
} catch (Exception $exc) {
    echo $exc->getMessage();
}










