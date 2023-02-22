<?php





require_once './Decoder.php';
require_once './View.php';

$data["funcName"] = $_GET["funcName"];

$view = new \View("/_modules/routers/html_templates/confirmForm/");

$view->show("main", $data);













