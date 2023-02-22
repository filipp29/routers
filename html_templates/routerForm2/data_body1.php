                <div class="supportHeader">
                    <div class="text">
                       Договор <?=$data['dnum']?>, <?=$data['address']?> 
                    </div>

                </div>
                <div class="supportBlock" style="margin-top: 30px">
                    <div class="image">
                        <img class="roundImg" src="<?=$data['operator_avatar']?>">
                    </div>
                    <div class="image" style="text-align: right; width: 30px;">
                        <img style="margin-top: 5px;" src="/_img/ltriang.png">
                    </div>
                    <div class="textBlock">
                        <div class="header">
                            <?=$data['operator']?> [<?=date('d.m.Y H:i:s',$data['inc_time'])?>]:
                        </div>
                        <div class="text">
                            <?=$data['text']?> 
                        </div>
                    </div>
                </div>

