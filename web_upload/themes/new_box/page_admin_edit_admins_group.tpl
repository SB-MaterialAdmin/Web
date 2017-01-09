<form action="" method="post">
<div class="form-horizontal" role="form" id="add-group">
			<div class="card-header">
				<h2>Разрешения админа <small>Выборочные права: Выберите эту опцию, чтобы выдать админу определённые права. <br>Новая группа: Выберите эту опцию для выбора определенных прав и сохранения их в новую группу. <br>Группы: Выберите готовые группы.</small></h2>
			</div>
			<div class="card-body card-padding p-b-0" id="group.details">
				<div class="form-group m-b-0">
					<label class="col-sm-3 control-label">Группы</label>
					<div class="col-sm-9">
						<table width="90%" style="border-collapse:collapse;" id="group.details">
							<tr>
								<td>
									<div class="col-xs-6 p-b-10" id="admingroup">
										<select class="selectpicker" TABINDEX=11 onchange="update_server()" name="sg" id="sg">
											<option value="-1">Нет групп</option>
		        					<optgroup label="Группы" style="font-weight:bold;">
												{foreach from=$group_lst item=sg}
												<option value="{$sg.id}"{if $sg.id == $server_admin_group_id} selected="selected"{/if}>{$sg.name}</option>
												{/foreach}
											</optgroup>
										</select>
									</div>
									<div id="sgroup.msg" class="badentry"></div>
								</td>
							</tr>
							<tr>
								<td colspan="2" id="serverperm" valign="top" style="display: none;"></td>
							</tr>
							<tr>
								<td>
									<div class="col-xs-6 p-b-10" id="webgroup">
										<select TABINDEX=9 onchange="update_web()" name="wg" id="wg" class="selectpicker">
											<option value="-1">Нет групп</option>
				        			<optgroup label="Группы" style="font-weight:bold;">
											{foreach from=$web_lst item=wg}
											<option value="{$wg.gid}"{if $wg.gid == $group_admin_id} selected="selected"{/if}>{$wg.name}</option>
											{/foreach}
										</optgroup>
										</select>
									</div>
									<div id="wgroup.msg" class="badentry"></div>
								</td>
							</tr>
							<tr>
								<td colspan="2" id="webperm" valign="top" style="display: none;"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="card-body card-padding text-center">
				{sb_button text="Сохранить изменения" icon="<i class='zmdi zmdi-account-add'></i>" class="bgm-green btn-icon-text" id="agroups" submit=true}
				&nbsp;
				{sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
			</div>
        {$server_script}
		</div>
	</div>		
</form>
