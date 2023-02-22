<?php

require_once './RouterBase.php';
require_once './RouterState.php';

error_reporting(E_ALL); //Включаем вывод ошибок
set_time_limit(600); //Устанавливаем максимальное время работы скрипта в секундах
ini_set('display_errors', 1); //Еще один вывод ошибок
ini_set('memory_limit', '512M'); //Устанавливаем ограничение памяти на скрипт (512Мб)

require '../html_templates/routerChangeForm/main_header.php';
require '../html_templates/routerChangeForm/state_header.php';

$curState = $_GET["state"];

$states = \RouterState::getStateName();

foreach ($states as $key => $value){
    $selected = "";
    if ($curState == $key){
        $selected = "selected";
    }
    require '../html_templates/routerChangeForm/state_data.php';
}
require '../html_templates/routerChangeForm/state_footer.php';

if ($curState){
    $params = \RouterState::getStateListAll()[$curState]["params"];
    $count = count($params);
    require '../html_templates/routerChangeForm/count.php';
    for($i = 0; $i < $count; $i++){
        $key = $params[$i];
        require '../html_templates/routerChangeForm/block.php';
    }
}
require '../html_templates/routerChangeForm/main_footer.php';
