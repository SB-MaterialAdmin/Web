<?php /* Smarty version 2.6.29, created on 2018-09-24 17:57:52
         compiled from page_admin_edit_admins_group.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sb_button', 'page_admin_edit_admins_group.tpl', 52, false),)), $this); ?>
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
												<?php $_from = $this->_tpl_vars['group_lst']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sg']):
?>
												<option value="<?php echo $this->_tpl_vars['sg']['id']; ?>
"<?php if ($this->_tpl_vars['sg']['id'] == $this->_tpl_vars['server_admin_group_id']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['sg']['name']; ?>
</option>
												<?php endforeach; endif; unset($_from); ?>
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
											<?php $_from = $this->_tpl_vars['web_lst']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['wg']):
?>
											<option value="<?php echo $this->_tpl_vars['wg']['gid']; ?>
"<?php if ($this->_tpl_vars['wg']['gid'] == $this->_tpl_vars['group_admin_id']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['wg']['name']; ?>
</option>
											<?php endforeach; endif; unset($_from); ?>
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
				<?php echo smarty_function_sb_button(array('text' => "Сохранить изменения",'icon' => "<i class='zmdi zmdi-account-add'></i>",'class' => "bgm-green btn-icon-text",'id' => 'agroups','submit' => true), $this);?>

				&nbsp;
				<?php echo smarty_function_sb_button(array('text' => "Назад",'onclick' => "history.go(-1)",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'aback'), $this);?>

			</div>
        <?php echo $this->_tpl_vars['server_script']; ?>

		</div>
	</div>		
</form>