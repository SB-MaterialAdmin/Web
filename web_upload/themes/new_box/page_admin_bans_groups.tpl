{if NOT $permission_addban}
	Доступ запрещен!
{else}
	{if NOT $groupbanning_enabled}
		Функция отключена!
	{else}
<div class="card">
	<div class="form-horizontal" role="form" id="add-group1">
		<div class="card-header">
		<h2>Добавить бан Группы
		{if NOT $list_steam_groups}
		<small>Здесь вы можете добавить запрет на группу сообщества Steam. Например <code>http://steamcommunity.com/groups/interwavestudios</code></small></h2>
		</div>
		<div class="card-body card-padding p-b-0" id="group.details">
			<div class="form-group m-b-5">
				<label for="nickname" class="col-sm-3 control-label">{help_icon title="Ссылка на группу" message="Введите ссылку на группу сообщества Steam."}Ссылка на группу</label>
				<div class="col-sm-9">
					<div class="fg-line">
						<input type="hidden" id="fromsub" value="" />
						<input type="text" TABINDEX=1 class="form-control" id="nickname" name="nickname" placeholder="Введите данные">
					</div>
				<div id="nick.msg"></div>
				</div>
			</div>
			<div class="form-group m-b-5">
				<label for="BanReason" class="col-sm-3 control-label">{help_icon title="Причина бана группы" message="Введите причину, по которой Вы собираетесь забанить группу."}Причина бана группы</label>
					<div class="col-sm-9 p-t-10">
						<div class="fg-line">
							<textarea name="BanReason" cols="30" rows="5" class="form-control p-t-5" placeholder="Будьте максимально информативны" >{$reason}</textarea>
						</div>
					</div>
			</div>
		<div class="card-body card-padding text-center">
				{sb_button text="Забанить группу" onclick="ProcessGroupBan();" icon="<i class='zmdi zmdi-shield-security'></i>" class="bgm-green btn-icon-text" id="agban" submit=false}
					  &nbsp;
				{sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
		</div>
		{else}
		Группы игрока {$player_name}.<br />
		Выберите группу сообщества Steam, которую желаете забанить.<br /><br />
		<div id="steamGroupsText" name="steamGroupsText">Загрузка групп...</div>
		<div id="steamGroups" name="steamGroups" style="display:none;">
			<table id="steamGroupsTable" name="steamGroupsTable" border="0" width="500px">
			<tr>
				<td height="16" class="listtable_1" style="padding:0px;width:3px;" align="center"><div class="ok" style="height:16px;width:16px;cursor:pointer;" id="tickswitch" name="tickswitch" onclick="TickSelectAll();"></div></td>
				<td height="16" class="listtable_top" align="center"><b>Группа</b></td>
			</tr>
			</table>
			&nbsp;&nbsp;L&nbsp;&nbsp;<a href="#" onclick="TickSelectAll();return false;" title="Выбрать все" name="tickswitchlink" id="tickswitchlink">Выбрать все</a><br /><br />
			<table width="90%" style="border-collapse:collapse;" id="group.details" cellpadding="3">
				<tr>
					<td valign="top" width="35%">
						<div class="rowdesc">
							{help_icon title="Причина бана группы" message="Введите причину бана, по которой желаете забанить группу."}Причина бана группы 
						</div>
					</td>
					<td>
						<div align="left">
							<textarea class="submit-fields" TABINDEX=2 cols="30" rows="5" id="groupreason" name="groupreason" /></textarea>
						</div>
						<div id="groupreason.msg" class="badentry"></div>
					</td>
				</tr>
			</table>
			<input type="button" class="btn ok" onclick="CheckGroupBan();" name="gban" id="gban" onmouseover="ButtonOver('gban');" onmouseout="ButtonOver('gban');" value="Забанить Группу">
		</div>
		<div id="steamGroupStatus" name="steamGroupStatus" width="100%"></div>
		<script type="text/javascript">$('tickswitch').value = 0;xajax_GetGroups('{$list_steam_groups}');</script>
		{/if}
		</div>
	</div>
</div>
	{/if}
{/if}