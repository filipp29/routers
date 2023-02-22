<tr class="tr <?=(($number % 2) ? "even" : "odd")?>" >
    <td class="td"><?=date("d.m.Y H:i:s",$timeStamp)?></td>
    <td class="td" onclick="getSimpleRouterForm('<?=$mac?>')"><?=$mac?></td>
    <td class="td"><?=$type?></td>
    <td class="td"><?=$text?></td>
</tr>