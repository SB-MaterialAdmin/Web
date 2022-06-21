{if NOT $permission_addban}
	Доступ запрещен!
{else}
	{if NOT $groupbanning_enabled}
		Функция отключена!
	{else}
<div class="card">
	<div class="form-horizontal" role="form" id="group.details">
		{if NOT $list_steam_groups}
		<div class="card-header">
		<h2>Добавить бан Группы
		<small>Здесь вы можете добавить запрет на группу сообщества Steam. Например <code>http://steamcommunity.com/groups/interwavestudios</code></small></h2>
		</div>
		<div class="card-body card-padding p-b-0" id="group.details">
			<div class="form-group m-b-5">
				<label for="groupurl" class="col-sm-3 control-label">{help_icon title="Ссылка на группу" message="Введите ссылку на группу сообщества Steam."}Ссылка на группу</label>
				<div class="col-sm-9">
					<div class="fg-line">
						<input type="text" TABINDEX=1 class="form-control" id="groupurl" name="groupurl" placeholder="Введите данные">
					</div>
				<div id="groupurl.msg"></div>
				</div>
			</div>
			<div class="form-group m-b-5">
				<label for="groupreason" class="col-sm-3 control-label">{help_icon title="Причина бана группы" message="Введите причину, по которой Вы собираетесь забанить группу."}Причина бана группы</label>
					<div class="col-sm-9 p-t-10">
						<div class="fg-line">
							<textarea name="groupreason" id="groupreason" class="form-control p-t-5" placeholder="Будьте максимально информативны"></textarea>
						</div>
					</div>
				<div id="groupreason.msg"></div>
			</div>
		<div class="card-body card-padding text-center">
				{sb_button text="Забанить группу" onclick="ProcessGroupBan();" icon="<i class='zmdi zmdi-shield-security'></i>" class="bgm-green btn-icon-text" id="agban" submit=false}
					  &nbsp;
				{sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
		</div>
		</div>
		{else}
		<div class="card-header">
		<h2>Группы игрока {$player_name}
		<small>Выберите группу сообщества Steam, которую желаете забанить.</small></h2>
		</div>
		<div class="alert alert-success" role="alert" id="steamGroupStatus" name="steamGroupStatus" style="display:none;"></div>
		<div class="card-body card-padding p-b-0" id="group.details">
		
			<div class="form-group m-b-5" id="steamGroups" name="steamGroups" >
				<label class="col-sm-3 control-label">Группы <mark style="cursor:pointer;" id="tickswitch" name="tickswitch" onclick="TickSelectAll();"> (пометить) </mark></label>
					<div class="col-sm-9 p-t-10">
						<div id="steamGroupsText" name="steamGroupsText"> Загрузка групп...</div>
						<table id="steamGroupsTable" name="steamGroupsTable" border="0" width="500px">
						</table>
					</div>
			</div>
		
			<div class="form-group m-b-5">
				<label for="groupreason" class="col-sm-3 control-label">{help_icon title="Причина бана группы" message="Введите причину бана, по которой желаете забанить группу."}Причина бана группы </label>
				<div class="col-sm-9 p-t-10">
					<div class="fg-line">
						<textarea name="groupreason" id="groupreason" class="form-control p-t-5" placeholder="Будьте максимально информативны"></textarea>
					</div>
					<div id="groupreason.msg" class="badentry"></div>
				</div>
			</div>
		<script type="text/javascript">$('tickswitch').value = 0;xajax_GetGroups('{$list_steam_groups}');</script>
		</div>
		<div class="card-body card-padding text-center">
			{sb_button text="Забанить группу" onclick="CheckGroupBan();" icon="<i class='zmdi zmdi-shield-security'></i>" class="bgm-green btn-icon-text" id="gban" submit=false}
			&nbsp;
			{sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
		</div>
		{/if}
	</div>
</div>
	{/if}
{/if}
