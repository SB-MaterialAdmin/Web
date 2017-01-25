<form action="" method="post" id="theme_form">
	<input type="hidden" name="settingsGroup" value="mainsettings_themes" />
	<div class="card">
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Управление шаблоном <small>Выберите подходящую для вас настройку данного шаблона.</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
                <div class="form-group m-b-5">
					<label for="splashscreen" class="col-sm-3 control-label">{help_icon title="Экран загрузки" message="Позволяет включать и выключать экран загрузки с надписью 'Please wait...'"} Экран загрузки</label>
					<div class="col-sm-9 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="splashscreen" name="splashscreen" hidden="hidden" /> 
							<label for="splashscreen" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label> Включить?
						</div>
					</div>
				</div>
				
				<div class="form-group m-b-5">
					<label for="home_stats" class="col-sm-3 control-label">{help_icon title="Статистика проекта" message="Позволяет включать и выключать статистику проекта (кол-во админов, серверов, банов, мутов) на главной"} Статистика</label>
					<div class="col-sm-9 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="home_stats" name="home_stats" hidden="hidden" /> 
							<label for="home_stats" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label> Включить?
						</div>
					</div>
				</div>
			
				<div class="form-group m-b-5">
					<label for="global_themes_t" class="col-sm-3 control-label">{help_icon title="Режимы" message="Позволяет переключаться между минимальным(скрытие главного меню) и обычным(всегда открытое главное меню) шаблоном. При минимальном режиме, пользователь сам может изменять себе вид переключателем справа сверху."} Режим</label>
					<div class="col-sm-9 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="global_themes_t" name="global_themes_t" hidden="hidden" /> 
							<label for="global_themes_t" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label> Полная поддержка?
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="xleb_kroxi_t" class="col-sm-3 control-label">{help_icon title="Хлебные крошки" message="Позволяет отключать панель навигации 'Главная/Админ-Центр'"} Хлебные крошки</label>
					<div class="col-sm-9 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="xleb_kroxi_t" name="xleb_kroxi_t" hidden="hidden" /> 
							<label for="xleb_kroxi_t" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label> Включить?
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label class="col-sm-3 control-label">{help_icon title="Цвет шапки" message="Позволяет выбрать подходящий на ваш вкус цвет шапки SourceBans"} Цвет шапки</label>
					<div class="col-sm-9 p-t-10">
						<input type="text" value="{$theme_color}" id="color_theme_result" name="color_theme_result" hidden="hidden" />
							<table>
								<tr>
									<td>
										<label for="lightblue" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="lightblue" id="lightblue" name="inlineRadioOptions" hidden="hidden" onchange="$('color_theme_result').value = this.value;"/>
											<i class="input-helper"></i>    
											<span class="ss-skin bgm-lightblue" data-skin="lightblue">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="bluegray" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="bluegray" id="bluegray" name="inlineRadioOptions" hidden="hidden" onchange="$('color_theme_result').value = this.value;"/>
											<i class="input-helper"></i>    
											<span class="ss-skin bgm-bluegray" data-skin="bluegray">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</label>
									</td>
								</tr>
								<tr>
									<td>
										 <label for="cyan" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="cyan" id="cyan" name="inlineRadioOptions" hidden="hidden" onchange="$('color_theme_result').value = this.value;"/>
											<i class="input-helper"></i>    
											<span class="ss-skin bgm-cyan" data-skin="cyan">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</label>
									</td>
								</tr>
								<tr>
									<td>
										 <label for="teal" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="teal" id="teal" name="inlineRadioOptions" hidden="hidden" onchange="$('color_theme_result').value = this.value;"/>
											<i class="input-helper"></i>    
											<span class="ss-skin bgm-teal" data-skin="teal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="orange" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="orange" id="orange" name="inlineRadioOptions" hidden="hidden" onchange="$('color_theme_result').value = this.value;"/>
											<i class="input-helper"></i>    
											<span class="ss-skin bgm-orange" data-skin="orange">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="blue" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="blue" id="blue" name="inlineRadioOptions" hidden="hidden" onchange="$('color_theme_result').value = this.value;"/>
											<i class="input-helper"></i>    
											<span class="ss-skin bgm-blue" data-skin="blue">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="del_style" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="" id="del_style" name="inlineRadioOptions" hidden="hidden" onchange="$('color_theme_result').value = this.value;"/>
											<i class="input-helper"></i> <span class="c-red">Убрать</span>
										</label>
									</td>
								</tr>
							</table>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="theme_color_p" class="col-sm-3 control-label">{help_icon title="Цвет шапки" message="Позволяет выбрать подходящий на ваш вкус цвет шапки SourceBans. При заполнении поля ввода, обычный цвет шапки будет игнорироваться!"} Цвет шапки(свой цвет)</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="theme_color_p" name="theme_color_p" {if $theme_color_t != ""}value="{$theme_color_t}"{else}placeholder="Введите код цвета в формате #ffffff(Не обязательно при выбранном цвете шапки)"{/if} />
						</div>
						<a href="http://www.stm.dp.ua/web-design/color-html.php" target="_blank">См.: Код цвета для HTML</a>
					</div>
				</div>
			</div>
			<div class="card-header">
				<h2>Управление фоном <small>Точнейшая настройка заднего фона с готовыми вариантами.</small>
							<ul class="actions">
                                <li class="dropdown">
									<a href="#" data-toggle="dropdown">
										<i class="zmdi zmdi-more-vert c-red"></i>
									</a>
                    
									<ul class="dropdown-menu dropdown-menu-right">
										<li>
											<a href="#" onclick="theme_num(1);">Шаблон 1</a>
										</li>
										<li>
											<a href="#" onclick="theme_num(2);">Шаблон 2</a>
										</li>
										<li>
											<a href="#" onclick="theme_num(3);">Шаблон 3</a>
										</li>
										<li>
											<a href="#" onclick="theme_num(4);">Шаблон 4</a>
										</li>
										<li>
											<a href="#" onclick="theme_num(5);">Шаблон 5</a>
										</li>
										<li>
											<a href="#" onclick="theme_num(6);">Шаблон 6</a>
										</li>
										<li>
											<a href="#" onclick="theme_num(7);">Шаблон 7</a>
										</li>
										<li>
											<a href="#" onclick="theme_num(8);">Шаблон 8</a>
										</li>
										<li>
											<a href="#" onclick="theme_num(9);">Шаблон 9</a>
										</li>
										<li>
											<a href="#" onclick="theme_num(10);">Шаблон 10</a>
										</li>
										<li>
											<a href="#" onclick="theme_num(11);">Шаблон 11</a>
										</li>
									</ul>
                                </li>
                            </ul>
				</h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-5">
					<label for="bg_scr" class="col-sm-3 control-label">{help_icon title="Фон" message="Задний фон всех страниц SourceBans. Если хотите использовать цвет вместо картинки, напиши код цвета в формате '#ffffff', либо rgb/rgba - без всяких ';', а не ссылку на картинку."} Фон</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="bg_scr" name="bg_scr" {if $config_bg_scr_value != ""}value="{$config_bg_scr_value}"{else}placeholder="Введите ссылку на картинку или код цвета в формате #ffffff, либо rgb/rgba(Не обязательно)"{/if} />
						</div>
						<a href="http://www.stm.dp.ua/web-design/color-html.php" target="_blank">См.: Код цвета для HTML</a>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label class="col-sm-3 control-label">{help_icon title="Повторение фона" message="Повторение картинки фона, при условии, что указана ссылка на картинку."} Повторение</label>
					<div class="col-sm-9 p-t-10">
						<input type="text" value="{$config_bg_scr_rep_value}" id="bg_scr_rep" name="bg_scr_rep" hidden="hidden" />
						<table>
								<tr>
									<td>
										<label for="no-repeat" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="no-repeat" id="no-repeat" name="inlineRadioOptions_bg_rep" hidden="hidden" onchange="$('bg_scr_rep').value = this.value;"/>
											<i class="input-helper"></i> no-repeat
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="repeat" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="repeat" id="repeat" name="inlineRadioOptions_bg_rep" hidden="hidden" onchange="$('bg_scr_rep').value = this.value;"/>
											<i class="input-helper"></i> repeat
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="repeat-x" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="repeat-x" id="repeat-x" name="inlineRadioOptions_bg_rep" hidden="hidden" onchange="$('bg_scr_rep').value = this.value;"/>
											<i class="input-helper"></i> repeat-x
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="repeat-y" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="repeat-y" id="repeat-y" name="inlineRadioOptions_bg_rep" hidden="hidden" onchange="$('bg_scr_rep').value = this.value;"/>
											<i class="input-helper"></i> repeat-y
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="inherit" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="inherit" id="inherit" name="inlineRadioOptions_bg_rep" hidden="hidden" onchange="$('bg_scr_rep').value = this.value;"/>
											<i class="input-helper"></i> inherit
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="space" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="space" id="space" name="inlineRadioOptions_bg_rep" hidden="hidden" onchange="$('bg_scr_rep').value = this.value;"/>
											<i class="input-helper"></i> space
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="round" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="round" id="round" name="inlineRadioOptions_bg_rep" hidden="hidden" onchange="$('bg_scr_rep').value = this.value;"/>
											<i class="input-helper"></i> round
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="del_rep" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="" id="del_rep" name="inlineRadioOptions_bg_rep" hidden="hidden" onchange="$('bg_scr_rep').value = this.value;"/>
											<i class="input-helper"></i> <span class="c-red">Убрать</span>
										</label>
									</td>
								</tr>
						</table>
						<a href="http://htmlbook.ru/css/background-repeat" target="_blank">См.: Описание background-repeat</a>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label class="col-sm-3 control-label">{help_icon title="Прикрепление" message="Позволяет настроить крепление картинки, при условии, что она есть."} Крепление</label>
					<div class="col-sm-9 p-t-10">
						<input type="text" value="{$config_bg_att_value}" id="bg_scr_att" name="bg_scr_att" hidden="hidden" />
						<table>
								<tr>
									<td>
										<label for="fixed" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="fixed" id="fixed" name="inlineRadioOptions_bg_att" hidden="hidden" onchange="$('bg_scr_att').value = this.value;"/>
											<i class="input-helper"></i> fixed
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="scroll" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="scroll" id="scroll" name="inlineRadioOptions_bg_att" hidden="hidden" onchange="$('bg_scr_att').value = this.value;"/>
											<i class="input-helper"></i> scroll
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="inherit_2" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="inherit" id="inherit_2" name="inlineRadioOptions_bg_att" hidden="hidden" onchange="$('bg_scr_att').value = this.value;"/>
											<i class="input-helper"></i> inherit
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="local" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="local" id="local" name="inlineRadioOptions_bg_att" hidden="hidden" onchange="$('bg_scr_att').value = this.value;"/>
											<i class="input-helper"></i> local
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<label for="del_att" class="radio radio-inline m-r-20 p-t-0">
											<input type="radio" value="" id="del_att" name="inlineRadioOptions_bg_att" hidden="hidden" onchange="$('bg_scr_att').value = this.value;"/>
											<i class="input-helper"></i> <span class="c-red">Убрать</span>
										</label>
									</td>
								</tr>
						</table>
						<a href="http://htmlbook.ru/css/background-attachment" target="_blank">См.: Описание background-attachment</a>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="bg_pos" class="col-sm-3 control-label">{help_icon title="Позиция" message="Позиция картинки, если такова имеется. Вводить значения, типа 'center' или 'top left' и т.д."} Позиция</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="bg_pos" name="bg_pos" {if $config_bg_pos_value != ""}value="{$config_bg_pos_value}"{else}placeholder="Введите данные, типа: 'center' или 'top left' и т.д.(Не обязательно)"{/if} />
						</div>
						<a href="http://htmlbook.ru/css/background-position" target="_blank">См.: Описание background-position</a>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="bg_size" class="col-sm-3 control-label">{help_icon title="Размер" message="Размер картинки в %. Например: '100%'"} Размер</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="bg_size" name="bg_size" {if $config_bg_size_value != ""}value="{$config_bg_size_value}"{else}placeholder="Введите данные, для изменения размера картинки.(Не обязательно)"{/if} />
						</div>
						<a href="http://htmlbook.ru/faq/kak-rastyanut-fon-na-vsyu-shirinu-okna" target="_blank">См.: Гайд background-size</a>
					</div>
				</div>
			</div>
			<div class="card-header">
				<h2>Управление информацией <small>Управление дополнительными функциями на главной странице SourceBans.</small></h2>
			</div>
			<div class="alert alert-info" role="alert">Если функция выключена, дальнешие настройки в данном блоке не требуются.<br />Если некоторые параметры не нужны, просто оставьте поле ввода - пустым.</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-5">
					<label for="obrat_cvaz" class="col-sm-3 control-label">{help_icon title="Дополнительная информация" message="Добавляет на главной странице дополнительный блок в рездел 'Главная', в котором можно указать дополнительную информацию."} Обратная связь</label>
					<div class="col-sm-9 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="obrat_cvaz" name="obrat_cvaz" hidden="hidden" /> 
							<label for="obrat_cvaz" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label> Включить?
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="dash_textik" class="col-sm-3 control-label">{help_icon title="Дополнительное описание" message="Описание, которое добавляется в раздел 'Главная' на главную страницу SourceBans."} Описание</label>
					<div class="col-sm-9 p-t-15">
						<textarea cols="80" rows="20" id="dash_textik" name="dash_textik" class="html-editor">{if $dash_info_block_text != ""}{$dash_info_block_text}{else}{/if}</textarea>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="dash_textik_p" class="col-sm-3 control-label">{help_icon title="Дополнительная подпись" message="Подпись, которая добавляется в описание."} Подпись</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="dash_textik_p" name="dash_textik_p" {if $dash_info_block_text_t != ""}value="{$dash_info_block_text_t}"{else}placeholder="Введите данные(Не обязательно)"{/if} />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="dash_link_vk" class="col-sm-3 control-label">{help_icon title="Дополнительная ссылка" message="Ссылка, которая добавляется в описание. Пример: http://site1.com/"} VK</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="dash_link_vk" name="dash_link_vk" {if $dash_info_vk != ""}value="{$dash_info_vk}"{else}placeholder="Введите данные(Не обязательно)"{/if} />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="dash_link_steam" class="col-sm-3 control-label">{help_icon title="Дополнительная ссылка" message="Ссылка, которая добавляется в описание. Пример: http://site1.com/"} Steam</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="dash_link_steam" name="dash_link_steam" {if $dash_info_steam != ""}value="{$dash_info_steam}"{else}placeholder="Введите данные(Не обязательно)"{/if} />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="dash_link_yout" class="col-sm-3 control-label">{help_icon title="Дополнительная ссылка" message="Ссылка, которая добавляется в описание. Пример: http://site1.com/"} Youtube</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="dash_link_yout" name="dash_link_yout" {if $dash_info_yout != ""}value="{$dash_info_yout}"{else}placeholder="Введите данные(Не обязательно)"{/if} />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="dash_link_faceb" class="col-sm-3 control-label">{help_icon title="Дополнительная ссылка" message="Ссылка, которая добавляется в описание. Пример: http://site1.com/"} Facebook</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="dash_link_faceb" name="dash_link_faceb" {if $dash_info_face != ""}value="{$dash_info_face}"{else}placeholder="Введите данные(Не обязательно)"{/if} />
						</div>
					</div>
				</div>
			</div>
			<div class="card-header">
				<h2>Управление уведомлениями <small>Заполните, если необходимо, текст уведомлений, сопровождающий администраторов/пользователей.</small></h2>
			</div>
			<div class="alert alert-info" role="alert">Если вы не хотите использовать уведомление, оставьте поле ввода у этого уведомления - пустым.</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-5">
					<label for="yvedom_1" class="col-sm-3 control-label">{help_icon title="Уведомление" message="Уведомление, которое выходит при заходе на главную страницу SourceBans."} На главной</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="yvedom_1" name="yvedom_1" {if $config_text_home != ""}value="{$config_text_home}"{else}placeholder="Введите данные(Не обязательно)"{/if} />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="yvedom_2" class="col-sm-3 control-label">{help_icon title="Уведомление" message="Уведомление, которое выходит администратору при заходе на страницу мониторинга."} В мониторинге</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="yvedom_2" name="yvedom_2" {if $config_text_mon != ""}value="{$config_text_mon}"{else}placeholder="Введите данные(Не обязательно)"{/if} />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="yvedom_3" class="col-sm-3 control-label">{help_icon title="Уведомление" message="Перове уведомление, которое выходит пользователю при заходе в свой аккаунт SourceBans."} В профиле (1)</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="yvedom_3" name="yvedom_3" {if $config_text_acc != ""}value="{$config_text_acc}"{else}placeholder="Введите данные(Не обязательно)"{/if} />
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="yvedom_4" class="col-sm-3 control-label">{help_icon title="Уведомление" message="Второе уведомление, которое выходит пользователю при заходе в свой аккаунт SourceBans."} В профиле (2)</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" class="form-control" id="yvedom_4" name="yvedom_4" {if $config_text_acc2 != ""}value="{$config_text_acc2}"{else}placeholder="Введите данные(Не обязательно)"{/if} />
						</div>
					</div>
				</div>
			</div>
			<div class="card-body card-padding text-center">
				{sb_button text="Сохранить" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" id="asettings" submit=true}
				&nbsp;
				{sb_button text="Назад" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
			</div>
		</div>
	</div>
</form>
