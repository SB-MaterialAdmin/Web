<form action="" method="post">
    <input type="hidden" name="settingsGroup" value="features" />
    <div class="card" id="group.features">
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Опции<small>За дополнительной информацией или помощью наведите курсор мыши на знак вопроса.</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
			
				<div class="form-group m-b-5">
					<label for="export_public" class="col-sm-3 control-label">{help_icon title="Включить публичный список банов" message="Установите этот флажок, чтобы все могли скачать список банов."} Разрешить экспорт банов</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="export_public">
								<input type="checkbox" name="export_public" id="export_public" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_kickit" class="col-sm-3 control-label">{help_icon title="Включить кик" message="Установите этот флажок, чтобы кикнуть игрока, когда бан добавлен в базу."} Включить кик</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_kickit">
								<input type="checkbox" name="enable_kickit" id="enable_kickit" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_groupbanning" class="col-sm-3 control-label">{help_icon title="Включить групповые баны" message="Установите этот флажок, если вы хотите включить бан групп."} Включить групповые баны</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_groupbanning">
								<input type="checkbox" name="enable_groupbanning" id="enable_groupbanning" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
							<div id="enable_groupbanning.msg"></div>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_friendsbanning" class="col-sm-3 control-label">{help_icon title="Включить баны друзей" message="Установите этот флажок, если вы хотите включить бан всех друзей игрока."} Включить баны друзей</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_friendsbanning">
								<input type="checkbox" name="enable_friendsbanning" id="enable_friendsbanning" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
							<div id="enable_friendsbanning.msg"></div>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_adminrehashing" class="col-sm-3 control-label">{help_icon title="Включить перезагрузку списка администраторов" message="Установите этот флажок, если вы хотите, чтобы права администраторов перезагружались при любом изменении админов.групп."} Включить перезагрузку списка администраторов</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_adminrehashing">
								<input type="checkbox" name="enable_adminrehashing" id="enable_adminrehashing" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
							<div id="enable_adminrehashing.msg"></div>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_admininfo" class="col-sm-3 control-label">{help_icon title="Информация об Администраторе" message="Показывает информацию(скайп, вк, STEAMID) о забанившем игрока Администраторе в банлисте или мут/гаг листе."} Информация об администраторе</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_admininfo">
								<input type="checkbox" name="enable_admininfo" id="enable_admininfo" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="allow_admininfo" class="col-sm-3 control-label">{help_icon title="Смена информации админом" message="Разрешить пользователю самому менять VK или Skype в своем профиле?"} Смена своей информации Админом</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="allow_admininfo">
								<input type="checkbox" name="allow_admininfo" id="allow_admininfo" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				
				<div class="form-group m-b-5">
					<label for="moder_group_st" class="col-sm-3 control-label">{help_icon title="Модерирование группы" message="Система которая позволяет администратору редактировать вообще любые баны(при условии, что ему разрешено редактировать все баны), но только на том сервере, где у этого права администратора. Полезно будет тем, у кого есть такие должности как 'управляющий сервером', чтобы администраторы в этой группе могли редактировать только баны на тех серверах, где они управляют."} Модерировать группу</label>
					<div class="col-sm-3 p-t-5">
						<select class="selectpicker" name="moder_group_st" id="moder_group_st">
							<option value="0">Отключить</option>
							{foreach from=$wgroups item=gr}
								<option value="{$gr.gid}"{if $gr.gid == $config_modergroup} selected="selected"{/if}>{$gr.name}</option>
							{/foreach}
						</select>
					</div>
				</div>
				
				{display_material_checkbox name="old_serverside" help_title="Режим совместимости с плагинами SB" help_text="Переключает веб-панель в режим совместимости со старой серверной частью SourceBans."}
				
				<div class="form-group form-inline m-b-5">
					<label for="admin_warns" class="col-sm-3 control-label">{help_icon title="Предупреждения" message="Позволяет включить систему предупреждений для Администраторов."} Предупреждения</label>
					
					<div class="col-sm-1 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="admin_warns" name="admin_warns" hidden="hidden" /> 
							<label for="admin_warns" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label>
						</div>
					</div>
					
					<div class="col-sm-3">
						<div class="fg-line">
							<input type="text" class="form-control" id="admin_warns_max" name="admin_warns_max" placeholder="Максимальное кол-во предупреждений" value="{$maxWarnings}" style="width: 100%;" />
						</div>
					</div>
				</div>
			</div>

			<div class="card-body card-padding text-center">
				{sb_button text="Сохранить" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" id="fsettings" submit=true}
				&nbsp;
				{sb_button text="Назад" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="fback"}
			</div>
		</div>
	</div>
</form>

{if $old_serverside}<script>$('old_serverside').checked = true;</script>{/if}
{if $warnings_enabled}<script>$('admin_warns').checked = true;</script>{/if}

{literal}
<script>$('admin_warns').onclick = function() {
    $('admin_warns_max').disabled = !$('admin_warns').checked;
}
$('admin_warns').onclick();</script>
{/literal}
