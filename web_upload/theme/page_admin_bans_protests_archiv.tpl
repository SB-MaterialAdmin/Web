{if NOT $permission_protests}
  Доступ запрещен!
{else}
<div class="card-header">
  <h2>Архив протестов банов ({$protest_count_archiv})<small>Кликните на имя игрока для просмотра подробностей бана</small></h2>
</div>
<div id="banlist-nav"> 
    {$aprotest_nav}
</div>
  <table class="table table-bordered">
    <tr>
            <th width="20%" class="text-center">Ник</th>
          <th width="20%" class="text-center">Steam ID</th>
            <th width="20%" class="text-center">Действие</th>
    </tr>
    {foreach from="$protest_list_archiv" item="protest"}
    <tr>
            <td class="text-center">{if $protest.archiv!=2}<a href="./index.php?p=banlist{if $protest.authid!=""}&advSearch={$protest.authid}&advType=steamid{else}&advSearch={$protest.ip}&advType=ip{/if}" title="Показать бан">{$protest.name}</a>{else}<i><font color="#677882">бан удалён</font></i>{/if}</td>
            <td class="text-center">{if $protest.authid!=""}<a href="https://steamcommunity.com/profiles/{$protest.commid}">{$protest.authid}</a>{else}{$protest.ip}{/if}</td>
            <td class="text-center">
        {if $permission_editban}
        <a href="#" onclick="RemoveProtest('{$protest.pid}', '{if $protest.authid!=""}{$protest.authid}{else}{$protest.ip}{/if}', '2');">Восстановить</a> -
            <a href="#" onclick="RemoveProtest('{$protest.pid}', '{if $protest.authid!=""}{$protest.authid}{else}{$protest.ip}{/if}', '0');">Удалить</a> -
            {/if}
            <a href="index.php?p=admin&c=bans&o=email&type=p&id={$protest.pid}">Контакты</a>
            </td>
    </tr>
    {/foreach}
  </table>
{/if}

