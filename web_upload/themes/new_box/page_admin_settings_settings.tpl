<form action="" method="post">
	<input type="hidden" name="settingsGroup" value="mainsettings" />
	<div class="card" id="group.details">
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Главные настройки<small>За дополнительной информацией или помощью наведите курсор мыши на знак вопроса.</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-5">
					<label for="template_title" class="col-sm-3 control-label">{help_icon title="Заголовок" message="Задайте заголовок вкладки отображаемый в браузере."} Заголовок</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="template_title" name="template_title" placeholder="Введите данные" value="{$config_title}" />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="template_logo" class="col-sm-3 control-label">{help_icon title="Путь до логотипа" message="Здесь вы можете указать путь до вашего логотипа."} Путь до логотипа</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="template_logo" name="template_logo" placeholder="Введите данные" value="{$config_logo}" />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="config_password_minlength" class="col-sm-3 control-label">{help_icon title="Длина пароля" message="Задайте минимальное количество символов пароля."} Минимальная длина пароля</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="config_password_minlength" name="config_password_minlength" placeholder="Введите данные" value="{$config_min_password}" />
						</div>
						<div id="minpasslength.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="config_dateformat" class="col-sm-3 control-label">{help_icon title="Формат даты" message="Задайте формат даты банов отображаемый в банлисте, при подробном просмотре бана, мутов, гагов."} Формат даты в деталях</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="config_dateformat" name="config_dateformat" placeholder="Введите данные" value="{$config_dateformat}" />
						</div>
						<a href="http://www.php.net/date" target="_blank">См.: PHP date</a>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="config_dateformat2" class="col-sm-3 control-label">{help_icon title="Формат даты" message="Задайте формат даты банов, мутов, гагов, отображаемый в банлисте(в таблице). Не путайте с подробным просмотром времени бана, мутов, гагов!"} Формат даты блокировок</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="config_dateformat2" name="config_dateformat2" placeholder="Введите данные" value="{$config_dateformat_ver2}" />
						</div>
						<a href="http://www.php.net/date" target="_blank">См.: PHP date</a>
					</div>
				</div>
				<div class="form-group m-b-5 form-inline">
					<label for="sel_timezoneoffset" class="col-sm-3 control-label">{help_icon title="Часовой пояс" message="Задайте часовой пояс."} Часовой пояс</label>
					<div class="col-sm-5 p-t-5">
						<select class="selectpicker" name="timezoneoffset" id="sel_timezoneoffset">
							<option value="-12" class="">(GMT -12:00) Eniwetok, Kwajalein</option>
						
							<option value="-11" id="-39600" class="" >(GMT -11:00) Midway Island, Samoa</option>
							<option value="-10" id="-36000" class="">(GMT -10:00) Hawaii</option>
							<option value="-9" class="">(GMT -9:00) Alaska</option>
							<option value="-8" class="">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
							<option value="-7" class="">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
							<option value="-6" class="">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
							
							<option value="-5" class="">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
							<option value="-4" class="">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
							<option value="-3.5" class="">(GMT -3:30) Newfoundland</option>
							<option value="-3" class="">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
							<option value="-2" class="">(GMT -2:00) Mid-Atlantic</option>
							<option value="-1" class="">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
							<option value="0" class="">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
							<option value="1" class="">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
							
							<option value="2" class="">(GMT +2:00) Kaliningrad, South Africa</option>
							<option value="3" class="">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
							<option value="3.5" class="">(GMT +3:30) Tehran</option>
							<option value="4" class="">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
							<option value="4.5" class="">(GMT +4:30) Kabul</option>
							<option value="5" class="">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
							<option value="5.5" class="">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
							<option value="6" class="">(GMT +6:00) Almaty, Dhaka, Colombo</option>
							<option value="7" class="">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
							
							<option value="8" class="">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
							<option value="9" class="">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
							<option value="9.5" class="">(GMT +9:30) Adelaide, Darwin</option>
							<option value="10" class="">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
							<option value="11" class="">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
							<option value="12" class="">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
						</select>
					</div>
					<div class="col-sm-4 p-t-5">
						<div class="checkbox m-b-15">
							<label for="config_summertime">
								<input type="checkbox" name="config_summertime" id="config_summertime" hidden="hidden" />
								<i class="input-helper"></i> Летнее время
							</label>
						</div>
					</div>
				</div>
{*				<div class="form-group m-b-5">
					<label for="config_summertime" class="col-sm-3 control-label">{help_icon title="Летнее время" message="Включить автоматический переход на летнее время."} Летнее время</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="config_summertime">
								<input type="checkbox" name="config_summertime" id="config_summertime" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>*}
				<div class="form-group m-b-5">
					<label for="config_debug" class="col-sm-3 control-label">{help_icon title="Режим отладки" message="Включить режим отладки."} Режим отладки</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="config_debug">
								<input type="checkbox" name="config_debug" id="config_debug" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				{display_material_checkbox name="footer_gendata" help_title="Время генерации" help_text="Включает отображение времени генерации страницы и кол-во выполненных запросов к БД в 'подвале'."}
				
				<div class="form-group m-b-5 form-inline">
					<label for="maintenance" class="col-sm-3 control-label">{help_icon title="Обслуживание системы" message="Выберите операцию для обслуживания системы, после чего, нажмите 'Выполнить'."} Обслуживание системы</label>
					<div class="col-sm-4 p-t-5">
						<select class="selectpicker" name="maintenance" id="maintenance">
							<option value="themecache">Очистить кеш шаблона</option>
							<option value="avatarcache">Очистить кеш аватарок</option>
							<option value="cleancountrycache">Очистить кеш стран банлиста</option>
							<option value="bansexpired">Удалить истёкшие баны</option>
							<option value="commsexpired">Удалить истёкшие блокировки чата</option>
							<option value="adminsexpired">Удалить истёкших Администраторов</option>
							<option value="commentsclean">Удалить все комментарии</option>
							<option value="protests">Удалить все протесты банов</option>
							<option value="reports">Удалить все предложения бана (репорты)</option>
							<option value="banlogclean">Очистить историю заблокированных соединений</option>
							<option value="warningsexpired">Удалить истёкшие Предупреждения</option>
							<option value="rehashcountries">Обновить кеш стран в банлисте</option>
							<option value="updatecountries">Обновить файл GeoIP</option>
							<option value="optimizebd">Произвести оптимизацию БД</option>
							<option value="avatarupdate">Пересобрать кеш аватарок</option>
						</select>
					</div>
					<div class="col-sm-2 p-t-5">
						{sb_button text="Выполнить" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" id="asettings" onclick="xajax_Maintenance($('maintenance').value);" submit=false}
					</div>
				</div>
			</div>
			
			<div class="card-header">
				<h2>Настройки приветствия</h2>
			</div>
			<div class="card-body card-padding p-b-0">
				
				<div class="form-group m-b-5">
					<label class="col-sm-3 control-label">{help_icon title="Текст приветствия" message="Введите текст приветствия, отображаемого на главной странице."} Приветствие</label>
					<div class="col-sm-9">
						<textarea TABINDEX=6 cols="80" rows="20" id="dash_intro_text" name="dash_intro_text" class="html-editor">{$config_dash_text}</textarea>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="dash_nopopup" class="col-sm-3 control-label">{help_icon title="Выключить всплывающие окна" message="Установите этот флажок, чтобы отключить всплывающие подсказки и использовать прямые ссылки."} Выключить всплывающие окна</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="dash_nopopup">
								<input type="checkbox" name="dash_nopopup" id="dash_nopopup" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				
			</div>
			
			
			<div class="card-header">
				<h2>Настройки страницы</h2>
			</div>
			<div class="card-body card-padding p-b-0">
				
				<div class="form-group m-b-5">
					<label for="enable_protest" class="col-sm-3 control-label">{help_icon title="Включить протест банов" message="Поставьте галку чтобы включить страницу протеста банов."} Включить протест банов</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_protest">
								<input type="checkbox" name="enable_protest" id="enable_protest" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="admin_list_t" class="col-sm-3 control-label">{help_icon title="Включить админлист" message="Поставьте галку чтобы включить страницу с списком админов."} Включить админлист</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="admin_list_t">
								<input type="checkbox" name="admin_list_t" id="admin_list_t" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="vay4_t" class="col-sm-3 control-label">{help_icon title="Включить Ваучеры" message="Поставьте галку чтобы включить систему ваучеров."} Включить ваучеры</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="vay4_t">
								<input type="checkbox" name="vay4_t" id="vay4_t" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="protest_emailonlyinvolved" class="col-sm-3 control-label">{help_icon title="Только отправка e-mail" message="Если включено - при протесте бана будет только отправляться e-mail админу."} Только отправка e-mail</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="protest_emailonlyinvolved">
								<input type="checkbox" name="protest_emailonlyinvolved" id="protest_emailonlyinvolved" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_submit" class="col-sm-3 control-label">{help_icon title="Предложение бана" message="Поставьте галку чтобы включить страницу запроса банов."} Предложение бана</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_submit">
								<input type="checkbox" name="enable_submit" id="enable_submit" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="default_page" class="col-sm-3 control-label">{help_icon title="Главная страница" message="Выберите главную страницу при открытии SourceBans."} Главная страница</label>
					<div class="col-sm-3 p-t-5">
						<select class="selectpicker" name="default_page" id="default_page">
							<option value="0">Главная</option>
							<option value="1">Банлист</option>
							<option value="2">Серверы</option>
							<option value="3">Предложить бан</option>
							<option value="4">Протест бана</option>
						</select>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="block_home" class="col-sm-3 control-label">{help_icon title="Блокировки" message="Выберите отображение блокировок на главной странице."} Блокировки на главной</label>
					<div class="col-sm-3 p-t-5">
						<select class="selectpicker" name="block_home" id="block_home">
							<option value="0">Скрыть все</option>
							<option value="1">Только Баны</option>
							<option value="2">Только Муты/Гаги</option>
							<option value="3">Показывать все</option>
						</select>
					</div>
				</div>
				
			</div>
			
			
			<div class="card-header">
				<h2>Настройки банлиста</h2>
			</div>
			<div class="card-body card-padding p-b-0">
				
				<div class="form-group m-b-5">
					<label for="banlist_bansperpage" class="col-sm-3 control-label">{help_icon title="Банов на странице" message="Выберите количество банов на страницу."} Банов на странице</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="banlist_bansperpage" name="banlist_bansperpage" placeholder="Введите данные" value="{$config_bans_per_page}" />
						</div>
						<div id="bansperpage.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="banlist_hideadmname" class="col-sm-3 control-label">{help_icon title="Скрыть имя админа" message="Поставьте галку чтобы скрыть имя админа в деталях бана."} Скрыть имя админа</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="banlist_hideadmname">
								<input type="checkbox" name="banlist_hideadmname" id="banlist_hideadmname" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
						<div id="banlist_hideadmname.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="banlist_nocountryfetch" class="col-sm-3 control-label">{help_icon title="Не показывать страну" message="Поставьте галку чтобы не показывать страну забаненного игрока."}Не показывать страну</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="banlist_nocountryfetch">
								<input type="checkbox" name="banlist_nocountryfetch" id="banlist_nocountryfetch" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
						<div id="banlist_nocountryfetch.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="banlist_hideplayerips" class="col-sm-3 control-label">{help_icon title="Скрыть IP игрока" message="Поставьте галку чтобы скрыть IP адрес игрока в деталях бана."}Скрыть IP игрока</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="banlist_hideplayerips">
								<input type="checkbox" name="banlist_hideplayerips" id="banlist_hideplayerips" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
						<div id="banlist_hideplayerips.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="banlist_bansperpage" class="col-sm-3 control-label">{help_icon title="Свои причины бана" message="Введите свои причины банов."} Свои причины бана <a href="javascript:void(0)" onclick="MoreFields();" data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить поле" class="f-16 p-l-5"><i class="zmdi zmdi-plus-circle"></i></a></label>
					<div class="col-sm-9">
						{foreach from="$bans_customreason" item="creason"}
							<div class="fg-line">
								<input type="text" TABINDEX=1 class="form-control" placeholder="Введите данные" name="bans_customreason[]" id="bans_customreason[]" value="{$creason}" />
							</div>
						{/foreach}
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" placeholder="Введите данные" name="bans_customreason[]" id="bans_customreason[]" />
						</div>
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" placeholder="Введите данные" name="bans_customreason[]" id="bans_customreason[]" />
						</div>
						<div id="custom.reasons" name="custom.reasons">
						
						</div>
						<div id="bans_customreason.msg"></div>
					</div>
				</div>
				
			</div>
			
			<div class="card-header">
				<h2>Настройки отправки почты</h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<p><span style="color: red;">Обратите внимание!</span><br />Если функция <i>mail()</i> на Вашем веб-сервере не настроена, то <strong>рекомендуется</strong> включить и настроить SMTP для работоспособной отправки писем от системы.</p>
				
				<!-- SMTP settings start -->
				{display_material_checkbox name="smtp_enabled" help_title="Использовать SMTP" help_text="Включает использование SMTP-почты вместо mail()"}
				<div id='smtp'>
					{display_material_input name="smtp_host" help_title="Адрес почтового сервера" help_text="Здесь указывается адрес до почтового SMTP-сервера." placeholder="ssl://smtp.yandex.ru" value=$smtp_host}
					{display_material_input name="smtp_port" help_title="Порт почтового сервера" help_text="Здесь указываете порт SMTP-сервера. Порт можно узнать из справочной системы вашего почтового сервиса." placeholder="465" value=$smtp_port}
					{display_material_input name="smtp_username" help_title="Логин почтового аккаунта" help_text="Введите имя своего почтового ящика" placeholder="primer@yandex.ru" value=$smtp_username}
					{display_material_input name="smtp_password" help_title="Пароль от почтового аккаунта" help_text="Укажите пароль от своего почтового аккаунта на указанном SMTP-сервере." value="*Скрыт*" placeholder=""}
					{display_material_input name="smtp_charset" help_title="Кодировка сообщений" help_text="Укажите кодировку сообщений. Как правило, UTF-8 или Windows-1251" placeholder="UTF-8" value=$smtp_charset}
					{display_material_input name="smtp_from" help_title="Имя отправителя" help_text="Введите в это поле, от чьего имени письма будут помечаться. Отображается при прочтении в поле 'От кого'." placeholder="[SourceBans] SMTP-sender" value=$smtp_from}
				</div>
				<!-- SMTP settings  end  -->
			</div>
			
			<div class="card-body card-padding text-center">
				{sb_button text="Сохранить" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" id="asettings" submit=true}
				&nbsp;
				{sb_button text="Назад" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
			</div>
		</div>
	</div>
		
</form>
<script>$('sel_timezoneoffset').value = "{$config_time}";
{if $smtp_enabled}$('smtp_enabled').checked = true;{/if}
{literal}
$('smtp_enabled').onclick = function() {
	if ($('smtp_enabled').checked == false)
		$('smtp').style.display = "none";
	else
		$('smtp').style.display = "";
}
{/literal}

$('smtp_enabled').onclick();
</script>
