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