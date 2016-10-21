<form action="" method="post">
	<input type="hidden" name="cardGroup" value="card_form" />
	<div class="card">
		<div class="card-header">
		<h2>Ваучер <small>Активация ключа, с помощью которого происходит автоматическое добавление администратора.</small></h2>
		</div>

		{if $param == "0"}
		<div class="card-body card-padding">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group fg-line">
						<label>Ваучер</label>
						<input type="text" class="form-control input-mask" id="pay_v4" name="pay_v4" data-mask="0000-0000-0000-0000" placeholder="Пример: 2000 2098 0500 0188">
					</div>
					<form name="register" method="post">
						  <h3>Проверочный код</h3>
						  <img src="includes/captcha/captcha.php" /> -> 
						  <input type="text" name="kapcha" />
					</form>
				</div>
			</div>
		</div>
		{else}
		<div class="alert alert-info" role="alert">Ваш ваучер был успешно принят! Продолжайте заполнение всех полей и выбор сервера.<br />Активация срока и администраторских прав начнется при следующем шаге(активации).<br />Вы используете ваучер: <i><b>{$klu4ik}</b></i></div>
		<div class="form-horizontal" role="form">
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-0">
					<label for="user_login" class="col-sm-3 control-label text-right">{help_icon title="Логин" message="Введите логин для доступа к ВЕБ панели"} Логин</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="user_login" name="user_login" placeholder="Введите данные" />
						</div>
						<div id="name.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-0">
					<label for="password" class="col-sm-3 control-label text-right">{help_icon title="Пароль" message="Введите пароль для доступа к ВЕБ панели"} Пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" TABINDEX=1 class="form-control" id="password" name="password" placeholder="Введите данные" />
						</div>
						<div id="password.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-0">
					<label for="password2" class="col-sm-3 control-label text-right">{help_icon title="Пароль" message="Подтвердите пароль"} Пароль(подтверждение)</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" TABINDEX=1 class="form-control" id="password2" name="password2" placeholder="Введите данные" />
						</div>
						<div id="password2.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-0">
					<label for="user_steamid" class="col-sm-3 control-label text-right">{help_icon title="STEAM" message="Введите ваш реальный Steam ID для идентификации и доступа к панели через Steam API KEY"} STEAMID</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="user_steamid" name="user_steamid" placeholder="Введите данные" />
						</div>
						<div id="steam.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-0">
					<label for="user_email" class="col-sm-3 control-label text-right">{help_icon title="E-mail" message="Введите ваш почтовый ящик. Может понадобиться для восстановления пароля"} E-MAIL</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="user_email" name="user_email" placeholder="Введите данные" />
						</div>
						<div id="email.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-0">
					<label for="skype" class="col-sm-3 control-label text-right">{help_icon title="Skype" message="Введите ваш логин в Skype"} Skype</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="skype" name="skype" placeholder="Введите данные" />
						</div>
					</div>
				</div>
				<div class="form-group m-b-0">
					<label for="vk" class="col-sm-3 control-label text-right">{help_icon title="vk" message="Введите ИМЕННО ID, а не полную ссылку вашего профиля в VK!"} VK(ID)</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="vk" name="vk" placeholder="Введите данные" />
						</div>
					</div>
				</div>
				<div class="form-group m-b-0">
					<label class="col-sm-3 control-label text-right">{help_icon title="Срок" message="Текущий срок, который присвоен данному ваучеру."} Срок действия</label>
					<div class="col-sm-9 p-t-10">
						{$days}
					</div>
				</div>
				<div class="form-group m-b-0">
					<label class="col-sm-3 control-label text-right">{help_icon title="Группа" message="Ваша ВЕБ группа, при активации ваучера."} Группа(веб)</label>
					<div class="col-sm-9 p-t-10">
						{$gr_web}
					</div>
				</div>
				<div class="form-group m-b-0">
					<label class="col-sm-3 control-label text-right">{help_icon title="Группа" message="Ваша СЕРВЕРНАЯ группа, при активации ваучера."} Группа(сервер)</label>
					<div class="col-sm-9 p-t-10">
						{$gr_srv}
					</div>
				</div>
				{if $servers == ""}
					<div class="form-group m-b-0">
						<label class="col-sm-3 control-label text-right">Доступные сервера</label>
						<div class="col-sm-9">
							<div class="checkbox">
								<table width="100%" valign="left" id="group.details">
										{foreach from="$server_list" item="server"}
											<tr>
												<td>
													<div class="checkbox m-b-15">
														<label for="s_{$server.sid}">
															<input type="checkbox" name="servers[]" id="s_{$server.sid}" value="s{$server.sid}" hidden="hidden" />
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
				{/if}
			</div>
		</div>
		{$server_script}
		{/if}
		<div class="card-body card-padding text-center">
			{if $param == "0"}
				{sb_button text="Далее" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" id="pay_send" submit=true}
			{else}
				{sb_button text="Активировать" onclick="AddAdmin_pay();" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" id="pay_send_activ" submit=false}
			{/if}
		</div>
	</div>
</form>