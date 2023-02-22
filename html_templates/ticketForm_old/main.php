<!DOCTYPE html>

<!--Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/html.html to edit this template-->

<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="windows-1251">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link id="cssstyle" rel="stylesheet" href="/_modules/routers/routers.css">
    </head>
    <body>
        <div class="ticketFormBox">
            <div class="header">
                Информация о тикете
            </div>
            <div class="routerInfo">
                <div class="block">
                    <div class="label">
                        MAC адрес:
                    </div>
                    <div class="value">
                        00:FF:12:21:21:45
                    </div>
                </div>
                <div class="block">
                    <div class="label">
                        MAC адрес
                    </div>
                    <div class="value">
                        00:FF:12:21:21:45
                    </div>
                </div>
                <div class="block">
                    <div class="label">
                        MAC адрес
                    </div>
                    <div class="value">
                        00:FF:12:21:21:45
                    </div>
                </div>
                <div class="block">
                    <div class="label">
                        MAC адрес
                    </div>
                    <div class="value">
                        00:FF:12:21:21:45
                    </div>
                </div>
                <div class="block">
                    <div class="label">
                        MAC адрес
                    </div>
                    <div class="value">
                        00:FF:12:21:21:45
                    </div>
                </div>
                <div class="block">
                    <div class="label">
                        MAC адрес
                    </div>
                    <div class="value">
                        00:FF:12:21:21:45
                    </div>
                </div>
            </div>
            <div class="ticketInfoBox">
                <div class="date">
                    !!!!!!!!!!!!!
                </div>
                <div class="ticketInfo">
                    <div class="system">
                        <?=$data["text"]?> <?=$data["timeStamp"]?> 
                    </div>
                    <div class="comment">
                        <div class="author">
                            [<?=date("Y-m-d H:i:s", $data["timeStamp"])?>] <?=$data["author"]?> 
                        </div>
                        <div class="text">
                            <?=$data["text"]?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="commentBox">
                <input type="text" name="commentInput" id="commentInput" class="input">
                <div class="button" onclick="addCommentButtonClick()">
                    Отправить
                </div>
            </div>
            <div class="ticketFormButtonBox">
                <div class="buttonBlock">
                    <div class="button" onclick="toInstalled()">
                        Вернуть в "учет"
                    </div>
                    <div class="button" onclick="toServEng()">
                        Изъят
                    </div>
                    <div class="button" onclick="toLost">
                        Утерян
                    </div>
                    <div class="button" onclick="toWriteOff">
                        Списан
                    </div>
                </div>
                <div class="button" onclick="ticketFormClose()">
                    Закрыть
                </div>
            </div>
        </div>
        <script src="./script.js" type="text/javascript"></script>
    </body>
</html>