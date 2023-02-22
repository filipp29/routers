                <div class="supportBlock" style="margin-top: 40px">
                    <div class="textBlock">
                        <div class="header">
                            <?=$data['executed']?> [<?=date('Y-m-d H:i:s',$data['end_time'])?>]:
                        </div>
                        <div class="text">
                            <?=$data['resolution']?> 
                        </div>
                    </div>
                    <div class="image" style="text-align: left; width: 30px;">
                        <img style="margin-top: 5px;" src="/_img/rtriang.png">
                    </div>
                    <div class="image">
                        <img class="roundImg" src="<?=$data['executed_avatar']?>">
                    </div>
                </div>