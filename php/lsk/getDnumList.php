<?php



$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //��������� �������� ����� (�����, ������ ���� �������� � ���������� ��������
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //���������� ���������� ��� ������ � ��
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/Decoder.php');



$br = array_keys(objLoadBranch("/users/", false, true));
$result = [];
$i = 0;
foreach($br as $dnum){
    $obj = objLoad("/users/{$dnum}/user.vcard");
    if (preg_match("/���������/", $obj["address"])){
        $result[] = "{$dnum}&0&0";
    }
}

objSave("/routers/lsk/dnumlist.info", "raw", $result);
echo "!!!!!!!!!!!";










