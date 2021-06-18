<form action="" method="post">
	<div class="card" id="admin-page-content">
		<div class="form-horizontal" role="form" id="add-group">
			<div class="card-header">
				<h2>Доступ к серверу(ам) <small>Выберите сервер или группу серверов, которые он будет администрировать..</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-0">
					<label class="col-sm-3 control-label">Доступные сервера</label>
					<div class="col-sm-9">
						<div class="checkbox">
							<table width="100%" valign="left" id="group.details">
								{if $row_count < 1}
										<tr>
											<td><b><i>Вам нужно добавить сервер или группу серверов, прежде, чем вы сможете настроить разрешения админов сервера</i></b></td>
										</tr>
								{else}
									{foreach from="$group_list" item="group"}
										<tr>
											<td>
												<div class="checkbox m-b-15">
													<label for="group_{$group.gid}">
														<input type="checkbox" id="group_{$group.gid}" name="group[]" value="g{$group.gid}" hidden="hidden" />
														<i class="input-helper"></i> {$group.name}<b><i> (Группа сервера)</i></b></span>
													</label>
												</div>
											</td>
										</tr>
									{/foreach}
									{foreach from="$server_list" item="server"}
										<tr>
											<td>
												<div class="checkbox m-b-15">
													<label for="server_{$server.sid}">
														<input type="checkbox" name="servers[]" id="server_{$server.sid}" value="s{$server.sid}" hidden="hidden" />
														<i class="input-helper"></i> <span id="server_host_{$server.sid}"><i>Получение имени сервера...</i></span>
													</label>
												</div>
											</td>
										</tr>
									{/foreach}
								{/if}
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body card-padding text-center">
						{if $row_count > 0}
							{sb_button text="Сохранить" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" id="editadminserver" submit=true}
							&nbsp;
						{/if}
		      			{sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
			</div>
			<script>
			{foreach from="$assigned_servers" item="asrv"}
				if($('server_{$asrv.0}'))$('server_{$asrv.0}').checked = true;
				if($('group_{$asrv[1]}'))$('group_{$asrv[1]}').checked = true;
			{/foreach}
			{foreach from="$server_list" item="server"}
				xajax_ServerHostPlayers({$server.sid}, "id", "server_host_{$server.sid}");
			{/foreach}
			</script>
		</div>
	</div>
</form>
