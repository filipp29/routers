<!DOCTYPE html>

Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/html.html to edit this template

<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="windows-1251">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        
        <div  class="block hidden">
            <div  class="key">
                MAC �����
            </div>
            <input type="text" name="value1" id="mac" class="value">
        </div>
        <br>
        <input type="button" name="check" onclick="checkButtonClick()" value="��������� MAC" >
        <br>
        <div  class="block hidden">
            <div  class="key">
                ���
            </div>
            <input type="text" name="value1" id="type" class="value">
        </div>
        <br>
        <div  class="block hidden">
            <div  class="key">
                �����
            </div>
            <input type="text" name="value1" id="author" class="value">
        </div>
        <br>
        <div  class="block hidden">
            <div  class="key">
                �����������
            </div>
            <textarea id="text" name="name" rows="5" cols="10"></textarea>
        </div>
        <br>
        <input type="button" name="submit" onclick="submitButtonClick()" value="�������� �����������">
        <div id="noAuthor" hidden="hidden">
            ����� �� ������
        </div>
        <div id="noComment" hidden="hidden">
            ����������� �� ������
        </div>
    </body>
</html>
