{if NOT $permission_list}
	Доступ запрещен
{else}
	
<div class="card">
	<div class="form-horizontal" role="form">
		<div class="card-header">
			<h2>Управление серверами ({$server_count}) <small>Список всех доступных серверов.</small></h2>
		</div>
		{if $permission_config}
			<div class="alert alert-info" role="alert">Чтобы просмотреть информацию для конфигурационного файла SourceBans кликните <b><a href="index.php?p=admin&c=servers&o=dbsetup" class="c-red">здесь</a></b>.</div>
		{/if}
		<div class="card-body table-responsive">

			<table id="data-table-command" class="table table-striped table-vmiddle bootgrid-table" aria-busy="false">
				<thead>
					<td class="front-module-header" width="3%" height='16'><strong>ID</strong></td>
					<td class="front-module-header" width="54%" height='16'><strong>Имя сервера</strong></td>
					<td class="front-module-header" width="6%" height='16'><strong>Игроки</strong></td>
					<td class="front-module-header" width="5%" height='16'><strong>МОД</strong></td>
					<td class="front-module-header" height='16'><strong>Действия</strong></td>
				</thead>
				{foreach from="$server_list" item="server"}
				
				<script>xajax_ServerHostPlayers({$server.sid});</script>
				<tr id="sid_{$server.sid}" {if $server.enabled==0}style="background-color:#eaeaea" title="Отключен"{/if}>
					<td style="border-bottom: solid 1px #ccc" height='16'>{$server.sid}</td>
					<td style="border-bottom: solid 1px #ccc" height='16' id="host_{$server.sid}"><i>Запрашиваем данные с сервера...</i></td>
					<td style="border-bottom: solid 1px #ccc" height='16' id="players_{$server.sid}">Н/Д</td>
					<td style="border-bottom: solid 1px #ccc" height='16'><img src="images/games/{$server.icon}"></td>
					<td style="border-bottom: solid 1px #ccc" height='16'>
					
					{if $server.rcon_access}
						<a href="index.php?p=admin&c=servers&o=rcon&id={$server.sid}">RCON</a> -
					{/if}
				
					{if $permission_editserver}
						<a href="index.php?p=admin&c=servers&o=edit&id={$server.sid}">Редактировать</a> -
					{/if}
					
					{if $pemission_delserver}
						<a href="#" onclick="RemoveServer({$server.sid}, '{$server.ip}:{$server.port}');">Удалить</a>
					{/if}
					</td>
				</tr>
				
				{/foreach}
			</table>
		
		</div>
		{if $permission_addserver}
			<div class="card-body card-padding">
				<button class="btn bgm-orange waves-effect save" onclick="childWindow=open('pages/admin.uploadmapimg.php','upload','resizable=yes,width=300,height=130');" id="upload">Загрузить изображение карты</button>
				<div id="mapimg1.msg" class="contacts c-profile clearfix p-t-20 p-l-0" style="display:none;">
					<div class="col-md-3 col-sm-4 col-xs-6 p-l-0 p-r-0">
						<div class="c-item">
							<div href="#" class="ci-avatar text-center f-20 p-t-10">
								<i class="zmdi zmdi-balance-wallet zmdi-hc-fw"></i>
							</div>
																
							<div class="c-info">
								<strong id="mapimg.msg"></strong>
							</div>
																
							<div class="c-footer c-green f-700 text-center p-t-5 p-b-5">
								Успешно загружено
							</div>
						</div>
					</div>
				</div>
			</div>
		{/if}
	</div>
</div>

{/if}