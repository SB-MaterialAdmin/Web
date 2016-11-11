{if NOT $permission_listmods}
	Доступ запрещен!
{else}
	{display_header title="Список МОДов ($mod_count)" text="Список всех МОДов, добавленных в SourceBans"}
	<div class="card-body card-padding">
		<table class="table table-striped">
			<tbody>
				<tr>
					<th width="35%" class="text-left">Имя</th>
					<th width="20%" class="text-center">Папка</th>
					<th width="5%"  class="text-center">Иконка</th>
					<th width="2%"  class="text-center">Универсальный SteamID</th>{if $permission_editmods || $permission_deletemods}
					<th class="text-right">Действия</th>{/if}
				</tr>
				{foreach from="$mod_list" item="mod" name="gaben"}
				<tr id="mid_{$mod.mid}">
				<td class="text-left">{$mod.name|htmlspecialchars}</td>
				<td class="text-center">{$mod.modfolder|htmlspecialchars}</td>
				<td class="text-center"><img src="images/games/{$mod.icon}" width="16"></td>
				<td class="text-center">{$mod.steam_universe|htmlspecialchars}</td>{if $permission_editmods || $permission_deletemods}
				<td class="text-right">
					{if $permission_editmods}
					<a href="index.php?p=admin&c=mods&o=edit&id={$mod.mid}">Редактировать</a> / 
					{/if}
					{if $permission_deletemods}
					<a href="#" onclick="RemoveMod('{$mod.name|escape:'quotes'|htmlspecialchars}', '{$mod.mid}');">Удалить</a>
					{/if}
				</td>
				{/if}
			</tr>
		{/foreach}
	</table>
	</div>
{/if}
