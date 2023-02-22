    <div style="width: calc(100% - 16px); border: 1px var(--modColor_light) solid; background-color: var(--modBGColor); border-radius: 5px; margin: auto; margin-top: 10px; padding: 5px;">
        <div style="font-weight: bold; font-size: 12px;">
            <span style="color: #aaa; font-weight: lighter;"><?=date("d.m.Y H:i:s", $data["timeStamp"])?></span> <?=$data["author"]?>
        </div>
        <div style="display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 58px; text-align: left; vertical-align: top;">
                    <img src="<?=$avatar?>" style="border: 1px var(--modColor_light) solid; border-radius: 24px; width: 48px; height: 48px;"/>
                </div>
                <div style="display: table-cell; padding-top: 5px;">
                    <?=$data["text"]?>
                </div>
            </div>
        </div>
    </div>