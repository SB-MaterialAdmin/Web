<?php /* Smarty version 2.6.29, created on 2018-09-18 17:08:12
         compiled from page_adminlist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'page_adminlist.tpl', 44, false),)), $this); ?>
<?php $_from = ($this->_tpl_vars['games']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['game']):
?>
<?php if ($this->_tpl_vars['game']['servers'] > 0): ?>
<?php $this->assign('currgame', $this->_tpl_vars['game']['mid']); ?>
<div class="card">
	<div class="card-header">
			<h2>
				<img class="gameicon" src="images/games/<?php echo $this->_tpl_vars['game']['icon']; ?>
" style="width: 20px;height: 20px;" /> 
                <?php echo $this->_tpl_vars['game']['name']; ?>

				<small>Полный список администраторов на доступных игровых серверах с подробной информацией.</small>
			</h2>
	</div>
	<div class="card-body card-padding">	
		<table id="data-table-command" class="table table-striped table-vmiddle">
			<thead>
				<?php $_from = ($this->_tpl_vars['server_list']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['server']):
?>
                    <?php if ($this->_tpl_vars['server']['modid'] == $this->_tpl_vars['currgame']): ?>
                    <?php if ($this->_tpl_vars['server']['admincount'] > 0): ?>
                    <?php $this->assign('adminlist', $this->_tpl_vars['server']['adminlist']); ?>
					<tr class="opener1" style="cursor:pointer;" name="servers[]" id="s_<?php echo $this->_tpl_vars['server']['sid']; ?>
" value="s<?php echo $this->_tpl_vars['server']['sid']; ?>
">
						<th width="10%">
							<img id="mapimg_<?php echo $this->_tpl_vars['server']['sid']; ?>
" class="img-responsive img-thumbnail" style="width:60px; height:60px" src='images/maps/nomap.jpg'>
						</th>
						<th width="100%" class="f-14" id="sa<?php echo $this->_tpl_vars['server']['sid']; ?>
"><span id="host_<?php echo $this->_tpl_vars['server']['sid']; ?>
">Получение имени сервера... <?php echo $this->_tpl_vars['server']['ip']; ?>
:<?php echo $this->_tpl_vars['server']['port']; ?>
</span></th>
						<th width="10%" style="font-size: 28px;"><i class="zmdi zmdi-accounts-list"></i></th>
					</tr>
					<tr>
						<td colspan="3" align="center" style="background-color: #f4f4f4;padding: 0px;border-top: 0px solid #FFFFFF;">
							<div class="opener" style="visibility: hidden; zoom: 1; opacity: 0;">
								<table id="data-table-command" class="table table-striped table-vmiddle" style="width:87%" align="center">
									<thead>
										<tr>
											<th class="bgm-bluegray c-white text-center" width="20%">Ник</th>
											<th class="bgm-bluegray c-white text-center" width="25%">Группа</th>
											<th class="bgm-bluegray c-white text-center" width="12%">Skype</th>
											<th class="bgm-bluegray c-white text-center" width="12%">VK</th>
											<th class="bgm-bluegray c-white text-center">Должность</th>
										</tr>
									</thead>
									<tbody id="adminlist">
                                        <?php $_from = ($this->_tpl_vars['adminlist']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['admin']):
?>
										<tr>
											<td><img src="<?php echo $this->_tpl_vars['admin']['avatar']; ?>
" style="width: 20px; height: 20px; border-radius: 25px;" /> <a href="https://steamcommunity.com/profiles/<?php echo $this->_tpl_vars['admin']['authid']; ?>
"><?php echo $this->_tpl_vars['admin']['user']; ?>
</a></td>
											<td><?php if ($this->_tpl_vars['admin']['srv_group'] != ""): ?><?php echo $this->_tpl_vars['admin']['srv_group']; ?>
<?php else: ?>Нет группы\Индивид. права<?php endif; ?></td>
											<td><?php if ($this->_tpl_vars['admin']['skype'] != ""): ?><a href="skype:<?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['skype'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
?userinfo" title="Просмотреть информацию о профиле Skype"><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['skype'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><?php else: ?>Неизвестно<?php endif; ?></td>
											<td><?php if ($this->_tpl_vars['admin']['vk'] != ""): ?><a href="https://vk.com/<?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['vk'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="Перейти в профиль ВК"><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['vk'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><?php else: ?>Неизвестно<?php endif; ?></td>
											<td><?php if ($this->_tpl_vars['admin']['comment'] != ""): ?><?php echo $this->_tpl_vars['admin']['comment']; ?>
<?php else: ?>Нет данных. Обычный рядовой, контролирует порядок на серверах.<?php endif; ?></td>
										</tr>
										<?php endforeach; endif; unset($_from); ?>
									</tbody>
								</table>
							</div>
						</td>
					</tr>
					<script>xajax_ServerHostPlayers(<?php echo $this->_tpl_vars['server']['sid']; ?>
, 'servers', '', '0', '-1', '<?php echo $this->_tpl_vars['IN_HOME']; ?>
', 70);</script>
                    <?php endif; ?>
                    <?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</thead>
		</table>
	</div>
</div>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

<?php echo $this->_tpl_vars['server_script']; ?>


<script type="text/javascript">
	InitAccordion('tr.opener1', 'div.opener', 'content');
</script>