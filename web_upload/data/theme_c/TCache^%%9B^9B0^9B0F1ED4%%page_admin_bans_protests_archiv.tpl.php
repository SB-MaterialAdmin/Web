<?php /* Smarty version 2.6.29, created on 2018-09-25 13:23:26
         compiled from page_admin_bans_protests_archiv.tpl */ ?>
<?php if (! $this->_tpl_vars['permission_protests']): ?>
Доступ запрещен!
<?php else: ?>
<div class="card-header">
	<h2>Архив протестов банов (<?php echo $this->_tpl_vars['protest_count_archiv']; ?>
)<small>Кликните на имя игрока для просмотра подробностей бана</small></h2>
</div>
<div id="banlist-nav"> 
	<?php echo $this->_tpl_vars['aprotest_nav']; ?>

</div>
<table class="table table-bordered">
	<tr>
		<th width="10%" class="text-center">Ник</th>
		<th width="20%" class="text-center">Steam ID</th>
		<th width="50%" class="text-center">Сообщение</th>
		<th width="20%" class="text-center">Действие</th>
	</tr>
	<?php $_from = ($this->_tpl_vars['protest_list_archiv']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['protest']):
?>
	<tr>
		<td class="text-center"><?php if ($this->_tpl_vars['protest']['archiv'] != 2): ?><a href="./index.php?p=banlist<?php if ($this->_tpl_vars['protest']['authid'] != ""): ?>&advSearch=<?php echo $this->_tpl_vars['protest']['authid']; ?>
&advType=steamid<?php else: ?>&advSearch=<?php echo $this->_tpl_vars['protest']['ip']; ?>
&advType=ip<?php endif; ?>" title="Показать бан"><?php echo $this->_tpl_vars['protest']['name']; ?>
</a><?php else: ?><i><font color="#677882">бан удалён</font></i><?php endif; ?></td>
		<td class="text-center"><?php if ($this->_tpl_vars['protest']['authid'] != ""): ?><a href="https://steamcommunity.com/profiles/<?php echo $this->_tpl_vars['protest']['commid']; ?>
"><?php echo $this->_tpl_vars['protest']['authid']; ?>
</a><?php else: ?><?php echo $this->_tpl_vars['protest']['ip']; ?>
<?php endif; ?></td>

		    <td><?php echo $this->_tpl_vars['protest']['reason']; ?>
</td>
		<td class="text-center">
			<?php if ($this->_tpl_vars['permission_editban']): ?>
			<a href="#" onclick="RemoveProtest('<?php echo $this->_tpl_vars['protest']['pid']; ?>
', '<?php if ($this->_tpl_vars['protest']['authid'] != ""): ?><?php echo $this->_tpl_vars['protest']['authid']; ?>
<?php else: ?><?php echo $this->_tpl_vars['protest']['ip']; ?>
<?php endif; ?>', '2');">Восстановить</a> -
			<a href="#" onclick="RemoveProtest('<?php echo $this->_tpl_vars['protest']['pid']; ?>
', '<?php if ($this->_tpl_vars['protest']['authid'] != ""): ?><?php echo $this->_tpl_vars['protest']['authid']; ?>
<?php else: ?><?php echo $this->_tpl_vars['protest']['ip']; ?>
<?php endif; ?>', '0');">Удалить</a> -
			<?php endif; ?>
			<a href="index.php?p=admin&c=bans&o=email&type=p&id=<?php echo $this->_tpl_vars['protest']['pid']; ?>
">Контакты</a>
		</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</table>
<?php endif; ?>
