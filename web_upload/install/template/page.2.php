<?php
	if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}
	if(isset($_POST['postd']))
	{
		if(empty($_POST['server']) ||empty($_POST['port']) ||empty($_POST['username']) ||empty($_POST['database']) ||empty($_POST['prefix']))
		{
			echo "<script>setTimeout(\"ShowBox('Внимание!', 'Заколните необходимые поля.', 'blue', '', true)\", 1000);</script>";
		}
		else
		{
			$server = "mysqli://" . $_POST['username'] . ":" . $_POST['password'] . "@" . $_POST['server'] . ":" . $_POST['port'] . "/" . $_POST['database'];
			$db = ADONewConnection($server);
			if(!$db) {
				echo "<script>setTimeout(\"ShowBox('Ошибка', 'ошибка соединения с сервером баз данных. <br />Проверьте введенные данные', 'red', '', true);\", 1200);</script>";
			} else if(strlen($_POST['prefix']) > 9) {
				echo "<script>setTimeout(\"ShowBox('Ошибка', 'Префикс таблиц не может быть длиннее 9 символов.<br />Исправьте это.', 'red', '', true);\", 1200);</script>";
			} else {
				?>
				<form action="index.php?step=3" method="post" name="send" id="send">
					<input type="hidden" name="username" value="<?php echo $_POST['username']?>">
					<input type="hidden" name="password" value="<?php echo $_POST['password']?>">
					<input type="hidden" name="server" value="<?php echo $_POST['server']?>">
					<input type="hidden" name="database" value="<?php echo $_POST['database']?>">
					<input type="hidden" name="port" value="<?php echo $_POST['port']?>">
					<input type="hidden" name="prefix" value="<?php echo $_POST['prefix']?>">
					<input type="hidden" name="apikey" value="<?php echo $_POST['apikey']?>">
					<input type="hidden" name="sb-wp-url" value="<?php echo $_POST['sb-wp-url']?>">
				</form>
				<script>
				$('send').submit();
				</script> <?php
			}
		}
	}
	?>



<div class="card m-b-0" id="messages-main">
	<form action="" method="post" name="submit" id="submit">
		<div class="ms-menu">
			<div class="ms-block p-10">
				<span class="c-black"><b>Процесс</b></span>
			</div>

			<div class="listview lv-user" id="install-progress">
				<div class="lv-item media">
					<div class="lv-avatar bgm-orange pull-left">1</div>
					<div class="media-body">
						<div class="lv-title"><del>Шаг: Лицензия</del></div>
						<div class="lv-small"><i class="zmdi zmdi-timer-off zmdi-hc-fw c-red"></i> <del>Предыдущий шаг</del></div>
					</div>
				</div>

				<div class="lv-item media active">
					<div class="lv-avatar bgm-red pull-left">2</div>
					<div class="media-body">
						<div class="lv-title">Шаг: База данных</div>
						<div class="lv-small"><i class="zmdi zmdi-badge-check zmdi-hc-fw c-green"></i> Текущий шаг</div>
					</div>
				</div>

				<div class="lv-item media">
					<div class="lv-avatar bgm-orange pull-left">3</div>
					<div class="media-body">
						<div class="lv-title">Шаг: Системные требования</div>
						<div class="lv-small"><i class="zmdi zmdi-time zmdi-hc-fw c-blue"></i> Следующий шаг</div>
					</div>
				</div>

				<div class="lv-item media">
					<div class="lv-avatar bgm-orange pull-left">4</div>
					<div class="media-body">
						<div class="lv-title">Шаг: Создание таблиц</div>
						<div class="lv-small"><i class="zmdi zmdi-time zmdi-hc-fw c-blue"></i> Следующий шаг</div>
					</div>
				</div>

				<div class="lv-item media">
					<div class="lv-avatar bgm-orange pull-left">5</div>
					<div class="media-body">
						<div class="lv-title">Шаг: Установка</div>
						<div class="lv-small"><i class="zmdi zmdi-time zmdi-hc-fw c-blue"></i> Следующий шаг</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="ms-body">
			<div class="listview lv-message">
				<div class="lv-header-alt clearfix">
					<div class="lvh-label">
						<span class="c-black">Информация</span>
					</div>
				</div>

				<div class="lv-body p-15">                                    
					Наводите курсор мыши на иконку <img border="0" src="../images/help.png" /> для получения дополнительной информации.
				</div>

				<div class="lv-header-alt clearfix">
					<div class="lvh-label">
						<span class="c-black" id="submit-main-full">Информация MySQL</span>
					</div>
				</div>
				<div class="lv-body p-15" id="group.details">
					<div class="col-sm-12">
						<div class="form-group col-sm-12">
							<label for="server" class="col-sm-3 control-label"><?php echo HelpIcon("Сервер", "Введите IP или адрес сервера MySQL");?> Адрес сервера</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="text" class="form-control input-sm" id="server" name="server" placeholder="Введите данные" value="<?php echo isset($_POST['server'])?$_POST['server']:'localhost';?>" />
								</div>
								<div id="server.msg"></div>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<label for="port" class="col-sm-3 control-label"><?php echo HelpIcon("Порт сервера", "Введите порт, на котором работает MySQL");?> Порт сервера</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="text" class="form-control input-sm" id="port" name="port" placeholder="Введите данные" value="<?php echo isset($_POST['port'])?$_POST['port']:3306;?>" />
								</div>
								<div id="port.msg"></div>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<label for="username" class="col-sm-3 control-label"><?php echo HelpIcon("Имя пользователя", "Введите имя пользователя MySQL");?> Имя пользователя</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="text" class="form-control input-sm" id="username" name="username" placeholder="Введите данные" value="<?php echo isset($_POST['username'])?$_POST['username']:'';?>" />
								</div>
								<div id="user.msg"></div>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<label for="password" class="col-sm-3 control-label"><?php echo HelpIcon("Пароль", "Введите пароль пользователя MySQL");?> Пароль</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="password" class="form-control input-sm" id="password" name="password" placeholder="Введите данные" value="<?php echo isset($_POST['password'])?$_POST['password']:'';?>" />
								</div>
								<div id="password.msg"></div>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<label for="database" class="col-sm-3 control-label"><?php echo HelpIcon("База данных", "Введите имя базы данных");?> База данных</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="text" class="form-control input-sm" id="database" name="database" placeholder="Введите данные" value="<?php echo isset($_POST['database'])?$_POST['database']:'';?>" />
								</div>
								<div id="database.msg"></div>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<label for="prefix" class="col-sm-3 control-label"><?php echo HelpIcon("Префикс", "Введите префикс таблиц");?> Префикс таблиц</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="text" class="form-control input-sm" id="prefix" name="prefix" placeholder="Введите данные" value="<?php echo isset($_POST['prefix'])?$_POST['prefix']:'sb';?>" />
								</div>
								<div id="database.msg"></div>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<label for="apikey" class="col-sm-3 control-label"><?php echo HelpIcon("Steam API ключ", "Скопируйте и вставьте ваш Steam API ключ здесь. Он нужен для авторизации администраторов через Steam.");?> Steam API ключ (необязательно)</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="text" class="form-control input-sm" id="apikey" name="apikey" placeholder="Введите данные" value="<?php echo isset($_POST['apikey'])?$_POST['apikey']:'';?>" />
								</div>
								<div id="database.msg"></div>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<label for="sb-wp-url" class="col-sm-3 control-label"><?php echo HelpIcon("Адрес SourceBans", "Адрес установки системы SourceBans. Пример: http://mysite.com/bans/");?> Адрес SourceBans</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="text" class="form-control input-sm" id="sb-wp-url" name="sb-wp-url" placeholder="Введите данные" value="<?php echo isset($_POST['sb-wp-url'])?$_POST['sb-wp-url']:TryAutodetectURL();?>" />
								</div>
								<div id="database.msg"></div>
							</div>
						</div>
						<div class="p-10" align="center">
							<button onclick="checkAccept()" class="btn btn-primary waves-effect" id="button" name="button">Далее</button>
						</div>
						<input type="hidden" name="postd" value="1">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>



<script type="text/javascript">
	$E('html').onkeydown = function(event){
	    var event = new Event(event);
	    if (event.key == 'enter' ) $('submit').submit();
	}
</script>
