<?php




$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
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
        echo "����� ������� ������";
        
    }
    else{
        echo "��� ��������� ������ ". $data["type"];
    }
    
    
} catch (Exception $exc) {
    echo $exc->getMessage();
}










