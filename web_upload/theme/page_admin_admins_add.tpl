{if NOT $permission_addadmin}
	Доступ запрещен!
{else}
	<div class="card">
		<div class="form-horizontal" role="form" id="add-group">
			<div class="card-header">
				<h2>Добавление Администратора <small>Заполните информацию об администраторе в специальных полях.</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-5">
					<label for="adminname" class="col-sm-3 control-label">Логин</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="adminname" name="adminname" placeholder="Введите данные">
						</div>
						<div id="name.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="steam" class="col-sm-3 control-label">SteamID</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=2 class="form-control" id="steam" name="steam" placeholder="Введите данные">
						</div>
						<div id="steam.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="email" class="col-sm-3 control-label">E-Mail</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=3 class="form-control" id="email" name="email" placeholder="Введите данные">
						</div>
						<div id="email.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="password" class="col-sm-3 control-label">Пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" TABINDEX=4 class="form-control" id="password" name="password" placeholder="Введите данные">
						</div>
						<div id="password.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="password2" class="col-sm-3 control-label">Повторите пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" TABINDEX=5 class="form-control" id="password2" name="password2" placeholder="Введите данные">
						</div>
						<div id="password.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="a_useserverpass" class="col-sm-3 control-label">Пароль на сервер</label>
					<div class="col-sm-9 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="a_useserverpass" name="a_useserverpass" TABINDEX=6 onclick="$('a_serverpass').disabled = !$(this).checked;" hidden="hidden" /> 
							<label for="a_useserverpass" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label> Включить
						</div>
						<div class="fg-line">
							<input type="password" TABINDEX=7 class="form-control" id="a_serverpass" name="a_serverpass" placeholder="Введите данные(Не обязательно)" disabled>
						</div>
						<div id="a_serverpass.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="a_foreverperiod" class="col-sm-3 control-label">{help_icon title="Срок" message="На сколько дней выдавать права администратору."} Доступ</label>
					<div class="col-sm-9 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="a_foreverperiod" name="a_foreverperiod" TABINDEX=9 onclick="$('a_period').disabled = $(this).checked;" hidden="hidden" /> 
							<label for="a_foreverperiod" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label>Навсегда
						</div>
						<div class="fg-line">
							<input type="text" TABINDEX=8 class="form-control" id="a_period" name="a_period" value="30">
						</div>
						<div id="a_period.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="skype" class="col-sm-3 control-label">{help_icon title="Skype" message="Связь с админмистратором через Skype."} Skype</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=9 class="form-control" id="skype" name="skype" placeholder="Введите данные(Не обязательно)">
						</div>
						<div id="skype.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="comment" class="col-sm-3 control-label">{help_icon title="Коментарий" message="Напишите коментарий к администратору."} Комментарий</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<textarea TABINDEX=10 class="form-control p-t-5" id="comment" name="comment" rows="3" placeholder="Введите желаемый текст(Не обязательно). Включена поддержка html."></textarea>
						</div>
						<div id="comment.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="vk" class="col-sm-3 control-label">{help_icon title="ВКонтакте" message="Введите ID профиля, для генерации ссылки на страницу администратора в соцсети."} VK(ID)</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=10 class="form-control" id="vk" name="vk" placeholder="Введите данные(Не обязательно)">
						</div>
						<div id="vk.msg"></div>
					</div>
				</div>
			</div>
				
				
			<div class="card-header">
				<h2>Доступ к серверу(ам) <small>Выберите сервер или группу серверов, которые он будет администрировать..</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-0">
					<label class="col-sm-3 control-label">Доступные сервера</label>
					<div class="col-sm-9">
						<div class="checkbox">
							<table width="100%" valign="left" id="group.details">
								{foreach from="$group_list" item="group"}
									<tr>
										<td>
											<div class="checkbox m-b-15">
												<label for="g_{$group.gid}_g">
													<input type="checkbox" name="group[]" id="g_{$group.gid}_g" value="g{$group.gid}" hidden="hidden" />
													<i class="input-helper"></i> {$group.name}<b><i> (Группа сервера)</i></b></span>
												</label>
											</div>
										</td>
									</tr>
								{/foreach}
								{foreach from="$server_list" item="server"}
									<tr>
										<td>
											<div class="checkbox m-b-15">
												<label for="s_{$server.sid}_s">
													<input type="checkbox" name="servers[]" id="s_{$server.sid}_s" value="s{$server.sid}" hidden="hidden" />
													<i class="input-helper"></i> <span id="sa{$server.sid}"><i>Получение имени сервера... {$server.ip}:{$server.port}</i></span>
												</label>
											</div>
										</td>
									</tr>
								{/foreach}
							</table>
						</div>
					</div>
				</div>
			</div>

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
										<select class="selectpicker" TABINDEX=11 onchange="update_server()" name="serverg" id="serverg">
											<optgroup label="Основное">
												<option value="-2">Выберите серверную группу</option>
												<option value="-3">Нет разрешений</option>
												<option value="c">Выборочные права</option>
												<option value="n">Новая группа</option>
											</optgroup>
											<optgroup label="Группы">
												{foreach from="$server_admin_group_list" item="server_wg"}
													<option value='{$server_wg.id}'>{$server_wg.name}</option>
												{/foreach}
											</optgroup>
										</select>
									</div>
									<div id="server.msg" class="badentry"></div>
								</td>
							</tr>
							<tr>
								<td colspan="2" id="serverperm" valign="top" style="display: none;"></td>
							</tr>
							<tr>
								<td>
									<div class="col-xs-6 p-b-10" id="webgroup">
										<select TABINDEX=9 onchange="update_web()" name="webg" id="webg" class="selectpicker">
											<optgroup label="Основное">
												<option value="-2">Выберите ВЕБ группу</option>
												<option value="-3">Нет разрешений</option>
												<option value="c">Выборочные права</option>
												<option value="n">Новая группа</option>
											</optgroup>
											<optgroup label="Группы">
												{foreach from="$server_group_list" item="server_g"}
													<option value='{$server_g.gid}'>{$server_g.name}</option>
												{/foreach}
											</optgroup>
										</select>
									</div>
									<div id="web.msg" class="badentry"></div>
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
				{sb_button text="Добавить админа" onclick="ConvertSteamID_3to2('steam');ProcessAddAdmin();" icon="<i class='zmdi zmdi-account-add'></i>" class="bgm-green btn-icon-text" id="aadmin" submit=false}
				&nbsp;
				{sb_button text="Back" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
			</div>
        {$server_script}
		</div>
	</div>
{/if}
