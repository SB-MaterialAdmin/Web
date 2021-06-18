<div class="row">
	<div class="col-sm-2">
	</div>
	<div class="col-sm-8">
		<div class="card">
			<div class="card-body table-responsive">
				
				<table width="100%" class="table">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="25%">Критерий</th>
							<th width="70%">Ввод</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="p-b-5">
								<div class="p-t-5">
									<label class="radio radio-inline m-r-20" for="name">
										<input id="name" name="search_type" type="radio" value="name" hidden="hidden" />
										<i class="input-helper"></i> 
									</label>
								</div>
							</td>
							<td class="p-b-5">
								<div class="p-t-5"><label for="nick" onclick="$('name').checked = true">Ник игрока</label></div>
							</td>
							<td class="p-b-5">
								<div class="fg-line">
									<input type="text" class="form-control" id="nick" value="" onmouseup="$('name').checked = true" placeholder="Введите ник" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="p-b-5">
								<div class="p-t-5">
									<label class="radio radio-inline m-r-20" for="steam_">
										<input id="steam_" name="search_type" type="radio" value="radiobutton" hidden="hidden" />
										<i class="input-helper"></i> 
									</label>
								</div>
							</td>
							<td class="p-b-5">
								<div class="p-t-5"><label for="steamid" onclick="$('steam_').checked = true">SteamID</label></div>
							</td>
							<td class="p-b-5">
								<div class="col-sm-6 p-0">
									<div class="fg-line">
										<input type="text" class="form-control" id="steamid" value="" onmouseup="$('steam_').checked = true" placeholder="Введите SteamID" />
									</div>
								</div>
								<div class="col-sm-6 p-t-5 p-r-0">
									<select class="selectpicker" id="steam_match" onmouseup="$('steam_').checked = true">
										<option value="0" selected>Точное совпадение</option>
										<option value="1">Примерное совпадение</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="p-b-5">
								<div class="p-t-5">
									<label class="radio radio-inline m-r-20" for="reason_">
										<input id="reason_" name="search_type" type="radio" value="name" hidden="hidden" />
										<i class="input-helper"></i> 
									</label>
								</div>
							</td>
							<td class="p-b-5">
								<div class="p-t-5"><label for="ban_reason" onclick="$('reason_').checked = true">Причина</label></div>
							</td>
							<td class="p-b-5">
								<div class="fg-line">
									<input type="text" class="form-control" id="ban_reason" value="" onmouseup="$('reason_').checked = true" placeholder="Введите причину бана" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="p-b-5">
								<div class="p-t-5">
									<label class="radio radio-inline m-r-20" for="date">
										<input id="date" name="search_type" type="radio" value="name" hidden="hidden" />
										<i class="input-helper"></i> 
									</label>
								</div>
							</td>
							<td class="p-b-5">
								<div class="p-t-5"><label for="day" onclick="$('date').checked = true">Дата</label></div>
							</td>
							<td class="p-b-5">
								<div class="row">
									<div class="col-sm-12 p-0">
										<div class="col-sm-4">
											<div class="fg-line">
												<input type="text" class="form-control" id="day" value="" onmouseup="$('date').checked = true" placeholder="День" maxlength="2" />
											</div>
										</div>
										<div class="col-sm-4">
											<div class="fg-line">
												<input type="text" class="form-control" id="month" value="" onmouseup="$('date').checked = true" placeholder="Месяц" maxlength="2" />
											</div>
										</div>
										<div class="col-sm-4">
											<div class="fg-line">
												<input type="text" class="form-control" id="year" value="" onmouseup="$('date').checked = true" placeholder="Год" maxlength="4" />
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
						
						<tr>
							<td class="p-b-5">
								<div class="p-t-5">
									<label class="radio radio-inline m-r-20" for="length_">
										<input id="length_" name="search_type" type="radio" value="radiobutton" hidden="hidden" />
										<i class="input-helper"></i> 
									</label>
								</div>
							</td>
							<td class="p-b-5">
								<div class="p-t-5"><label onclick="$('length_').checked = true" for="length_type">Срок</label></div>
							</td>
							<td class="p-b-5">
								<div class="col-sm-2 p-t-5 p-l-0 p-r-0">
									<select class="selectpicker" id="length_type" onmouseup="$('length_').checked = true">
										<option value="e" title="=">=</option>
										<option value="h" title=">">&gt;</option>
										<option value="l" title="<">&lt;</option>
										<option value="eh" title=">=">&gt;=</option>
										<option value="el" title="<=">&lt;=</option>
									</select>
								</div>
								<div class="col-sm-5 p-t-5">
									<div class="fg-line">
										<input type="text" class="form-control" id="other_length" name="other_length" value="" onmouseup="$('length_').checked = true" placeholder="Введите срок" />
									</div>
								</div>
								<div class="col-sm-5 p-t-5 p-r-0 select">
									<select class="form-control" id="length" onmouseup="$('length_').checked = true" onchange="switch_length(this);">
										<option value="0">Навсегда</option>
										<optgroup label="минуты">
											<option value="1">  1 минута</option>
											<option value="5">  5 минут</option>
											<option value="10">  10 минут</option>
											<option value="15">  15 минут</option>
											<option value="30">  30 минут</option>
											<option value="45">  45 минут</option>
										</optgroup>
										<optgroup label="часы">
											<option value="60">  1 час</option>
											<option value="120">  2 часа</option>
											<option value="180">  3 часа</option>
											<option value="240">  4 часа</option>
											<option value="480">  8 часов</option>
											<option value="720">  12 часов</option>
										</optgroup>
										<optgroup label="дни">
											<option value="1440">  1 день</option>
											<option value="2880">  2 дня</option>
											<option value="4320">  3 дня</option>
											<option value="5760">  4 дня</option>
											<option value="7200">  5 дней</option>
											<option value="8640">  6 дней</option>
										</optgroup>
										<optgroup label="недели">
											<option value="10080">  1 неделя</option>
											<option value="20160">  2 недели</option>
											<option value="30240">  3 недели</option>
										</optgroup>
										<optgroup label="месяцы">
											<option value="40320">  1 месяц</option>
											<option value="80640">  2 месяца</option>
											<option value="120960">  3 месяца</option>
											<option value="241920">  6 месяцев</option>
											<option value="483840">  12 месяцев</option>
										</optgroup>
										<option value="other">  Другой срок в минутах</option>
									</select>
								</div>
							</td>
						</tr>
						
						<tr>
							<td class="p-b-5">
								<div class="p-t-5">
									<label class="radio radio-inline m-r-20" for="ban_type_">
										<input id="ban_type_" name="search_type" type="radio" value="radiobutton" hidden="hidden" />
										<i class="input-helper"></i> 
									</label>
								</div>
							</td>
							<td class="p-b-5">
								<div class="p-t-5"><label for="ban_type" onclick="$('ban_type_').checked = true">Тип бана</label></div>
							</td>
							<td class="p-b-5">
								<div class="col-sm-12 p-r-0 p-l-0">
									<select class="selectpicker" id="ban_type" onmouseup="$('ban_type_').checked = true">
										<option value="0" selected>Steam ID</option>
										<option value="1">IP адрес</option>
									</select>
								</div>
							</td>
						</tr>
						{if !$hideadminname}
							<tr>
								<td class="p-b-5">
									<div class="p-t-5">
										<label class="radio radio-inline m-r-20" for="admin">
											<input id="admin" name="search_type" type="radio" value="radiobutton" hidden="hidden" />
											<i class="input-helper"></i> 
										</label>
									</div>
								</td>
								<td class="p-b-5">
									<div class="p-t-5"><label for="ban_admin" onclick="$('admin').checked = true">Админ</label></div>
								</td>
								<td class="p-b-5">
									<div class="col-sm-12 p-r-0 p-l-0 select">
										<select class="form-control" id="ban_admin" onmouseup="$('admin').checked = true">
											{foreach from="$admin_list" item="admin}
												<option label="{$admin.user}" value="{$admin.aid}">  {$admin.user}</option>
											{/foreach}
										</select>
									</div>
								</td>
							</tr>
						{/if}
						
						<tr>
							<td class="p-b-5">
								<div class="p-t-5">
									<label class="radio radio-inline m-r-20" for="where_banned">
										<input id="where_banned" name="search_type" type="radio" value="radiobutton" hidden="hidden" />
										<i class="input-helper"></i> 
									</label>
								</div>
							</td>
							<td class="p-b-5">
								<div class="p-t-5"><label for="server" onclick="$('where_banned').checked = true">Забанен из</label></div>
							</td>
							<td class="p-b-5">
								<div class="col-sm-12 p-t-5 p-r-0 p-l-0 select">
									<select class="form-control" id="server" onmouseup="$('where_banned').checked = true">
										<option label="Web Бан" value="0">Web Бан</option>
										{foreach from="$server_list" item="server}
											<option value="{$server.sid}" id="ss{$server.sid}">  Получение адреса... ({$server.ip}:{$server.port})</option>
										{/foreach}
									</select>
								</div>
							</td>
						</tr>
						{if $is_admin}
							<tr>
								<td class="p-b-5">
									<div class="p-t-5">
										<label class="radio radio-inline m-r-20" for="comment_">
											<input id="comment_" name="search_type" type="radio" value="radiobutton" hidden="hidden" />
											<i class="input-helper"></i> 
										</label>
									</div>
								</td>
								<td class="p-b-5">
									<div class="p-t-5"><label for="ban_comment" onclick="$('comment_').checked = true">Комментарий</label></div>
								</td>
								<td class="p-b-5">
									<div class="fg-line">
										<input type="text" class="form-control" id="ban_comment" value="" onmouseup="$('comment_').checked = true" placeholder="Введите комментарий" />
									</div>
								</td>
							</tr>
						{/if}
						<tr>
							<td>
							</td>
							<td>
							</td>
							<td>
							</td>
						</tr>
						
					</tbody>
				</table>
				
			</div>
			<div class="card-body p-b-20 text-center">
				{sb_button text="Поиск" onclick="search_blocks();" icon="<i class='zmdi zmdi-search'></i>" class="bgm-green btn-icon-text" id="searchbtn" submit=false}
			</div>
		</div>
	</div>
	<div class="col-sm-2">
	</div>
</div>
{$server_script}
<script>InitAccordion('tr.sea_open', 'div.panel', 'content');</script>
