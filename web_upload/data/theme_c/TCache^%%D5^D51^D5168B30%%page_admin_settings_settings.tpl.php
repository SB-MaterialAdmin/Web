<?php /* Smarty version 2.6.29, created on 2018-09-24 17:56:35
         compiled from page_admin_settings_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'help_icon', 'page_admin_settings_settings.tpl', 10, false),array('function', 'display_material_input', 'page_admin_settings_settings.tpl', 53, false),array('function', 'display_material_checkbox', 'page_admin_settings_settings.tpl', 126, false),array('function', 'sb_button', 'page_admin_settings_settings.tpl', 151, false),)), $this); ?>
<form action="" method="post">
	<input type="hidden" name="settingsGroup" value="mainsettings" />
	<div class="card" id="group.details">
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Главные настройки<small>За дополнительной информацией или помощью наведите курсор мыши на знак вопроса.</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-5">
					<label for="template_title" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Заголовок",'message' => "Задайте заголовок вкладки отображаемый в браузере."), $this);?>
 Заголовок</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="template_title" name="template_title" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['config_title']; ?>
" />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="template_logo" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Путь до логотипа",'message' => "Здесь вы можете указать путь до вашего логотипа."), $this);?>
 Путь до логотипа</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="template_logo" name="template_logo" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['config_logo']; ?>
" />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="config_password_minlength" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Длина пароля",'message' => "Задайте минимальное количество символов пароля."), $this);?>
 Минимальная длина пароля</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="config_password_minlength" name="config_password_minlength" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['config_min_password']; ?>
" />
						</div>
						<div id="minpasslength.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="config_dateformat" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Формат даты",'message' => "Задайте формат даты банов отображаемый в банлисте, при подробном просмотре бана, мутов, гагов."), $this);?>
 Формат даты в деталях</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="config_dateformat" name="config_dateformat" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['config_dateformat']; ?>
" />
						</div>
						<a href="http://www.php.net/date" target="_blank">См.: PHP date</a>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="config_dateformat2" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Формат даты",'message' => "Задайте формат даты банов, мутов, гагов, отображаемый в банлисте(в таблице). Не путайте с подробным просмотром времени бана, мутов, гагов!"), $this);?>
 Формат даты блокировок</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="config_dateformat2" name="config_dateformat2" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['config_dateformat_ver2']; ?>
" />
						</div>
						<a href="http://www.php.net/date" target="_blank">См.: PHP date</a>
					</div>
				</div>

				<?php echo materialdesign_input(array('name' => 'nulladmin_name','help_title' => "Имя неизвестного админа",'help_text' => "Здесь указывается имя администратора, которого нет в SourceBans, или консоли.",'placeholder' => 'CONSOLE','value' => $this->_tpl_vars['nulladmin_name']), $this);?>


				<div class="form-group m-b-5 form-inline">
					<label for="sel_timezoneoffset" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Часовой пояс",'message' => "Задайте часовой пояс."), $this);?>
 Часовой пояс</label>
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
				<div class="form-group m-b-5">
					<label for="config_debug" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Режим отладки",'message' => "Включить режим отладки."), $this);?>
 Режим отладки</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="config_debug">
								<input type="checkbox" name="config_debug" id="config_debug" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<?php echo materialdesign_checkbox(array('name' => 'footer_gendata','help_title' => "Время генерации",'help_text' => "Включает отображение времени генерации страницы и кол-во выполненных запросов к БД в 'подвале'."), $this);?>

				
				<div class="form-group m-b-5 form-inline">
					<label for="maintenance" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Обслуживание системы",'message' => "Выберите операцию для обслуживания системы, после чего, нажмите 'Выполнить'."), $this);?>
 Обслуживание системы</label>
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
							<option value="vouchers">Удалить все использованные ваучеры</option>
						</select>
					</div>
					<div class="col-sm-2 p-t-5">
						<?php echo smarty_function_sb_button(array('text' => "Выполнить",'icon' => "<i class='zmdi zmdi-check-all'></i>",'class' => "bgm-green btn-icon-text",'id' => 'asettings','onclick' => "xajax_Maintenance($('maintenance').value);",'submit' => false), $this);?>

					</div>
				</div>
			</div>
			
			<div class="card-header">
				<h2>Настройки приветствия</h2>
			</div>
			<div class="card-body card-padding p-b-0">
				
				<div class="form-group m-b-5">
					<label class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Текст приветствия",'message' => "Введите текст приветствия, отображаемого на главной странице."), $this);?>
 Приветствие</label>
					<div class="col-sm-9">
						<textarea TABINDEX=6 cols="80" rows="20" id="dash_intro_text" name="dash_intro_text" class="html-editor"><?php echo $this->_tpl_vars['config_dash_text']; ?>
</textarea>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="dash_nopopup" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Выключить всплывающие окна",'message' => "Установите этот флажок, чтобы отключить всплывающие подсказки и использовать прямые ссылки."), $this);?>
 Выключить всплывающие окна</label>
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
					<label for="enable_protest" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Включить протест банов",'message' => "Поставьте галку чтобы включить страницу протеста банов."), $this);?>
 Включить протест банов</label>
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
					<label for="admin_list_t" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Включить админлист",'message' => "Поставьте галку чтобы включить страницу с списком админов."), $this);?>
 Включить админлист</label>
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
					<label for="vay4_t" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Включить Ваучеры",'message' => "Поставьте галку чтобы включить систему ваучеров."), $this);?>
 Включить ваучеры</label>
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
					<label for="protest_emailonlyinvolved" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Только отправка e-mail",'message' => "Если включено - при протесте бана будет только отправляться e-mail админу."), $this);?>
 Только отправка e-mail</label>
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
					<label for="enable_submit" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Предложение бана",'message' => "Поставьте галку чтобы включить страницу запроса банов."), $this);?>
 Предложение бана</label>
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
					<label for="default_page" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Главная страница",'message' => "Выберите главную страницу при открытии SourceBans."), $this);?>
 Главная страница</label>
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
					<label for="block_home" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Блокировки",'message' => "Выберите отображение блокировок на главной странице."), $this);?>
 Блокировки на главной</label>
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
					<label for="banlist_bansperpage" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Банов на странице",'message' => "Выберите количество банов на страницу."), $this);?>
 Банов на странице</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="banlist_bansperpage" name="banlist_bansperpage" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['config_bans_per_page']; ?>
" />
						</div>
						<div id="bansperpage.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="banlist_hideadmname" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Скрыть имя админа",'message' => "Поставьте галку чтобы скрыть имя админа в деталях бана."), $this);?>
 Скрыть имя админа</label>
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
					<label for="banlist_nocountryfetch" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Не показывать страну",'message' => "Поставьте галку чтобы не показывать страну забаненного игрока."), $this);?>
Не показывать страну</label>
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
					<label for="banlist_hideplayerips" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Скрыть IP игрока",'message' => "Поставьте галку чтобы скрыть IP адрес игрока в деталях бана."), $this);?>
Скрыть IP игрока</label>
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
					<label for="banlist_bansperpage" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Свои причины бана",'message' => "Введите свои причины банов."), $this);?>
 Свои причины бана <a href="javascript:void(0)" onclick="MoreFields();" data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить поле" class="f-16 p-l-5"><i class="zmdi zmdi-plus-circle"></i></a></label>
					<div class="col-sm-9">
						<?php $_from = ($this->_tpl_vars['bans_customreason']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['creason']):
?>
							<div class="fg-line">
								<input type="text" TABINDEX=1 class="form-control" placeholder="Введите данные" name="bans_customreason[]" id="bans_customreason[]" value="<?php echo $this->_tpl_vars['creason']; ?>
" />
							</div>
						<?php endforeach; endif; unset($_from); ?>
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
				<?php echo materialdesign_checkbox(array('name' => 'smtp_enabled','help_title' => "Использовать SMTP",'help_text' => "Включает использование SMTP-почты вместо mail()"), $this);?>

				<div id='smtp'>
					<?php echo materialdesign_input(array('name' => 'smtp_host','help_title' => "Адрес почтового сервера",'help_text' => "Здесь указывается адрес до почтового SMTP-сервера.",'placeholder' => "ssl://smtp.yandex.ru",'value' => $this->_tpl_vars['smtp_host']), $this);?>

					<?php echo materialdesign_input(array('name' => 'smtp_port','help_title' => "Порт почтового сервера",'help_text' => "Здесь указываете порт SMTP-сервера. Порт можно узнать из справочной системы вашего почтового сервиса.",'placeholder' => '465','value' => $this->_tpl_vars['smtp_port']), $this);?>

					<?php echo materialdesign_input(array('name' => 'smtp_username','help_title' => "Логин почтового аккаунта",'help_text' => "Введите имя своего почтового ящика",'placeholder' => "primer@yandex.ru",'value' => $this->_tpl_vars['smtp_username']), $this);?>

					<?php echo materialdesign_input(array('name' => 'smtp_password','help_title' => "Пароль от почтового аккаунта",'help_text' => "Укажите пароль от своего почтового аккаунта на указанном SMTP-сервере.",'value' => "*Скрыт*",'placeholder' => ""), $this);?>

					<?php echo materialdesign_input(array('name' => 'smtp_charset','help_title' => "Кодировка сообщений",'help_text' => "Укажите кодировку сообщений. Как правило, UTF-8 или Windows-1251",'placeholder' => "UTF-8",'value' => $this->_tpl_vars['smtp_charset']), $this);?>

					<?php echo materialdesign_input(array('name' => 'smtp_from','help_title' => "Имя отправителя",'help_text' => "Введите в это поле, от чьего имени письма будут помечаться. Отображается при прочтении в поле 'От кого'.",'placeholder' => "[SourceBans] SMTP-sender",'value' => $this->_tpl_vars['smtp_from']), $this);?>

				</div>
				<!-- SMTP settings  end  -->
			</div>
			
            <div class="card-header">
				<h2>Game Cache</h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<p>Кеширование ответов от игрового сервера позволяет избавиться от лишней нагрузки на порт игрового сервера, но так же может привести к проблемам неактуальных данных в веб-панели.<br />Все ответы сохраняются на ФС веб-сервера, в папке <em>data/gc</em><br /><b>Рекомендуемое значение для хранения ответов</b>: 30</p>
				
				<?php echo materialdesign_checkbox(array('name' => 'gc_enabled','help_title' => "Использовать GC",'help_text' => "Включает использование системы кеширования ответов"), $this);?>

                <?php echo materialdesign_input(array('name' => 'gc_entrylf','help_title' => "Время жизни кеша",'help_text' => "Время жизни записи кеша (в секундах)",'placeholder' => "Рекомендуемое значение: 30",'value' => $this->_tpl_vars['gc_entrylf']), $this);?>

			</div>
            
			<div class="card-body card-padding text-center">
				<?php echo smarty_function_sb_button(array('text' => "Сохранить",'icon' => "<i class='zmdi zmdi-check-all'></i>",'class' => "bgm-green btn-icon-text",'id' => 'asettings','submit' => true), $this);?>

				&nbsp;
				<?php echo smarty_function_sb_button(array('text' => "Назад",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'aback'), $this);?>

			</div>
		</div>
	</div>
		
</form>
<script>$('sel_timezoneoffset').value = "<?php echo $this->_tpl_vars['config_time']; ?>
";
<?php if ($this->_tpl_vars['smtp_enabled']): ?>$('smtp_enabled').checked = true;<?php endif; ?>
<?php if ($this->_tpl_vars['gc_enabled']): ?>$('gc_enabled').checked = true;<?php endif; ?>
<?php echo '
$(\'smtp_enabled\').onclick = function() {
	if ($(\'smtp_enabled\').checked == false)
		$(\'smtp\').style.display = "none";
	else
		$(\'smtp\').style.display = "";
}
'; ?>


$('smtp_enabled').onclick();
</script>