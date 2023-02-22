                        <div class="supportHeader">
                            Заявка от <?=date('d.m.Y H:i:s',$data['inc_time'])?> (Договор <?=$data['dnum']?>, <?=$data['address']?>)
                        </div>
                        <div class="supportText">
                            <?=$data['text']?>
                        </div>
                        <div class="supportResolution">
                            Заключение: <?=$data['resolution']?>
                        </div>
                        <div class="supportFooter">
                            <div class="elem">
                                Принял(-а): <?=$data['operator']?>
                            </div>
                            <div class="elem">
                                Выполнил(-а): <?=$data['executed']?>
                            </div>
                        </div>