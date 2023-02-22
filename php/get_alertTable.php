<?php
error_reporting(E_ALL); //�������� ����� ������
set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
ini_set('display_errors', 1); //��� ���� ����� ������
ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once $_SERVER['DOCUMENT_ROOT']. '/_modules/routers/php/RouterReports.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/_modules/routers/php/View.php';
date_default_timezone_set("Asia/Almaty");


//error_reporting(E_ALL); //�������� ����� ������
//set_time_limit(600); //������������� ������������ ����� ������ ������� � ��������
//ini_set('display_errors', 1); //��� ���� ����� ������
//ini_set('memory_limit', '512M'); //������������� ����������� ������ �� ������ (512��)


function srt(
        $a,
        $b
){
    if ((int)$a["timeStamp"] == (int)$b["timeStamp"]){
        return 0;
    }
    return ($a["timeStamp"] > $b["timeStamp"]) ? -1 : 1;
}

try{
    
    $report = new \RouterReports("alert_journal");
    $report->setDateEnd(time());
    $result = $report->getReport();
    usort($result, 'srt');
    $view = new \View("/_modules/routers/html_templates/alertTable/");
    $number = 0;
    for($i = 0; $i < count($result); $i++){
        $data = $result[$i];
        $number++;
        $data["number"] = $number;
        $view->show("data",$data);
    }

}
catch(\Exception $er){
    echo $er->getMessage();
}











