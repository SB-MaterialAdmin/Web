{foreach from="$games" item="game"}
{if $game.servers > 0}
{assign var="currgame" value=$game.mid}
<div class="card">
	<div class="card-header">
			<h2>
				<img class="gameicon" src="images/games/{$game.icon}" style="width: 20px;height: 20px;" /> 
                {$game.name}
				<small>Полный список администраторов на доступных игровых серверах с подробной информацией.</small>
			</h2>
	</div>
	<div class="card-body card-padding">	
		<table id="data-table-command" class="table table-striped table-vmiddle">
			<thead>
				{foreach from="$server_list" item="server"}
                    {if $server.modid == $currgame}
                    {if $server.admincount > 0}
                    {assign var="adminlist" value=$server.adminlist}
					<tr class="opener1" style="cursor:pointer;" name="servers[]" id="s_{$server.sid}" value="s{$server.sid}">
						<th width="10%">
							<img id="mapimg_{$server.sid}" class="img-responsive img-thumbnail" style="width:60px; height:60px" src='images/maps/nomap.jpg'>
						</th>
						<th width="100%" class="f-14" id="sa{$server.sid}"><span id="host_{$server.sid}">Получение имени сервера... {$server.ip}:{$server.port}</span></th>
						<th width="10%" style="font-size: 28px;"><i class="zmdi zmdi-accounts-list"></i></th>
					</tr>
					<tr>
						<td colspan="3" align="center" style="background-color: #f4f4f4;padding: 0px;border-top: 0px solid #FFFFFF;">
							<div class="opener" style="visibility: hidden; zoom: 1; opacity: 0;">
								<table id="data-table-command" class="table table-striped table-vmiddle" style="width:87%" align="center">
									<thead>
										<tr>
											<th class="bgm-bluegray c-white text-center" width="20%">Ник</th>
											<th class="bgm-bluegray c-white text-center" width="25%">Группа</th>
											<th class="bgm-bluegray c-white text-center" width="12%">Skype</th>
											<th class="bgm-bluegray c-white text-center" width="12%">VK</th>
											<th class="bgm-bluegray c-white text-center">Должность</th>
										</tr>
									</thead>
									<tbody id="adminlist">
                                        {foreach from="$adminlist" item="admin"}
										<tr>
											<td><img src="{$admin.avatar}" style="width: 20px; height: 20px; border-radius: 25px;" /> <a href="https://steamcommunity.com/profiles/{$admin.authid}">{$admin.user}</a></td>
											<td>{if $admin.srv_group != ""}{$admin.srv_group}{else}Нет группы\Индивид. права{/if}</td>
											<td>{if $admin.skype != ""}<a href="skype:{$admin.skype|escape}?userinfo" title="Просмотреть информацию о профиле Skype">{$admin.skype|escape}</a>{else}Неизвестно{/if}</td>
											<td>{if $admin.vk != ""}<a href="https://vk.com/{$admin.vk|escape}" title="Перейти в профиль ВК">{$admin.vk|escape}</a>{else}Неизвестно{/if}</td>
											<td>{if $admin.comment != ""}{$admin.comment}{else}Нет данных. Обычный рядовой, контролирует порядок на серверах.{/if}</td>
										</tr>
										{/foreach}
									</tbody>
								</table>
							</div>
						</td>
					</tr>
					<script>xajax_ServerHostPlayers({$server.sid}, 'servers', '', '0', '-1', '{$IN_HOME}', 70);</script>
                    {/if}
                    {/if}
				{/foreach}
			</thead>
		</table>
	</div>
</div>
{/if}
{/foreach}

{$server_script}

<script type="text/javascript">
	InitAccordion('tr.opener1', 'div.opener', 'content');
</script>
