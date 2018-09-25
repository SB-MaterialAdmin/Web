<?php /* Smarty version 2.6.29, created on 2018-09-24 18:06:22
         compiled from page_admin_admins_warnings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'page_admin_admins_warnings.tpl', 19, false),array('function', 'sb_button', 'page_admin_admins_warnings.tpl', 22, false),)), $this); ?>
<div class="card-header">
	<h2>Список предупреждений
		<small>Всего: <?php echo $this->_tpl_vars['count']; ?>
</small>
	</h2>
</div>
<div class="table-responsive">
	<table cellspacing="0" cellpadding="0" class="table table-striped">
		<tr>
			<th width="8%">ID</th>
			<th>От кого</th>
			<th class="text-right">Причина</th>
			<th class="text-right">Истекает</th>
			<th style="width: 12%;" class="text-right">Действия</th>
		</tr>
		
		<?php $_from = ($this->_tpl_vars['Warnings']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['warning']):
?>
		<tr<?php if ($this->_tpl_vars['warning']['expired']): ?> class="warning"<?php endif; ?>>
			<td><?php echo $this->_tpl_vars['warning']['id']; ?>
</td>
			<td><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['warning']['from'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</strong></td>
			<td class="text-right"><?php echo ((is_array($_tmp=$this->_tpl_vars['warning']['reason'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td class="text-right"><?php echo $this->_tpl_vars['warning']['expires']; ?>
</td>
			<td class="text-right"><?php if ($this->_tpl_vars['warning']['expired']): ?>Недоступно<?php else: ?><?php $this->assign('warId', $this->_tpl_vars['warning']['id']); ?><?php echo smarty_function_sb_button(array('text' => "Снять",'icon' => "<i class='zmdi zmdi-check-all'></i>",'class' => "bgm-red btn-icon-text",'onclick' => "xajax_RemoveWarning(".($this->_tpl_vars['warId']).")"), $this);?>
<?php endif; ?></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
	</table>
</div>

<div class="card">
	<div class="form-horizontal" role="form" id="add-group">
		<div class="card-header">
			<h2>Выдача предупреждения</h2>
		</div>
		<div class="card-body card-padding p-b-0">
			<div class="form-group form-inline fg-float">
				<div class="col-sm-2">
					<div class="fg-line">
						<input class="input-sm form-control fg-input" type="text" id="time" name="time" style="width: 100%;">
						<label class="fg-label">Срок (в днях)</label>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="fg-line">
						<input class="input-sm form-control fg-input" type="text" id="reason" name="reason" style="width: 100%;">
						<label class="fg-label">Причина</label>
					</div>
				</div>
				<div class="col-sm-2">
					<?php echo smarty_function_sb_button(array('text' => "Добавить",'icon' => "<i class='zmdi zmdi-check-all'></i>",'class' => "bgm-orange btn-icon-text",'onclick' => "xajax_AddWarning(".($this->_tpl_vars['thisId']).", $('time').value, $('reason').value);",'submit' => false), $this);?>

				</div>
			</div>
		</div>
		<br />
	</div>
</div>