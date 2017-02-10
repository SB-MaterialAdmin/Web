<form action="" method="post">
	<div class="form-horizontal" role="form">
		{display_header title="Детали МОДа" text="За дополнительной информацией или помощью наведите курсор мыши на знак вопроса."}
		<div class="card-body card-padding clearfix p-b-0">
			{display_material_input name="name" help_title="Название мода" help_text="Введите имя для МОДа." placeholder="Counter-Strike: Source" value=$name}
			<p id="name.msg" style="color:#CC0000;"></p>

			{display_material_input name="folder" help_title="Имя папки" help_text="Введите имя папки МОДа. Например, для Counter-Strike: Source папка будет 'cstrike'" placeholder="cstrike" value=$folder}
			<p id="folder.msg" style="color:#CC0000;"></p>

			{display_material_input name="steam_universe" help_title="Универсальный номер Steam" help_text="(STEAM_<b>X</b>:Y:Z) Некоторые игры отображают steamid отличающийся от других. Введите первую цифру в SteamID (<b>X</b>) в зависимости от вашего мода. (По умолчанию: 0)." placeholder="0" value=$steam_universe}
			{display_material_checkbox name="enabled" help_title="Активация МОДа" help_text="Выберите, чтобы включить этот МОД"}

			<div class="form-group m-b-5">
				<label for="icon" class="col-sm-3 control-label">{help_icon title="Upload Icon" message="Загрузить иконку" message="Кликните тут, чтобы загрузить иконку МОДа."}Загрузить иконку</label>
				<div class="col-sm-9">
					{sb_button text="Загрузить иконку МОДа" onclick="childWindow=open('pages/admin.uploadicon.php','upload','resizable=yes,width=300,height=130');" class="save" id="upload"}
				</div>
				<p id="icon.msg" style="color:#C00;">{if $mod_icon}Загружено: <b>{$mod_icon}</b>{/if}</p>
				<input type="hidden" id="icon_hid" name="icon_hid" />
			</div>

			<p class="text-center">{sb_button text="Сохранить МОД" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" submit=true id="amod"}&nbsp;
			{sb_button text="Назад" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" onclick="history.go(-1)" id="aback"}</p>
		</div>
	</div>
</form>
