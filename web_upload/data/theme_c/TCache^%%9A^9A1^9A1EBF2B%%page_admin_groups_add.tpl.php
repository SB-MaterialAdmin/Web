<?php /* Smarty version 2.6.29, created on 2018-09-21 18:07:03
         compiled from page_admin_groups_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'help_icon', 'page_admin_groups_add.tpl', 11, false),array('function', 'sb_button', 'page_admin_groups_add.tpl', 40, false),)), $this); ?>
<?php if (! $this->_tpl_vars['permission_addgroup']): ?>
	Доступ запрещён!
<?php else: ?>
	<div class="card" id="add-group">
		<div class="form-horizontal" role="form" id="group.details">
			<div class="card-header">
				<h2>Новая группа <small>Позволяет создань новую группу на веб-сервере или на игровом сервере.</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-5">
					<label for="groupname" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Имя группы",'message' => "Введите имя для новой группы."), $this);?>
 Имя группы</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="groupname" name="groupname" placeholder="Введите данные">
						</div>
						<div id="name.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="grouptype" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Тип группы",'message' => "Выберите тип группы. Это поможет идентифицировать и разделить группы по категориям."), $this);?>
 Тип группы</label>
					<div class="col-sm-3 p-t-5">
						<select class="selectpicker" onchange="UpdateGroupPermissionCheckBoxes()" name="grouptype" id="grouptype">
							<option value="0">Выберите...</option>
							<option value="1">Группа ВЕБ админов</option>
							<option value="2">Группа админов серверов</option>
							<option value="3">Группа серверов</option>
						</select>
						<div id="type.msg" class="badentry"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="grouptype" class="col-sm-3 control-label"></label>
					<div class="col-sm-9 p-t-5 p-l-0">
						<div id="perms"></div>
					</div>
				</div>
			</div>
				
			<div class="card-body card-padding text-center">
				<?php echo smarty_function_sb_button(array('text' => "Сохранить",'onclick' => "ProcessGroup();",'icon' => "<i class='zmdi zmdi-check-all'></i>",'class' => "bgm-green btn-icon-text",'id' => 'agroup','submit' => false), $this);?>

				&nbsp;
				<?php echo smarty_function_sb_button(array('text' => "Назад",'onclick' => "history.go(-1)",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'aback'), $this);?>

			</div>
		</div>
	</div>
<?php endif; ?>