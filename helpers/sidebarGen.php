<div style="margin-top: 64px;"></div>
<div id="sidebarButtons">
<?php


function checkLogin(
        $login
){
    $adminList = [
        "filipp",
        "izus",
        "elenao",
        "kairat",
        "alena.g"
    ];

    if (in_array($login, $adminList)){
        return true;
    }
    else{
        return false;
    }
}




    $login = $_COOKIE["login"];
    $dir=substr(__FILE__, 0, strpos(__FILE__, '/helpers/')).'/html/';
    $modName=substr($dir, strpos($dir, '/_modules/')+strlen('/_modules/'), strpos($dir, '/html/')-(strpos($dir, '/_modules/')+strlen('/_modules/')));
    $files=scandir($dir);
    $isFirst=true;
    foreach ($files as $file){
        if (substr_count($file, '.html')==1&&substr_count($file, '.')==1){
            $title=file_get_contents($dir.trim(str_replace('.html', '.title', $file)));
            $mname=trim(str_replace('.html', '', $file));
            if (($mname != "1index") && ($mname != "2stat") && (!checkLogin($login))){
                continue;
            }
            $add='';
            if ($isFirst){
                $add='_a';
                $isFirst=false;
            }
            ?>
<div class="tabBtn<?=$add?> sbButtonClass" id="listBtn_<?=$mname?>" onclick="clickOn('<?=$mname?>', '<?=$modName?>');">
    <div class="btnin">
        <div class="sideicon" style="background-image: url(/_modules/<?=$modName?>/html/<?=$mname?>.png);"></div> <?=$title?>
    </div>
</div>
            <?php
        }
    }
?>
</div>