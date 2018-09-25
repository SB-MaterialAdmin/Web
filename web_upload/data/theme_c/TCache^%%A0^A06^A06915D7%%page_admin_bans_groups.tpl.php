<?php /* Smarty version 2.6.29, created on 2018-09-18 17:04:10
         compiled from page_admin_bans_groups.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'help_icon', 'page_admin_bans_groups.tpl', 16, false),array('function', 'sb_button', 'page_admin_bans_groups.tpl', 34, false),)), $this); ?>
<?php if (! $this->_tpl_vars['permission_addban']): ?>
	Доступ запрещен!
<?php else: ?>
	<?php if (! $this->_tpl_vars['groupbanning_enabled']): ?>
		Функция отключена!
	<?php else: ?>
<div class="card">
	<div class="form-horizontal" role="form" id="group.details">
		<?php if (! $this->_tpl_vars['list_steam_groups']): ?>
		<div class="card-header">
		<h2>Добавить бан Группы
		<small>Здесь вы можете добавить запрет на группу сообщества Steam. Например <code>http://steamcommunity.com/groups/interwavestudios</code></small></h2>
		</div>
		<div class="card-body card-padding p-b-0" id="group.details">
			<div class="form-group m-b-5">
				<label for="groupurl" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Ссылка на группу",'message' => "Введите ссылку на группу сообщества Steam."), $this);?>
Ссылка на группу</label>
				<div class="col-sm-9">
					<div class="fg-line">
						<input type="text" TABINDEX=1 class="form-control" id="groupurl" name="groupurl" placeholder="Введите данные">
					</div>
				<div id="groupurl.msg"></div>
				</div>
			</div>
			<div class="form-group m-b-5">
				<label for="groupreason" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Причина бана группы",'message' => "Введите причину, по которой Вы собираетесь забанить группу."), $this);?>
Причина бана группы</label>
					<div class="col-sm-9 p-t-10">
						<div class="fg-line">
							<textarea name="groupreason" id="groupreason" class="form-control p-t-5" placeholder="Будьте максимально информативны"></textarea>
						</div>
					</div>
				<div id="groupreason.msg"></div>
			</div>
		<div class="card-body card-padding text-center">
				<?php echo smarty_function_sb_button(array('text' => "Забанить группу",'onclick' => "ProcessGroupBan();",'icon' => "<i class='zmdi zmdi-shield-security'></i>",'class' => "bgm-green btn-icon-text",'id' => 'agban','submit' => false), $this);?>

					  &nbsp;
				<?php echo smarty_function_sb_button(array('text' => "Назад",'onclick' => "history.go(-1)",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'aback'), $this);?>

		</div>
		</div>
		<?php else: ?>
		<div class="card-header">
		<h2>Группы игрока <?php echo $this->_tpl_vars['player_name']; ?>

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
				<label for="groupreason" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Причина бана группы",'message' => "Введите причину бана, по которой желаете забанить группу."), $this);?>
Причина бана группы </label>
				<div class="col-sm-9 p-t-10">
					<div class="fg-line">
						<textarea name="groupreason" id="groupreason" class="form-control p-t-5" placeholder="Будьте максимально информативны"></textarea>
					</div>
					<div id="groupreason.msg" class="badentry"></div>
				</div>
			</div>
		<script type="text/javascript">$('tickswitch').value = 0;xajax_GetGroups('<?php echo $this->_tpl_vars['list_steam_groups']; ?>
');</script>
		</div>
		<div class="card-body card-padding text-center">
			<?php echo smarty_function_sb_button(array('text' => "Забанить группу",'onclick' => "CheckGroupBan();",'icon' => "<i class='zmdi zmdi-shield-security'></i>",'class' => "bgm-green btn-icon-text",'id' => 'gban','submit' => false), $this);?>

			&nbsp;
			<?php echo smarty_function_sb_button(array('text' => "Назад",'onclick' => "history.go(-1)",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'aback'), $this);?>

		</div>
		<?php endif; ?>
	</div>
</div>
	<?php endif; ?>
<?php endif; ?>