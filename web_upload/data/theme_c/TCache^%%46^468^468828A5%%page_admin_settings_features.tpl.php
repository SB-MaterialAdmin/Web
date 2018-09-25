<?php /* Smarty version 2.6.29, created on 2018-09-24 17:56:35
         compiled from page_admin_settings_features.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'help_icon', 'page_admin_settings_features.tpl', 11, false),array('function', 'display_material_checkbox', 'page_admin_settings_features.tpl', 103, false),array('function', 'sb_button', 'page_admin_settings_features.tpl', 124, false),)), $this); ?>
<form action="" method="post">
    <input type="hidden" name="settingsGroup" value="features" />
    <div class="card" id="group.features">
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Опции<small>За дополнительной информацией или помощью наведите курсор мыши на знак вопроса.</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
			
				<div class="form-group m-b-5">
					<label for="export_public" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Включить публичный список банов",'message' => "Установите этот флажок, чтобы все могли скачать список банов."), $this);?>
 Разрешить экспорт банов</label>
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
					<label for="enable_kickit" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Включить кик",'message' => "Установите этот флажок, чтобы кикнуть игрока, когда бан добавлен в базу."), $this);?>
 Включить кик</label>
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
					<label for="enable_groupbanning" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Включить групповые баны",'message' => "Установите этот флажок, если вы хотите включить бан групп."), $this);?>
 Включить групповые баны</label>
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
					<label for="enable_friendsbanning" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Включить баны друзей",'message' => "Установите этот флажок, если вы хотите включить бан всех друзей игрока."), $this);?>
 Включить баны друзей</label>
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
					<label for="enable_adminrehashing" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Включить перезагрузку списка администраторов",'message' => "Установите этот флажок, если вы хотите, чтобы права администраторов перезагружались при любом изменении админов.групп."), $this);?>
 Включить перезагрузку списка администраторов</label>
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
					<label for="enable_admininfo" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Информация об Администраторе",'message' => "Показывает информацию(скайп, вк, STEAMID) о забанившем игрока Администраторе в банлисте или мут/гаг листе."), $this);?>
 Информация об администраторе</label>
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
					<label for="allow_admininfo" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Смена информации админом",'message' => "Разрешить пользователю самому менять VK или Skype в своем профиле?"), $this);?>
 Смена своей информации Админом</label>
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
					<label for="moder_group_st" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Модерирование группы",'message' => "Система которая позволяет администратору редактировать вообще любые баны(при условии, что ему разрешено редактировать все баны), но только на том сервере, где у этого права администратора. Полезно будет тем, у кого есть такие должности как 'управляющий сервером', чтобы администраторы в этой группе могли редактировать только баны на тех серверах, где они управляют."), $this);?>
 Модерировать группу</label>
					<div class="col-sm-3 p-t-5">
						<select class="selectpicker" name="moder_group_st" id="moder_group_st">
							<option value="0">Отключить</option>
							<?php $_from = $this->_tpl_vars['wgroups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gr']):
?>
								<option value="<?php echo $this->_tpl_vars['gr']['gid']; ?>
"<?php if ($this->_tpl_vars['gr']['gid'] == $this->_tpl_vars['config_modergroup']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['gr']['name']; ?>
</option>
							<?php endforeach; endif; unset($_from); ?>
						</select>
					</div>
				</div>
				
				<?php echo materialdesign_checkbox(array('name' => 'old_serverside','help_title' => "Режим совместимости с плагинами SB",'help_text' => "Переключает веб-панель в режим совместимости со старой серверной частью SourceBans."), $this);?>

				
				<div class="form-group form-inline m-b-5">
					<label for="admin_warns" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Предупреждения",'message' => "Позволяет включить систему предупреждений для Администраторов."), $this);?>
 Предупреждения</label>
					
					<div class="col-sm-1 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="admin_warns" name="admin_warns" hidden="hidden" /> 
							<label for="admin_warns" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label>
						</div>
					</div>
					
					<div class="col-sm-3">
						<div class="fg-line">
							<input type="text" class="form-control" id="admin_warns_max" name="admin_warns_max" placeholder="Максимальное кол-во предупреждений" value="<?php echo $this->_tpl_vars['maxWarnings']; ?>
" style="width: 100%;" />
						</div>
					</div>
				</div>
			</div>

			<div class="card-body card-padding text-center">
				<?php echo smarty_function_sb_button(array('text' => "Сохранить",'icon' => "<i class='zmdi zmdi-check-all'></i>",'class' => "bgm-green btn-icon-text",'id' => 'fsettings','submit' => true), $this);?>

				&nbsp;
				<?php echo smarty_function_sb_button(array('text' => "Назад",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'fback'), $this);?>

			</div>
		</div>
	</div>
</form>

<?php if ($this->_tpl_vars['old_serverside']): ?><script>$('old_serverside').checked = true;</script><?php endif; ?>
<?php if ($this->_tpl_vars['warnings_enabled']): ?><script>$('admin_warns').checked = true;</script><?php endif; ?>

<?php echo '
<script>$(\'admin_warns\').onclick = function() {
    $(\'admin_warns_max\').disabled = !$(\'admin_warns\').checked;
}
$(\'admin_warns\').onclick();</script>
'; ?>
