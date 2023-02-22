<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/html.html to edit this template
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="windows-1251">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <form id="form" method="GET" action="./auth_all_check.php">
            <input id="mac" type="radio" name="type" value="mac" checked>
            <label for="mac"> MAC </label>
            <input id="dnum" type="radio" name="type" value="dnum">
            <label for="dnum"> Номер договора </label>
            <br>
            <input type="text" name="value">
            
            <br>
            <input type="checkbox" name="showType" id="showType">
            <label for="showType">Показать типы авторизации</label>
            <br>
            <input type="checkbox" name="showMac" id="showMac" checked>
            <label for="showMac">Показать только время</label>
            <br>
            <br>
            <input id="submit" type="submit" name="submit" value="Отправить" >
        </form>
        <script>
        
            
        </script>
    </body>
</html>





