<?php

$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';

function getMacByLogin(
        $login
){
    return \RouterBase::getMacByLogin($login);
}