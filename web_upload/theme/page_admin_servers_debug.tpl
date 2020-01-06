{if NOT $permission_list}
Доступ запрещен
{else}

<div class="card">
	<div class="form-horizontal" role="form">
		<div class="card-header">
			<h2>Отладка серверов</h2>
		</div>

		<div class="card-body card-padding p-b-0" id="">
			<select class="form-control" id="server" onmouseup="$('where_banned').checked = true">
				<option label="Web Бан" value="0">Web Бан</option>
				{foreach from="$server_list" item="server}
				<option value="{$server.sid}" id="ss{$server.sid}"> Получение адреса... ({$server.ip}:{$server.port})
				</option>
				{/foreach}
			</select>
		</div>

		<div class="card-body card-padding text-center">
			<button type="button" class="btn btn-success">Начать Отладку</button>
			<button type="button" class="btn btn-danger">Назад</button>
		</div>

	</div>
</div>

{/if}