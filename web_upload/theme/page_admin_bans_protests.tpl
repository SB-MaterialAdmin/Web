{if NOT $permission_protests}
	Доступ запрещен!
{else}
<div class="card-header">
	<h2>Протесты банов ({$protest_count})<small>Кликните на имя игрока для просмотра подробностей бана</small></h2>
</div>
<div id="banlist-nav"> 
    {$protest_nav}
</div>
	<table class="table table-bordered">
		<tr>
        	<th width="20%" class="text-center">Ник</th>
      		<th width="20%" class="text-center">Steam ID</th>
           	<th width="20%" class="text-center">Действие</th>
		</tr>
		{foreach from="$protest_list" item="protest"}
		<tr>
            <td class="text-center"><a href="./index.php?p=banlist&advSearch={$protest.authid}&advType=steamid" title="Показать бан">{$protest.name}</a></td>
            <td class="text-center">{if $protest.authid!=""}<a href="https://steamcommunity.com/profiles/{$protest.commid}">{$protest.authid}</a>{else}{$protest.ip}{/if}</td>
            <td class="text-center">
            {if $permission_editban}
            <a href="#" onclick="RemoveProtest('{$protest.pid}', '{if $protest.authid!=""}{$protest.authid}{else}{$protest.ip}{/if}', '1');">Удалить</a> -
            {/if}
            <a href="index.php?p=admin&c=bans&o=email&type=p&id={$protest.pid}">Контакты</a>
            </td>
		</tr>
		{/foreach}
	</table>
{/if}

