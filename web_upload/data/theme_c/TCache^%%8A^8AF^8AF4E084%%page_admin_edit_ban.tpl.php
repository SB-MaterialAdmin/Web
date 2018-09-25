<?php /* Smarty version 2.6.29, created on 2018-09-18 17:50:30
         compiled from page_admin_edit_ban.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'help_icon', 'page_admin_edit_ban.tpl', 11, false),array('function', 'sb_button', 'page_admin_edit_ban.tpl', 155, false),)), $this); ?>
<form action="" method="post">
	<div class="card" id="admin-page-content">
		<div id="0">
		<div class="form-horizontal" role="form" id="add-group">
			<div class="card-header">
				<h2>Детали бана <small>За дополнительной информацией или помощью наведите курсор мыши на знак вопроса.</small></h2>
			</div>
			<input type="hidden" name="insert_type" value="add">
			<div class="card-body card-padding p-b-0" id="group.details">
				<div class="form-group m-b-5">
					<label for="name" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Имя игрока",'message' => "Имя забаненного игрока."), $this);?>
 Имя игрока</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="name" name="name" value="<?php echo $this->_tpl_vars['ban_name']; ?>
" placeholder="Введите данные">
						</div>
						<div id="name.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="type" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Тип бана",'message' => "Как банить: по STEAM ID или по IP."), $this);?>
 Тип бана</label>
					<div class="col-sm-2">
						<select class="selectpicker" id="type"  name="type">
							<option value="0">Steam ID</option>
							<option value="1">IP адрес</option>
						</select>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="steam" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => 'Steam ID','message' => "Steam ID забаненного игрока. Также можно ввести его Community ID."), $this);?>
 Steam ID / Community ID</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="steam" name="steam" value="<?php echo $this->_tpl_vars['ban_authid']; ?>
" placeholder="Введите данные">
						</div>
						<div id="steam.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="ip" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "IP адрес",'message' => "IP адрес забаненного игрока"), $this);?>
 IP адрес</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="ip" name="ip" value="<?php echo $this->_tpl_vars['ban_ip']; ?>
" placeholder="Введите данные">
						</div>
						<div id="ip.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="txtReason" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Причина бана",'message' => "Объясните, за что Вы его баните."), $this);?>
 Причина бана</label>
					<div class="col-sm-6" id="dreason" style="display:none;">
						<div class="fg-line">
							<input type="text" TABINDEX=4 class="form-control" id="txtReason" name="txtReason" placeholder="Напишите свою причину бана...">
						</div>
					</div>
					<div class="col-sm-3 p-t-5">
						<select id="listReason" name="listReason" TABINDEX=4 class="selectpicker" onChange="changeReason(this[this.selectedIndex].value);">
							  <option value="" selected> -- Выберите причину -- </option>
							  <optgroup label="Читы">
								<option value="Aimbot">Aimbot</option>
								<option value="Antirecoil">Antirecoil</option>
								<option value="Wallhack">Wallhack</option>
								<option value="Spinhack">Spinhack</option>
								<option value="Multi-Hack">Multi-Hack</option>
								<option value="No Smoke">No Smoke</option>
								<option value="No Flash">No Flash</option>
							  </optgroup>
							  <optgroup label="Поведение">









								<option value="Убийство союзников">Убийство союзников</option>
								<option value="Ослепление союзников">Ослепление союзников</option>
								<option value="Спамил В Мик/Чат">Спамил В Мик/Чат</option>
								<option value="Неуместный спрей">Неуместный спрей</option>
								<option value="Неуместный язык">Неуместный язык</option>
								<option value="Неуместный ник">Неуместный ник</option>
								<option value="Игнорирование админа">Игнорирование админа</option>
								<option value="Блокировка союзников">Блокировка союзников</option>
								<option value="Мат/Оскорбления">Мат/Оскорбления</option>
								<option value="Реклама">Реклама</option>
							  </optgroup>
							  <?php if ($this->_tpl_vars['customreason']): ?>
								  <optgroup label="Custom">
								  <?php $_from = ($this->_tpl_vars['customreason']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['creason']):
?>
									<option value="<?php echo $this->_tpl_vars['creason']; ?>
"><?php echo $this->_tpl_vars['creason']; ?>
</option>
								  <?php endforeach; endif; unset($_from); ?>
								  </optgroup>
							  <?php endif; ?>
							  <option value="other">Другая причина</option>
						</select>
					</div>
					<div id="reason.msg"></div>
				</div>
				<div class="form-group m-b-5">
					<label for="banlength" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Срок бана",'message' => "Выберите срок бана."), $this);?>
 Срок бана</label>
					<div class="col-sm-3">
						<select id="banlength" name="banlength" TABINDEX=4 class="selectpicker">
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
				<div class="form-group m-b-5">
					<label for="demo_link" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => 'Demo Link','message' => "Введите ссылку на скачивание Демо файла через GoogleDisk или YandexDisk или любом другом хранилище."), $this);?>
 Demo Link</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="demo_link" name="demo_link" <?php if ($this->_tpl_vars['demo_link_val']): ?> value="<?php echo $this->_tpl_vars['demo_link_val']; ?>
" <?php else: ?> placeholder="Укажите ссылку(При необходимости)" <?php endif; ?> />
						</div>
						<div id="demo_link.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Загрузить демо",'message' => "Кликните тут, чтобы загрузить демо."), $this);?>
 Загрузить демо</label>
					<div class="col-sm-9 p-t-10">
						<?php echo smarty_function_sb_button(array('text' => "Загрузить демо",'onclick' => "childWindow=open('pages/admin.uploaddemo.php','upload','resizable=no,width=300,height=130');",'class' => "bgm-orange",'id' => 'udemo','submit' => false), $this);?>

						<div id="demo1.msg" class="contacts c-profile clearfix p-t-20 p-l-0" <?php if ($this->_tpl_vars['ban_demo']): ?> style="display:block;" <?php else: ?> style="display:none;" <?php endif; ?>>
							<div class="col-md-3 col-sm-4 col-xs-6 p-l-0 p-r-0">
								<div class="c-item">
									<div href="#" class="ci-avatar text-center f-20 p-t-10">
										<i class="zmdi zmdi-balance-wallet zmdi-hc-fw"></i>
									</div>
																		
									<div class="c-info">
										<strong id="demo.msg"><?php echo $this->_tpl_vars['ban_demo']; ?>
</strong>
									</div>
																		
									<div class="c-footer c-green f-700 text-center p-t-5 p-b-5">

										Успешно загружено
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body card-padding text-center">
				<input type="hidden" name="did" id="did" value="" />
			    <input type="hidden" name="dname" id="dname" value="" /> 
				<?php echo smarty_function_sb_button(array('text' => "Сохранить",'icon' => "<i class='zmdi zmdi-check-all'></i>",'class' => "bgm-green btn-icon-text",'id' => 'editban','submit' => true), $this);?>

			    &nbsp;
			    <?php echo smarty_function_sb_button(array('text' => "Назад",'onclick' => "history.go(-1)",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'back','submit' => false), $this);?>

			</div>
			<script type="text/javascript">
				var did = 0;
				var dname = "";
				function demo(id, name)
				{
					$('demo.msg').setHTML("<b>" + name + "</b>");
					$('demo1.msg').style.display = "block";
					$('did').value = id;
					$('dname').value = name;
				}
			</script>
		</div>
		</div>
	</div>
	
</form>