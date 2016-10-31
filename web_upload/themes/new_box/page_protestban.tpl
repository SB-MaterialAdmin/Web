<form action="index.php?p=protest" method="post">
	<input type="hidden" name="subprotest" value="1">
	<div class="card">
		<div class="form-horizontal" role="form" id="add-group">
			<div class="card-header">
				<h2>Апелляция бана 
					<small>
						<u>Что произойдёт когда я отправлю апелляцию бана?</u><br />
						Администрация будет уведомлена о Вашем протесте. После они обязательно проверят детали и обстоятельства бана. Обычно срок рассмотрения заявки 24 часа, но помните, что для каждой заявки срок индивидуален.<br />
						<u>Примечание:</u> Отправление Администрации заявок с угрозами или мольбами о разбане приведут лишь к удалению Вашей заявки. Уважайте труд и время Администрации!
					</small>
				</h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-5">
					<label class="col-sm-3 control-label">Детали заявки:</label>
					<div class="col-sm-9">
						<div class="col-xs-6 p-l-0" id="webgroup">
							<select onChange="changeType(this[this.selectedIndex].value);" id="Type" name="Type" class="selectpicker">
								<optgroup label="Тип">
									<option value="0">Steam ID</option>
									<option value="1">IP Адрес</option>
								</optgroup>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5" id="steam.row">
					<label for="SteamID" class="col-sm-3 control-label">Ваш SteamID</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" size="40" class="form-control" maxlength="64" value="{$steam_id}" name="SteamID" placeholder="Введите данные(обязательное поле)">
						</div>
					</div>
				</div>
				<div class="form-group m-b-5" id="ip.row" style="display: none;">
					<label for="IP" class="col-sm-3 control-label">Ваш IP</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" size="40" class="form-control" maxlength="64" value="{$ip}" name="IP" placeholder="Введите данные(обязательное поле)">
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="PlayerName" class="col-sm-3 control-label">Ник</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" size="40" class="form-control" maxlength="70" value="{$player_name}" name="PlayerName" placeholder="Введите данные(обязательное поле)">
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="BanReason" class="col-sm-3 control-label">Причина</label>
					<div class="col-sm-9 p-t-10">
						<div class="fg-line">
							<textarea name="BanReason" cols="30" rows="5" class="form-control p-t-5" placeholder="От информативности/убедительности зависит Ваш разбан(обязательное поле)" >{$reason}</textarea>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="EmailAddr" class="col-sm-3 control-label">Ваш Email</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" size="40" class="form-control" maxlength="70" value="{$player_email}" name="EmailAddr" placeholder="Введите данные(обязательное поле)">
						</div>
					</div>
				</div>
			</div>
			
			<div class="card-body card-padding p-t-10 p-b-10">
				Перед тем как продолжить, проверьте <a href="index.php?p=banlist">банлист</a> на наличие Вашего бана.
				Если Вы считаете, что бан выдан ложно, то тогда можете написать протест.
			</div>
			
			<div class="card-body card-padding text-center">
				{sb_button text="Отправить" class="bgm-green btn-icon-text" id=alogin icon="<i class='zmdi zmdi-account-add'></i>" submit=true}
			</div>
		</div>
	</div>
</form>
