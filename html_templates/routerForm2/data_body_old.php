                        <div class="supportHeader">
                            ������ �� <?=date('d.m.Y H:i:s',$data['inc_time'])?> (������� <?=$data['dnum']?>, <?=$data['address']?>)
                        </div>
                        <div class="supportText">
                            <?=$data['text']?>
                        </div>
                        <div class="supportResolution">
                            ����������: <?=$data['resolution']?>
                        </div>
                        <div class="supportFooter">
                            <div class="elem">
                                ������(-�): <?=$data['operator']?>
                            </div>
                            <div class="elem">
                                ��������(-�): <?=$data['executed']?>
                            </div>
                        </div>