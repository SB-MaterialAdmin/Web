
				<div class="table-responsive">
					
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
										<label class="radio radio-inline m-r-20" for="admin_">
											<input id="admin_" name="search_type" type="radio" value="radiobutton" hidden="hidden" />
											<i class="input-helper"></i> 
										</label>
									</div>
								</td>
								<td class="p-b-5">
									<div class="p-t-5"><label for="nick" onclick="$('admin_').checked = true">Администратор</label></div>
								</td>
								<td class="p-b-5">
									<div class="col-sm-6 p-t-5 p-r-0 p-l-0">
										<select class="selectpicker" id="admin" onmouseup="$('admin_').checked = true">
											{foreach from="$admin_list" item="admin}
												<option label="{$admin.user}" value="{$admin.aid}">{$admin.user}</option>
											{/foreach}
										</select>
									</div>
								</td>
							</tr>
							
							<tr>
									<td class="p-b-5">
										<div class="p-t-5">
											<label class="radio radio-inline m-r-20" for="message_">
												<input id="message_" name="search_type" type="radio" value="radiobutton" hidden="hidden" />
												<i class="input-helper"></i> 
											</label>
										</div>
									</td>
									<td class="p-b-5">
										<div class="p-t-5"><label for="ip" onclick="$('message_').checked = true">Текст сообщения</label></div>
									</td>
									<td class="p-b-5">
										<div class="fg-line">
											<input type="text" class="form-control" id="message" value="" onmouseup="$('message_').checked = true" placeholder="Введите IP адрес" />
										</div>
									</td>
							</tr>
							<tr>
								<td class="p-b-5">
									<div class="p-t-5">
										<label class="radio radio-inline m-r-20" for="date_">
											<input id="date_" name="search_type" type="radio" value="radiobutton" hidden="hidden" />
											<i class="input-helper"></i> 
										</label>
									</div>
								</td>
								<td class="p-b-5">
									<div class="p-t-5"><label for="day" onclick="$('date_').checked = true">Дата</label></div>
								</td>
								<td class="p-b-5">
									<div class="row">
										<div class="col-sm-12 p-0">
											<div class="col-sm-4">
												<div class="fg-line">
													<input type="text" class="form-control" id="day" value="" onmouseup="$('date_').checked = true" placeholder="День" maxlength="2" />
												</div>
											</div>
											<div class="col-sm-4">
												<div class="fg-line">
													<input type="text" class="form-control" id="month" value="" onmouseup="$('date_').checked = true" placeholder="Месяц" maxlength="2" />
												</div>
											</div>
											<div class="col-sm-4">
												<div class="fg-line">
													<input type="text" class="form-control" id="year" value="" onmouseup="$('date_').checked = true" placeholder="Год" maxlength="4" />
												</div>
											</div>
										</div>
									</div>
								</td>
							</tr>
							
							<tr>
								<td class="p-b-5">
									<div class="p-t-5">
										<label class="radio radio-inline m-r-20" for="type_">
											<input id="type_" name="search_type" type="radio" value="radiobutton" hidden="hidden" />
											<i class="input-helper"></i> 
										</label>
									</div>
								</td>
								<td class="p-b-5">
									<div class="p-t-5"><label for="nick" onclick="$('type_').checked = true">Тип сообщения</label></div>
								</td>
								<td class="p-b-5">
									<div class="col-sm-6 p-t-5 p-r-0 p-l-0">
										<select class="form-control" id="type" onmouseup="$('type_').checked = true">
											<option label="Сообщение" value="m">Сообщение</option>
											<option label="Предупреждение" value="w">Предупреждение</option>
											<option label="Ошибка" value="e">Ошибка</option>
										</select>
									</div>
								</td>
							</tr>
							
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
					{sb_button text="Поиск" onclick="search_log();" icon="<i class='zmdi zmdi-search'></i>" class="bgm-green btn-icon-text"  id="searchbtn" submit=false}
				</div>
