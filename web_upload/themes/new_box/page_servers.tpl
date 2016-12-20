{if $IN_SERVERS_PAGE}
{if $IN_SERVERS_PAGE && $access_bans}<div class="alert alert-info servers_pg" role="alert"><h4>Подсказка:</h4><span class="p-l-10">Нажмите на <mark><!--<img src="themes/new_box/img/inn.png" style="width: 20px;height: 20px;" />-->&nbsp;<i class="zmdi zmdi-label c-white" style="font-size: 17px;"></i>&nbsp;</mark> возле ника игрока, чтобы вызвать меню управления игроком.</span></div>{/if}
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
					{foreach from=$server_list item=server}
						<tr id="opener_{$server.sid}" class="opener" style="cursor:pointer;" onmouseout="this.className=''" onmouseover="this.className='active'">
							<td class="text-center"><img src="images/games/{$server.icon}" border="0" /></td>
							<td class="text-center" id="os_{$server.sid}"></td>
							<td class="text-center" id="vac_{$server.sid}"></td>
							<td id="host_{$server.sid}"><i>Получение сведений о сервере...</i></td>
							<td class="text-right" id="players_{$server.sid}">Н/С</td>
							<td class="text-right" id="map_{$server.sid}">Н/С</td>
						</tr>
						<tr>
							<td colspan="7" align="center" style="padding: 0px;border-top: 0px solid #FFFFFF;">
								<div class="opener" style="visibility: hidden; zoom: 1; opacity: 0; height: 0px;">
									<div id="serverwindow_{$server.sid}" class="p-20">
											<div id="sinfo_{$server.sid}">
												<table width="90%" border="0" class="listtable">
													<tr>
														<td valign="top" class="table-responsive p-b-10">
															<table width="100%" border="0" id="playerlist_{$server.sid}" name="playerlist_{$server.sid}" align="center">
															</table>
														</td>
														<td width="355px" class="listtable_2 opener" valign="top">
															<img id="mapimg_{$server.sid}" height='255' width='100%' src='images/maps/nomap.jpg'>
															<br />
															<br />
															<div align='center'>
																<b>IP : Порт - {$server.ip}:{$server.port}</b> <br><br>
																<button type='submit' onclick="document.location = 'steam://connect/{$server.ip}:{$server.port}'" name='button' class='btn bgm-teal btn-icon-text waves-effect' id='button'><i class='zmdi zmdi-input-hdmi'></i> Подключиться</button>
																<button type='button' onclick="ShowBox('обновление..','<b>Обновление данных сервера...</b><br><i>Ждите!</i>', 'blue', '', true, 1000);document.getElementById('dialog-control').setStyle('display', 'none');xajax_RefreshServer({$server.sid});" name='button' class='btn bgm-amber btn-icon-text waves-effect' id='button' value='Refresh'><i class='zmdi zmdi zmdi-refresh-alt'></i>Обновить статус</button>
															</div>
															<br />
														</td>
													</tr>
												</table>
											</div>
											<div id="noplayer_{$server.sid}" name="noplayer_{$server.sid}" style="display:none;">
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
																<b>IP : Порт - {$server.ip}:{$server.port}</b><br><br>
																<!--<button type='submit' onclick="document.location = 'steam://connect/{$server.ip}:{$server.port}'" name='button' class='btn bgm-teal btn-icon-text waves-effect' id='button'><i class='zmdi zmdi-input-hdmi'></i> Подключиться</button>-->
																<button type='button' onclick="ShowBox('Обновление..','<b>Обновление данных сервера...</b><br><i>Please Wait!</i>', 'blue', '', true, 1000);document.getElementById('dialog-control').setStyle('display', 'none');xajax_RefreshServer({$server.sid});" name='button' class='btn bgm-amber btn-icon-text waves-effect' id='button'><i class='zmdi zmdi zmdi-refresh-alt'></i>Обновить статус</button>
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
					{/foreach}
				</tbody>
			</table>
		</div>
	</div>
{else}
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
					{foreach from=$server_list item=server}
						<tr id="opener_{$server.sid}" style="cursor:pointer;" onmouseout="this.className=''" onmouseover="this.className='active'" onclick="{$server.evOnClick}">
							<td class="text-center"><img src="images/games/{$server.icon}" border="0" /></td>
							<td class="text-center hidden-xs" id="os_{$server.sid}"></td>
							<td class="text-center hidden-xs" id="vac_{$server.sid}"></td>
							<td id="host_{$server.sid}"><i>Получение сведений о сервере...</i></td>
							<td class="text-right" id="players_{$server.sid}">Н/С</td>
							<td class="text-right hidden-xs" id="map_{$server.sid}">Н/С</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	</div>
{/if}



{if $IN_SERVERS_PAGE}
	<script type="text/javascript">
		InitAccordion('tr.opener', 'div.opener', 'content');
	</script>
{/if}
