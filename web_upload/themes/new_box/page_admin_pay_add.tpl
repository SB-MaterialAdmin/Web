<form action="" method="post">
	<input type="hidden" name="pay_card_admin" value="pay_card_add" />	
	<input type="hidden" id="card_gr_web" name="card_gr_web" />
	<input type="hidden" id="card_gr_srv" name="card_gr_srv" />
	<input type="hidden" id="srv_check_int" name="srv_check_int" />
	<div class="card">
		<div class="card-header">
		<h2>Ваучеры <small>Ваучер - ключ, с помощью которого происходит автоматическое добавление администратора при активации.</small></h2>
		</div>
		
		<div class="alert alert-info" role="alert">Перед заполнением форм, наведите на знак <img border="0" align="absbottom" src="images/help.png" /> , для полного ознакомления с Системой Ваучеров!</div>
		<div class="form-horizontal" role="form">
		<div class="card-body card-padding p-b-0">
			<div class="form-group m-b-5">
				<label for="card_key" class="col-sm-3 control-label text-right">{help_icon title="Ключ" message="Максимальная длинна символов ключа: 16 символов. Поддерживаются только цифры"} Ключ</label>
				<div class="col-xs-4">
					<div class="fg-line">
						<input type="text" TABINDEX=1 class="form-control input-mask" id="card_key" name="card_key" data-mask="0000000000000000" placeholder="Пример: 2000209805000188" />
					</div>
				</div>
                    <button type="button" class="btn btn-primary waves-effect m-t-5 btn-icon-text" onclick="$('card_key').value = getPassword(16);"><i class="zmdi zmdi-refresh-alt"></i> Cгенерировать</button>
			</div>
			<div class="form-group m-b-5">
				<label for="card_exp" class="col-sm-3 control-label text-right">{help_icon title="Срок" message="Введите '0', чтобы активировать срок - навсегда. Срок указывается в днях."} Срок админки</label>
				<div class="col-xs-4">
					<div class="fg-line">
						<input type="text" TABINDEX=1 class="form-control" id="card_exp" name="card_exp" placeholder="Введите данные" />
					</div>
				</div>
			</div>
			<div class="form-group m-b-5">
				<label class="col-sm-3 control-label text-right">{help_icon title="Группа" message="Выберите группу. Данная группа будет выдана админу, который активирует данный ваучер."} Группа(сервер)</label>
				<div class="col-sm-9 p-t-10">
						<table>
								{foreach from="$server_admin_group_list" item="server_wg"}
								<tr>
										<td>
											<label for="dd_{$server_wg.id}_dd" class="radio radio-inline m-r-20 p-t-0">
												<input type="radio" value="{$server_wg.name}" id="dd_{$server_wg.id}_dd" name="inlineRadioOptions_srv" hidden="hidden" onchange="$('card_gr_srv').value = this.value;" />
												<i class="input-helper"></i> {$server_wg.name}
											</label>
										</td>
								</tr>
								{/foreach}
								<tr>
										<td>
											<label for="no_group_web" class="radio radio-inline m-r-20 p-t-0">
												<input type="radio" value="" id="no_group_web" name="inlineRadioOptions_srv" hidden="hidden" onchange="$('card_gr_srv').value = this.value;" />
												<i class="input-helper"></i> <span class="c-red">Без группы</span>
											</label>
										</td>
								</tr>
						</table>
				</div>
			</div>
			<div class="form-group m-b-5">
				<label class="col-sm-3 control-label text-right">{help_icon title="Группа" message="Выберите группу. Данная группа будет выдана админу, который активирует данный ваучер."} Группа(веб)</label>
				<div class="col-sm-9 p-t-10">
						<table>
								{foreach from="$server_group_list" item="server_g"}
								<tr>
										<td>
											<label for="dp_{$server_g.gid}_pd" class="radio radio-inline m-r-20 p-t-0">
												<input type="radio" value="{$server_g.name}" id="dp_{$server_g.gid}_pd" name="inlineRadioOptions_web" hidden="hidden" onchange="$('card_gr_web').value = this.value;" />
												<i class="input-helper"></i> {$server_g.name}
											</label>
										</td>
								</tr>
								{/foreach}
								<tr>
									<td>
										<label for="no_group_srv" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="0" id="no_group_srv" name="inlineRadioOptions_web" hidden="hidden" onchange="$('card_gr_web').value = this.value;" />
											<i class="input-helper"></i> <span class="c-red">Без группы</span>
										</label>
									</td>
								</tr>
						</table>
				</div>
			</div>
			<div class="form-group m-b-5">
				<label class="col-sm-3 control-label text-right">{help_icon title="Сервер" message="Выберите сервер(а), на котором администратор будет иметь права. Если никакой сервер не будет выбран(включая 'Без сервера'), пользователь сам сможет выбрать себе сервер при активации Ваучера."} Сервер</label>
				<div class="col-sm-9 p-t-5">
						<table>
								{foreach from="$server_list" item="server"}
									<tr>
										<td>
											<div class="checkbox">
												<label for="s_{$server.sid}_s">
													<input type="checkbox" name="servers[]" id="s_{$server.sid}_s" value="s{$server.sid}" hidden="hidden" onchange="Check_cal();" />
													<i class="input-helper"></i> <span id="sa{$server.sid}"><i>Получение имени сервера... {$server.ip}:{$server.port}</i></span>
												</label>
											</div>
										</td>
									</tr>
								{/foreach}
								<tr>
									<td>
										<div class="checkbox">
											<label for="s_no_srv">
												<input type="checkbox" name="servers[]" id="s_no_srv" name="s_no_srv" value="-1" hidden="hidden" onchange="Check_cal();" />
												<i class="input-helper"></i> <span class="c-red">Без сервера</span>
											</label>
										</div>
									</td>
								</tr>
						</table>
				</div>
			</div>
		</div>
		</div>
		<div class="card-body card-padding text-center">
			{sb_button text="Добавить" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" id="pay_key_send" submit=true}
			
			{sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
		</div>
	</div>
</form>
{$server_script}