<?php
    $_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libProfiles.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libIntPhonebook.php');

    $dnum='3504009'; //� ���������� $dnum ����� �������� ����� ��������
    $addr='���������, ������, �. 53, ��. 161'; //� ���������� $addr ���������� ����� �������� (����� ��� ����������������)
?>
<!--<div style="background-color: #fff; border: 1px var(--modColor) dotted; padding: 5px; width: calc(100% - 10px); border-radius: 12px;">
    <div style="font-size: 24px; height: 60px; text-align: center; font-weight: bold; border-bottom: 1px var(--modColor_light) dashed;">
        �������� ���� ��������� (��������������� ����� � 2022-01-16) ��� �������� � kst_ber_53_4p:23
    </div>
    <div style="display: flex; font-size: 16px; margin-top: 10px; width: 100%; flex-wrap: wrap; justify-content: center; font-weight: bold;">
            <div style="white-space: nowrap; padding: 5px; padding-left: 30px;">
                <img src="/_modules/routers/icons/address.png" style="width: 16px; height: 16px;"/> �����: <span style="font-weight: lighter;">��������, ������, �. 53, ��. 161</span>
            </div>
            <div style="white-space: nowrap; padding: 5px; padding-left: 30px;">
                <img src="/_modules/routers/icons/mac.png" style="width: 16px; height: 16px;"/> MAC-�����: <span style="font-weight: lighter;">F8:F0:82:C3:B5:9E</span>
            </div>
            <div style="white-space: nowrap; padding: 5px; padding-left: 30px;">
                <img src="/_modules/routers/icons/dnum.png" style="width: 16px; height: 16px;"/> ����� ��������: <span style="font-weight: lighter;">135675</span>
            </div>
            <div style="white-space: nowrap; padding: 5px; padding-left: 30px;">
                <img src="/_modules/routers/icons/status.png" style="width: 16px; height: 16px;"/> ������: <span style="font-weight: lighter;">����������</span>
            </div>
            <div style="white-space: nowrap; padding: 5px; padding-left: 30px;">
                <img src="/_modules/routers/icons/debt.png" style="width: 16px; height: 16px;"/> ���� � ������: <span style="font-weight: lighter;">16</span>
            </div>
    </div>
    
</div>-->

<div style="display: table; width: calc(100% + 2px); margin-top: 10px;">
    <div style="display: table-row;">
        <div style="display: table-cell; height: 48px; vertical-align: middle;">
            <div style="display: flex; width: 100%;">
                <?php
                    $phones=ipbGetPhones($dnum);
                    foreach ($phones as $num=>$desc){
                        $num=ipbNormalizeNum($num, $addr);
                        if ($num!=''){
                            ?>
                <div class="rtrPhoneButton" onclick="rtrDialNum('<?=$num?>')">
                    <?=ipbReadableNum($num)?>
                    <div>
                        <?=$desc?>
                    </div>
                </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
        <div style="display: table-cell; width: 100px; vertical-align: middle;">
            <button style="margin-top: 0px;">� �������</button>
        </div>
    </div>
</div>

<div style="width: calc(100% - 2px); border: 2px var(--modColor) solid; background-color: #fff; display: table; margin-top: 10px;">
    <div style="display: table-row;">
        <div style="display: table-cell;">
            <input type="text" style="width: 100%; border: 0px; height: 100%;" placeholder="������� ����� �����������"/>
        </div>
        <div style="display: table-cell; width: 36px; text-align: right; vertical-align: middle; height: 36px;">
            <div style="background-color: var(--modColor); background-image: url(/_modules/routers/icons/sent.png); background-size: contain; width: 32px; height: 32px; border-radius: 16px;" class="hoverhgh" onclick="alert('����� �������� ��������. ����� � �� Enter � ��������� ���� ����������.');">
                
            </div>
        </div>
    </div>
</div>


<!--<div style="width: 100%; background-color: var(--modColor); color: #fff; text-align: center; margin-top: 5px; padding-top: 5px; padding-bottom: 2px; border: 1px var(--modColor) solid; font-weight: bold;">
    ����� �� 30.03.2022 19:54:22
</div>
<div style="width: 100%; overflow-y: scroll; height: 360px; border: 1px var(--modColor) solid; background-color: #fff;">-->
    
    <!-- ���� ������ ������ ������������ - ��� ����������. ����� ����� ��������� ������������������-->
    <div style="width: calc(100% - 16px); border: 1px var(--modColor_light) solid; background-color: var(--modBGColor); border-radius: 5px; margin: auto; margin-top: 10px; padding: 5px;">
        <div style="font-weight: bold; font-size: 12px;">
            <span style="color: #aaa; font-weight: lighter;">03.04.2022 10:45</span> ���� �����
        </div>
        <div style="display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 58px; text-align: left; vertical-align: top;">
                    <img src="<?=profileGetAvatar('izus')?>" style="border: 1px var(--modColor_light) solid; border-radius: 24px; width: 48px; height: 48px;"/>
                </div>
                <div style="display: table-cell; padding-top: 5px;">
                    � �����, ����������, ����� �����������, ������� �������� ������������. �������� �� ��������: ����� ������, ������ �����. � ���� �� �������, ��� ���������� ��������, ��������� ����� div � �������� ������������, � �� ���� ����� :)
                </div>
            </div>
        </div>
    </div>
    <!-- ����� ����� - ���� �����-->
    <div style="width: calc(100% - 16px); border: 1px var(--modColor_light) solid; background-color: var(--modBGColor); border-radius: 5px; margin: auto; margin-top: 10px; padding: 5px;">
        <div style="font-weight: bold; font-size: 12px;">
            <span style="color: #aaa; font-weight: lighter;">03.04.2022 10:45</span> ���� �����
        </div>
        <div style="display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 58px; text-align: left; vertical-align: top;">
                    <img src="<?=profileGetAvatar('izus')?>" style="border: 1px var(--modColor) solid; border-radius: 24px; width: 48px; height: 48px;"/>
                </div>
                <div style="display: table-cell; padding-top: 5px;">
                    � �����, ����������, ����� �����������, ������� �������� ������������. �������� �� ��������: ����� ������, ������ �����. � ���� �� �������, ��� ���������� ��������, ��������� ����� div � �������� ������������, � �� ���� ����� :)
                </div>
            </div>
        </div>
    </div>
    
    <div style="width: calc(100% - 16px); border: 1px var(--modColor_light) solid; background-color: var(--modBGColor); border-radius: 5px; margin: auto; margin-top: 10px; padding: 5px;">
        <div style="font-weight: bold; font-size: 12px;">
            <span style="color: #aaa; font-weight: lighter;">03.04.2022 10:45</span> ���� �����
        </div>
        <div style="display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 58px; text-align: left; vertical-align: top;">
                    <img src="<?=profileGetAvatar('izus')?>" style="border: 1px var(--modColor) solid; border-radius: 24px; width: 48px; height: 48px;"/>
                </div>
                <div style="display: table-cell; padding-top: 5px;">
                    � �����, ����������, ����� �����������, ������� �������� ������������. �������� �� ��������: ����� ������, ������ �����. � ���� �� �������, ��� ���������� ��������, ��������� ����� div � �������� ������������, � �� ���� ����� :)
                </div>
            </div>
        </div>
    </div>
    
    <div style="width: calc(100% - 16px); border: 1px var(--modColor_light) solid; background-color: var(--modBGColor); border-radius: 5px; margin: auto; margin-top: 10px; padding: 5px;">
        <div style="font-weight: bold; font-size: 12px;">
            <span style="color: #aaa; font-weight: lighter;">03.04.2022 10:45</span> ���� �����
        </div>
        <div style="display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 58px; text-align: left; vertical-align: top;">
                    <img src="<?=profileGetAvatar('izus')?>" style="border: 1px var(--modColor) solid; border-radius: 24px; width: 48px; height: 48px;"/>
                </div>
                <div style="display: table-cell; padding-top: 5px;">
                    � �����, ����������, ����� �����������, ������� �������� ������������. �������� �� ��������: ����� ������, ������ �����. � ���� �� �������, ��� ���������� ��������, ��������� ����� div � �������� ������������, � �� ���� ����� :)
                </div>
            </div>
        </div>
    </div>
    
    <div style="width: calc(100% - 16px); border: 1px var(--modColor_light) solid; background-color: var(--modBGColor); border-radius: 5px; margin: auto; margin-top: 10px; padding: 5px;">
        <div style="font-weight: bold; font-size: 12px;">
            <span style="color: #aaa; font-weight: lighter;">03.04.2022 10:45</span> ���� �����
        </div>
        <div style="display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 58px; text-align: left; vertical-align: top;">
                    <img src="<?=profileGetAvatar('izus')?>" style="border: 1px var(--modColor) solid; border-radius: 24px; width: 48px; height: 48px;"/>
                </div>
                <div style="display: table-cell; padding-top: 5px;">
                    � �����, ����������, ����� �����������, ������� �������� ������������. �������� �� ��������: ����� ������, ������ �����. � ���� �� �������, ��� ���������� ��������, ��������� ����� div � �������� ������������, � �� ���� ����� :)
                </div>
            </div>
        </div>
    </div>
    
    <div style="width: calc(100% - 16px); border: 1px var(--modColor_light) solid; background-color: var(--modBGColor); border-radius: 5px; margin: auto; margin-top: 10px; padding: 5px;">
        <div style="font-weight: bold; font-size: 12px;">
            <span style="color: #aaa; font-weight: lighter;">03.04.2022 10:45</span> ���� �����
        </div>
        <div style="display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 58px; text-align: left; vertical-align: top;">
                    <img src="<?=profileGetAvatar('izus')?>" style="border: 1px var(--modColor) solid; border-radius: 24px; width: 48px; height: 48px;"/>
                </div>
                <div style="display: table-cell; padding-top: 5px;">
                    � �����, ����������, ����� �����������, ������� �������� ������������. �������� �� ��������: ����� ������, ������ �����. � ���� �� �������, ��� ���������� ��������, ��������� ����� div � �������� ������������, � �� ���� ����� :)
                </div>
            </div>
        </div>
    </div>
    
    <div style="width: calc(100% - 16px); border: 1px var(--modColor_light) solid; background-color: var(--modBGColor); border-radius: 5px; margin: auto; margin-top: 10px; padding: 5px;">
        <div style="font-weight: bold; font-size: 12px;">
            <span style="color: #aaa; font-weight: lighter;">03.04.2022 10:45</span> ���� �����
        </div>
        <div style="display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 58px; text-align: left; vertical-align: top;">
                    <img src="<?=profileGetAvatar('izus')?>" style="border: 1px var(--modColor) solid; border-radius: 24px; width: 48px; height: 48px;"/>
                </div>
                <div style="display: table-cell; padding-top: 5px;">
                    � �����, ����������, ����� �����������, ������� �������� ������������. �������� �� ��������: ����� ������, ������ �����. � ���� �� �������, ��� ���������� ��������, ��������� ����� div � �������� ������������, � �� ���� ����� :)
                </div>
            </div>
        </div>
    </div>
    
    <div style="width: calc(100% - 16px); border: 1px var(--modColor_light) solid; background-color: var(--modBGColor); border-radius: 5px; margin: auto; margin-top: 10px; padding: 5px;">
        <div style="font-weight: bold; font-size: 12px;">
            <span style="color: #aaa; font-weight: lighter;">03.04.2022 10:45</span> ���� �����
        </div>
        <div style="display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 58px; text-align: left; vertical-align: top;">
                    <img src="<?=profileGetAvatar('izus')?>" style="border: 1px var(--modColor) solid; border-radius: 24px; width: 48px; height: 48px;"/>
                </div>
                <div style="display: table-cell; padding-top: 5px;">
                    � �����, ����������, ����� �����������, ������� �������� ������������. �������� �� ��������: ����� ������, ������ �����. � ���� �� �������, ��� ���������� ��������, ��������� ����� div � �������� ������������, � �� ���� ����� :)
                </div>
            </div>
        </div>
    </div>
    
    <!-- � ��� ������ ��������� ��������� (��������, ��� �������� ������� � ��.)-->
    
    <div style="width: calc(100% - 100px); margin: auto; margin-top: 10px; background-color: var(--modColor_light); border-radius: 5px; text-align: center; color: #fff; font-weight: bold; padding: 5px;">
        ������������� ������ ����� ���� ������. 30.03.2022 19:54:24 
    </div>
    
    <div style="width: calc(100% - 100px); margin: auto; margin-top: 10px; background-color: var(--modColor_light); border-radius: 5px; text-align: center; color: #fff; font-weight: bold; padding: 5px;">
        Ticket opened 30.03.2022 19:54:22 
    </div>
    
    
</div>
