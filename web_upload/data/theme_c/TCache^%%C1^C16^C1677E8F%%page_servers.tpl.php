<?php /* Smarty version 2.6.29, created on 2018-09-18 17:04:04
         compiled from page_servers.tpl */ ?>
<?php if ($this->_tpl_vars['IN_SERVERS_PAGE']): ?>
<?php if ($this->_tpl_vars['IN_SERVERS_PAGE'] && $this->_tpl_vars['access_bans']): ?><div class="alert alert-info servers_pg" role="alert"><h4>Подсказка:</h4><span class="p-l-10">Нажмите на <mark><!--<img src="theme/img/inn.png" style="width: 20px;height: 20px;" />-->&nbsp;<i class="zmdi zmdi-label c-white" style="font-size: 17px;"></i>&nbsp;</mark> возле ника игрока, чтобы вызвать меню управления игроком.</span></div><?php endif; ?>
	<div class="card">
		<!--<div class="card-header">
			<h2>Список серверов</h2>
		</div>-->
		<div class="card-body table-responsive">
			<table class="table table-striped">
				<tbody>
					<tr>
						<th class="text-center">Игра</th>
						<th class="text-center">ОС</th>
						<th class="text-center">VAC</th>
						<th class="text-left">Название сервера</th>
						<th class="text-right">Игроки</th>
						<th class="text-right">Текущая карта</th>
					</tr>
					<?php $_from = $this->_tpl_vars['server_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['server']):
?>
						<tr id="opener_<?php echo $this->_tpl_vars['server']['sid']; ?>
" class="opener" style="cursor:pointer;" onmouseout="this.className=''" onmouseover="this.className='active'">
							<td class="text-center"><img src="images/games/<?php echo $this->_tpl_vars['server']['icon']; ?>
" border="0" /></td>
							<td class="text-center" id="os_<?php echo $this->_tpl_vars['server']['sid']; ?>
"></td>
							<td class="text-center" id="vac_<?php echo $this->_tpl_vars['server']['sid']; ?>
"></td>
							<td id="host_<?php echo $this->_tpl_vars['server']['sid']; ?>
"><i>Получение сведений о сервере...</i></td>
							<td class="text-right" id="players_<?php echo $this->_tpl_vars['server']['sid']; ?>
">Н/С</td>
							<td class="text-right" id="map_<?php echo $this->_tpl_vars['server']['sid']; ?>
">Н/С</td>
						</tr>
						<tr>
							<td colspan="7" align="center" style="padding: 0px;border-top: 0px solid #FFFFFF;">
								<div class="opener" style="visibility: hidden; zoom: 1; opacity: 0; height: 0px;">
									<div id="serverwindow_<?php echo $this->_tpl_vars['server']['sid']; ?>
" class="p-20">
											<div id="sinfo_<?php echo $this->_tpl_vars['server']['sid']; ?>
">
												<table width="90%" border="0" class="listtable">
													<tr>
														<td valign="top" class="table-responsive p-b-10">
															<table width="100%" border="0" id="playerlist_<?php echo $this->_tpl_vars['server']['sid']; ?>
" name="playerlist_<?php echo $this->_tpl_vars['server']['sid']; ?>
" align="center">
															</table>
														</td>
														<td width="355px" class="listtable_2 opener" valign="top">
															<img id="mapimg_<?php echo $this->_tpl_vars['server']['sid']; ?>
" height='255' width='100%' src='images/maps/nomap.jpg'>
															<br />
															<br />
															<div align='center'>
																<b>IP : Порт - <?php echo $this->_tpl_vars['server']['ip']; ?>
:<?php echo $this->_tpl_vars['server']['port']; ?>
</b> <br><br>
																<button type='submit' onclick="document.location = 'steam://connect/<?php echo $this->_tpl_vars['server']['ip']; ?>
:<?php echo $this->_tpl_vars['server']['port']; ?>
'" name='button' class='btn bgm-teal btn-icon-text waves-effect' id='button'><i class='zmdi zmdi-input-hdmi'></i> Подключиться</button>
																<button type='button' onclick="ShowBox('обновление..','<b>Обновление данных сервера...</b><br><i>Ждите!</i>', 'blue', '', true, 1000);document.getElementById('dialog-control').setStyle('display', 'none');xajax_RefreshServer(<?php echo $this->_tpl_vars['server']['sid']; ?>
);" name='button' class='btn bgm-amber btn-icon-text waves-effect' id='button' value='Refresh'><i class='zmdi zmdi zmdi-refresh-alt'></i>Обновить статус</button>
															</div>
															<br />
														</td>
													</tr>
												</table>
											</div>
											<div id="noplayer_<?php echo $this->_tpl_vars['server']['sid']; ?>
" name="noplayer_<?php echo $this->_tpl_vars['server']['sid']; ?>
" style="display:none;">
												<table width="90%" border="0" class="listtable">
													<tr>
														<td align="center" width="90%">
															<h3>На сервере нет игроков! :(</h3>
														</td>
														<td>
															<div align='center'>
																<img height='255' width='340' src='images/maps/nomap.jpg'>
																<br />
																<br />
																<b>IP : Порт - <?php echo $this->_tpl_vars['server']['ip']; ?>
:<?php echo $this->_tpl_vars['server']['port']; ?>
</b><br><br>
																<!--<button type='submit' onclick="document.location = 'steam://connect/<?php echo $this->_tpl_vars['server']['ip']; ?>
:<?php echo $this->_tpl_vars['server']['port']; ?>
'" name='button' class='btn bgm-teal btn-icon-text waves-effect' id='button'><i class='zmdi zmdi-input-hdmi'></i> Подключиться</button>-->
																<button type='button' onclick="ShowBox('Обновление..','<b>Обновление данных сервера...</b><br><i>Please Wait!</i>', 'blue', '', true, 1000);document.getElementById('dialog-control').setStyle('display', 'none');xajax_RefreshServer(<?php echo $this->_tpl_vars['server']['sid']; ?>
);" name='button' class='btn bgm-amber btn-icon-text waves-effect' id='button'><i class='zmdi zmdi zmdi-refresh-alt'></i>Обновить статус</button>
																<br />
																<br />
															</div>
														</td>
													</tr>
												</table>
												<br><br>
											</div>
									</div>
								</div>
							</td>
						</tr>
					<?php endforeach; endif; unset($_from); ?>
				</tbody>
			</table>
		</div>
	</div>
<?php else: ?>
	<div class="card">
		<div class="card-header">
			<h2>Наши сервера <small>Список активных игровых серверов в данный момент.</small></h2>
		</div>

		<div class="card-body table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th class="text-center">Игра</th>
						<th class="text-center hidden-xs">OC</th>
						<th class="text-center hidden-xs">VAC</th>
						<th>Название сервера</th>
						<th class="text-right">Игроки</th>
						<th class="text-right hidden-xs">Текущая карта</th>
					</tr>
				</thead>
				<tbody>
					<?php $_from = $this->_tpl_vars['server_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['server']):
?>
						<tr id="opener_<?php echo $this->_tpl_vars['server']['sid']; ?>
" style="cursor:pointer;" onmouseout="this.className=''" onmouseover="this.className='active'" onclick="<?php echo $this->_tpl_vars['server']['evOnClick']; ?>
">
							<td class="text-center"><img src="images/games/<?php echo $this->_tpl_vars['server']['icon']; ?>
" border="0" /></td>
							<td class="text-center hidden-xs" id="os_<?php echo $this->_tpl_vars['server']['sid']; ?>
"></td>
							<td class="text-center hidden-xs" id="vac_<?php echo $this->_tpl_vars['server']['sid']; ?>
"></td>
							<td id="host_<?php echo $this->_tpl_vars['server']['sid']; ?>
"><i>Получение сведений о сервере...</i></td>
							<td class="text-right" id="players_<?php echo $this->_tpl_vars['server']['sid']; ?>
">Н/С</td>
							<td class="text-right hidden-xs" id="map_<?php echo $this->_tpl_vars['server']['sid']; ?>
">Н/С</td>
						</tr>
					<?php endforeach; endif; unset($_from); ?>
				</tbody>
			</table>
		</div>
	</div>
<?php endif; ?>



<?php if ($this->_tpl_vars['IN_SERVERS_PAGE']): ?>
	<script type="text/javascript">
		InitAccordion('tr.opener', 'div.opener', 'content');
	</script>
<?php endif; ?>