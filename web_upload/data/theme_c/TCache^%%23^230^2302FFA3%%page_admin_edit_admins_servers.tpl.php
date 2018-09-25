<?php /* Smarty version 2.6.29, created on 2018-09-18 19:02:44
         compiled from page_admin_edit_admins_servers.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sb_button', 'page_admin_edit_admins_servers.tpl', 50, false),)), $this); ?>
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
								<?php if ($this->_tpl_vars['row_count'] < 1): ?>
										<tr>
											<td><b><i>Вам нужно добавить сервер или группу серверов, прежде, чем вы сможете настроить разрешения админов сервера</i></b></td>
										</tr>
								<?php else: ?>
									<?php $_from = ($this->_tpl_vars['group_list']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group']):
?>
										<tr>
											<td>
												<div class="checkbox m-b-15">
													<label for="group_<?php echo $this->_tpl_vars['group']['gid']; ?>
">
														<input type="checkbox" id="group_<?php echo $this->_tpl_vars['group']['gid']; ?>
" name="group[]" value="g<?php echo $this->_tpl_vars['group']['gid']; ?>
" hidden="hidden" />
														<i class="input-helper"></i> <?php echo $this->_tpl_vars['group']['name']; ?>
<b><i> (Группа сервера)</i></b></span>
													</label>
												</div>
											</td>
										</tr>
									<?php endforeach; endif; unset($_from); ?>
									<?php $_from = ($this->_tpl_vars['server_list']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['server']):
?>
										<tr>
											<td>
												<div class="checkbox m-b-15">
													<label for="server_<?php echo $this->_tpl_vars['server']['sid']; ?>
">
														<input type="checkbox" name="servers[]" id="server_<?php echo $this->_tpl_vars['server']['sid']; ?>
" value="s<?php echo $this->_tpl_vars['server']['sid']; ?>
" hidden="hidden" />
														<i class="input-helper"></i> <span id="server_host_<?php echo $this->_tpl_vars['server']['sid']; ?>
"><i>Получение имени сервера...</i></span>
													</label>
												</div>
											</td>
										</tr>
									<?php endforeach; endif; unset($_from); ?>
								<?php endif; ?>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body card-padding text-center">
						<?php if ($this->_tpl_vars['row_count'] > 0): ?>
							<?php echo smarty_function_sb_button(array('text' => "Сохранить",'icon' => "<i class='zmdi zmdi-check-all'></i>",'class' => "bgm-green btn-icon-text",'id' => 'editadminserver','submit' => true), $this);?>

							&nbsp;
						<?php endif; ?>
		      			<?php echo smarty_function_sb_button(array('text' => "Назад",'onclick' => "history.go(-1)",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'aback'), $this);?>

			</div>
			<script>
			<?php $_from = ($this->_tpl_vars['assigned_servers']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['asrv']):
?>
				if($('server_<?php echo $this->_tpl_vars['asrv']['0']; ?>
'))$('server_<?php echo $this->_tpl_vars['asrv']['0']; ?>
').checked = true;
				if($('group_<?php echo $this->_tpl_vars['asrv'][1]; ?>
'))$('group_<?php echo $this->_tpl_vars['asrv'][1]; ?>
').checked = true;
			<?php endforeach; endif; unset($_from); ?>
			<?php $_from = ($this->_tpl_vars['server_list']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['server']):
?>
				xajax_ServerHostPlayers(<?php echo $this->_tpl_vars['server']['sid']; ?>
, "id", "server_host_<?php echo $this->_tpl_vars['server']['sid']; ?>
");
			<?php endforeach; endif; unset($_from); ?>
			</script>
		</div>
	</div>
</form>