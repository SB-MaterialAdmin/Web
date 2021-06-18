{if NOT $permission_addban}
	Доступ запрещен!
{else}


<div class="card">
	<div class="form-horizontal" role="form" id="add-group1">
		<div class="card-header">
			<h2>Добавление блокировки коммуникаций <small>За дополнительной информацией или помощью наведите курсор мыши на знак вопроса.</small></h2>
		</div>
		<div class="card-body card-padding p-b-0" id="group.details">
			<div class="form-group m-b-5">
				<label for="nickname" class="col-sm-3 control-label">{help_icon title="Ник" message="Введите ник игрока, которому дать блокировку."} Ник</label>
				<div class="col-sm-9">
					<div class="fg-line">
						<input type="hidden" id="fromsub" value="" />
						<input type="text" TABINDEX=1 class="form-control" id="nickname" name="nickname" placeholder="Введите данные">
					</div>
					<div id="nick.msg"></div>
				</div>
			</div>
			
			<div class="form-group m-b-5">
				<label for="steam" class="col-sm-3 control-label">{help_icon title="Steam ID / Community ID" message="Steam ID или Community ID игрока, которому дать блокировку."} Steam ID / Community ID</label>
				<div class="col-sm-9">
					<div class="fg-line">
						<input type="text" TABINDEX=1 class="form-control" id="steam" name="steam" placeholder="Введите данные">
					</div>
					<div id="steam.msg"></div>
				</div>
			</div>
			
			<div class="form-group m-b-5">
				<label for="type" class="col-sm-3 control-label">{help_icon title="Тип блокировки" message="Выберите что блокировать-чат или микрофон"}Тип блокировки </label>
				<div class="col-sm-2">
					<select class="selectpicker" id="type" name="type">
						<option value="1">Микрофон</option>
                        <option value="2">Чат</option>
                        <option value="3">Чат &amp; Микро</option>
					</select>
				</div>
			</div>
			
			<div class="form-group m-b-5">
				<label for="listReason" class="col-sm-3 control-label">{help_icon title="Причина блокировки" message="Выберите причину, по которой хотите произвести блокировку."} Причина блокировки</label>
				<div class="col-sm-6" id="dreason" style="display:none;">
					<div class="fg-line">
						<input type="text" TABINDEX=4 class="form-control" id="txtReason" name="txtReason" placeholder="Напишите свою причину бана...">
					</div>
				</div>
				<div class="col-sm-3 p-t-5">
					<select class="selectpicker" id="listReason" name="listReason" onChange="changeReason(this[this.selectedIndex].value);">
						<option value="" selected> -- Выберите причину -- </option>
						<optgroup label="Нарушение">
							<option value="Непристойное общение">Непристойное общение</option>
							<option value="Оскорбление игроков">Оскорбление игроков</option>
							<option value="Неуважение администрации">Неуважение администрации</option>
							<option value="Неприемлимое общение">Неприемлимое общение</option>
							<option value="Торговля">Торговля</option>
							<option value="Спам">Спам</option>
							<option value="Реклама">Реклама</option>
						</optgroup>
						<option value="other">Своя причина</option>
					</select>
				</div>
				<div id="reason.msg"></div>
			</div>
			
			<div class="form-group m-b-5">
				<label for="banlength" class="col-sm-3 control-label">{help_icon title="Срок блокировки" message="Выберите, на какой срок Вы хотите его заблокировать."} Срок блока</label>
				<div class="col-sm-3">
					<select id="banlength" TABINDEX=4 class="selectpicker">
						<option value="0">Навсегда</option>
						<optgroup label="Минуты">
						  <option value="1">1 минута</option>
						  <option value="5">5 минут</option>
						  <option value="10">10 минут</option>
						  <option value="15">15 минут</option>
						  <option value="30">30 минут</option>
						  <option value="45">45 минут</option>
						</optgroup>
						<optgroup label="Часы">
							<option value="60">1 час</option>
							<option value="120">2 часа</option>
							<option value="180">3 часа</option>
							<option value="240">4 часа</option>
							<option value="480">8 часов</option>
							<option value="720">12 часов</option>
						</optgroup>
						<optgroup label="Дни">
						  <option value="1440">1 день</option>
						  <option value="2880">2 дня</option>
						  <option value="4320">3 дня</option>
						  <option value="5760">4 дня</option>
						  <option value="7200">5 дней</option>
						  <option value="8640">6 дней</option>
						</optgroup>
						<optgroup label="Недели">
						  <option value="10080">1 неделя</option>
						  <option value="20160">2 недели</option>
						  <option value="30240">3 недели</option>
						</optgroup>
						<optgroup label="Месяцы">
						  <option value="43200">1 месяц</option>
						  <option value="86400">2 месяца</option>
						  <option value="129600">3 месяца</option>
						  <option value="259200">6 месяцев</option>
						  <option value="518400">12 месяцев</option>
						</optgroup>
					</select>
				</div>
				<div id="length.msg"></div>
			</div>
			
		</div>
		<div class="card-body card-padding text-center">
			{sb_button text="Добавить блокировку" onclick="ConvertSteamID_3to2('steam');ProcessBan();" icon="<i class='zmdi zmdi-shield-security'></i>" class="bgm-green btn-icon-text" id="aban" submit=false}
				  &nbsp;
			{sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
		</div>
	</div>
</div>

{/if}
