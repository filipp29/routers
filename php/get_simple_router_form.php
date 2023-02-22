<?php
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //”казываем корневую папку (нужно, только если работаем с консольным скриптом

//error_reporting(E_ALL); //¬ключаем вывод ошибок
//set_time_limit(600); //”станавливаем максимальное врем€ работы скрипта в секундах
//ini_set('display_errors', 1); //≈ще один вывод ошибок
//ini_set('memory_limit', '512M'); //”станавливаем ограничение пам€ти на скрипт (512ћб)
require_once './RouterForm.php';




$mac = $_GET["mac"];

$forms = new \RouterForm($mac);
$forms->show();
$forms->showSimpleFooter();
















