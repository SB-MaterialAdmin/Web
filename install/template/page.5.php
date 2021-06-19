<?php
	if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}
	
	$web_cfg = "<?php
/**
 * config.php
 * 
 * This file contains all of the configuration for the db
 * that will 
 * @author SteamFriends Development Team
 * @version 1.0.0
 * @copyright SteamFriends (www.SteamFriends.com)
 * @package SourceBans
 */
if(!defined('IN_SB')){echo 'You should not be here. Only follow links!';die();}

define('DB_HOST', '{server}');   					// The host/ip to your SQL server
define('DB_USER', '{user}');						// The username to connect with
define('DB_PASS', '{pass}');						// The password
define('DB_NAME', '{db}');  						// Database name	
define('DB_PREFIX', '{prefix}');					// The table prefix for SourceBans
define('DB_PORT','{port}');							// The SQL port (Default: 3306)
define('STEAMAPIKEY','{steamapikey}');				// Steam API Key for Shizz
define('SB_WP_URL','{sbwpurl}');       				//URL of SourceBans Site

//define('DEVELOPER_MODE', true);			// Use if you want to show debugmessages
//define('SB_MEM', '128M'); 				// Override php memory limit, if isn't enough (Banlist is just a blank page)
?>";

	$srv_cfg = '"driver_default"		"mysql"
	
	"sourcebans"
	{
		"driver"			"mysql"
		"host"				"{server}"
		"database"			"{db}"
		"user"				"{user}"
		"pass"				"{pass}"
		//"timeout"			"0"
		"port"			"{port}"
	}
	
	"sourcecomms"
	{
		"driver"			"mysql"
		"host"				"{server}"
		"database"			"{db}"
		"user"				"{user}"
		"pass"				"{pass}"
		//"timeout"			"0"
		"port"			"{port}"
	}
';
	
	$web_cfg = str_replace("{server}", $_POST['server'], $web_cfg);
	$web_cfg = str_replace("{user}", $_POST['username'], $web_cfg);
	$web_cfg = str_replace("{pass}", $_POST['password'], $web_cfg);
	$web_cfg = str_replace("{db}", $_POST['database'], $web_cfg);
	$web_cfg = str_replace("{prefix}", $_POST['prefix'], $web_cfg);
	$web_cfg = str_replace("{port}", $_POST['port'], $web_cfg);
	$web_cfg = str_replace("{steamapikey}", $_POST['apikey'], $web_cfg);
	$web_cfg = str_replace("{sbwpurl}", $_POST['sb-wp-url'], $web_cfg);
	
	$srv_cfg = str_replace("{server}", $_POST['server'], $srv_cfg);
	$srv_cfg = str_replace("{user}", $_POST['username'], $srv_cfg);
	$srv_cfg = str_replace("{pass}", $_POST['password'], $srv_cfg);
	$srv_cfg = str_replace("{db}", $_POST['database'], $srv_cfg);
	$srv_cfg = str_replace("{port}", $_POST['port'], $srv_cfg);
	
	if(is_writable("../config.php"))
	{
		$config = fopen(ROOT . "../config.php", "w");
		fwrite($config, $web_cfg);
		fclose($config);
	}
	
	if(isset($_POST['postd']) && $_POST['postd'])
	{
		if(empty($_POST['uname']) ||empty($_POST['pass1']) ||empty($_POST['pass2'])||empty($_POST['steam'])||empty($_POST['email']))
		{
			echo "<script>setTimeout(\"ShowBox('Ошибка', 'Пропущены некоторые данные. Все поля должны быть заполнены.', 'red', '', true);\", 1200);</script>";
		}
		else
		{
			require(ROOT . "../includes/adodb/adodb.inc.php");
			include_once(ROOT . "../includes/adodb/adodb-errorhandler.inc.php");
			$server = "mysqli://" . $_POST['username'] . ":" . $_POST['password'] . "@" . $_POST['server'] . ":" . $_POST['port'] . "/" . $_POST['database'];
			$db = ADONewConnection($server);
			if(!$db)
				echo "<script>setTimeout(\"ShowBox('Ошибка', 'Ошибка соединения с базой данных. <br />Проверьте данные', 'red', '', true);\", 1200);</script>";
			else 
			{
				$db->Execute("SET NAMES `utf8`");
				// Setup Admin
				$admin = $GLOBALS['db']->Prepare("INSERT INTO ".$_POST['prefix']."_admins(user,authid,password,gid, email, extraflags, immunity) VALUES (?,?,?,?,?,?,?)");
				$GLOBALS['db']->Execute($admin,array($_POST['uname'], $_POST['steam'], sha1(sha1(SB_SALT . $_POST['pass1'])), -1, $_POST['email'], (1<<24), 100));
				
				// Auth admin
				setcookie("aid", 1);
				setcookie("password", sha1(sha1(SB_SALT . $_POST['pass1'])));
	
							
				// Setup Settings
				$file = file_get_contents(INCLUDES_PATH . "/data.sql");
				$file = str_replace("{prefix}", $_POST['prefix'], $file);
				$querys = explode(";", $file);
				foreach($querys AS $q)
				{
					if(strlen($q) > 2)
					{
						$res = $db->Execute(stripslashes($q) . ";");
						if(!$res)
							$errors++;
					}	
				}	
				
				?>
					<div class="card m-b-0"  id="messages-main">
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

								<div class="lv-item media">
									<div class="lv-avatar bgm-orange pull-left">2</div>
									<div class="media-body">
										<div class="lv-title"><del>Шаг: База данных</del></div>
										<div class="lv-small"><i class="zmdi zmdi-timer-off zmdi-hc-fw c-red"></i> <del>Предыдущий шаг</del></del></div>
									</div>
								</div>

								<div class="lv-item media">
									<div class="lv-avatar bgm-orange pull-left">3</div>
									<div class="media-body">
										<div class="lv-title"><del>Шаг: Системные требования</del></div>
										<div class="lv-small"><i class="zmdi zmdi-timer-off zmdi-hc-fw c-blue"></i> <del>Предыдущий шаг</del></div>
									</div>
								</div>

								<div class="lv-item media">
									<div class="lv-avatar bgm-orange pull-left">4</div>
									<div class="media-body">
										<div class="lv-title"><del>Шаг: Создание таблиц</del></div>
										<div class="lv-small"><i class="zmdi zmdi-timer-off zmdi-hc-fw c-blue"></i> <del>Предыдущий шаг</del></div>
									</div>
								</div>

								<div class="lv-item media active">
									<div class="lv-avatar bgm-red pull-left">5</div>
									<div class="media-body">
										<div class="lv-title">Шаг: Установка</div>
										<div class="lv-small"><i class="zmdi  zmdi-badge-check zmdi-hc-fw c-green"></i> Текущий шаг</div>
									</div>
								</div>
							</div>
						</div>
						<div class="ms-body" id="submit-main">
							<div class="listview lv-message">
								<div class="lv-header-alt clearfix">
									<div class="lvh-label">
										<span class="c-black">Информация</span>
									</div>
								</div>

								<div class="lv-body p-15">
									Наводите курсор мыши на кнопки <img border="0" src="../images/help.png" /> для получения дополнительной информации.
								</div>
								
								<div class="lv-header-alt clearfix">
									<div class="lvh-label">
										<span class="c-black">Последние шаги</span>
									</div>
								</div>
								
								<div class="lv-body p-15">
									Последним шагом будет вставить данные в databases.cfg на игровом сервере (/[MOD]/addons/sourcemod/configs/databases.cfg)
								</div>
								
								<div class="lv-body p-15">
									<div class="col-sm-12">
										
										<div class="form-group col-sm-12">
											<label class="col-sm-3 control-label"><?php echo HelpIcon("Данные", "Этот код должен быть вставлен в секцию 'Databases' { [вот сюда] }");?>databases.cfg</label>
											<div class="col-sm-9">
												<textarea class="form-control" cols="105" rows="15" readonly><?php echo $srv_cfg;?></textarea>
											</div>
										</div>
										
									</div>
									&nbsp;
								</div>
								<?php
									if(strtolower($_POST['server']) == "localhost" || $_POST['server'] == "127.0.0.1")
									{
										echo '<script>setTimeout(\'ShowBox("Предупреждение локального сервера", "Вы указали, что Ваш сервер MySQL запущен на той же машине, что и вебсервер. Если это не так, то в databases.cfg замените значение localhost на IP адрес веб сервера." , "blue", "", true);\', 1200);</script>';
									}
									if(!is_writable("../config.php"))
									{
								?>
										<div class="lv-header-alt clearfix">
											<div class="lvh-label">
												<span class="c-black">Запись данных</span>
											</div>
										</div>
										<div class="lv-body p-15">
											Так как файл config.cfg не перезаписываемый, вставьте в него следующие значения самостоятельно.
										</div>
										
										<div class="lv-body p-15">
											<div class="col-sm-12">
												<div class="form-group col-sm-12">
													<label class="col-sm-3 control-label"><?php echo HelpIcon("Данные", "Этот код должен быть вставлен в файл config.cfg, который находится в корне SourceBans.");?>config.cfg</label>
													<div class="col-sm-9">
														<textarea class="form-control" cols="105" rows="15" readonly><?php echo $web_cfg;?></textarea><br />
													</div>
												</div>
											</div>
											&nbsp;
										</div>
								<?php
									}
								?>
								<div class="lv-header-alt clearfix">
									<div class="lvh-label">
										<span class="c-black">Финиш</span>
									</div>
									<div class="lv-body p-15">
                                        Установка SourceBans закончена. Удалите папку install в корне SourceBans, после перейдите на <a href="../updater">страницу обновлений</a>. SourceBans пытается автоматически произвести обновление, но в случае неполадок, стоит посмотреть именно вручную на страницу обновлений.<br /><br /><b>Не забудьте после процесса обновления, удалить и папку updater!</b>
                                    </div>
								</div>
								<iframe src="./../updater/index.php?updater_ajax_call=true" style="display: hidden;"></iframe>
							</div>
						</div>
					</div>
				<?php
			}
		}
		include TEMPLATES_PATH.'/footer.php';
		die();
	}
	
	?>
<form action="" name="mfrm" id="mfrm" method="post">

	<div class="card m-b-0"  id="messages-main">
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

				<div class="lv-item media">
					<div class="lv-avatar bgm-orange pull-left">2</div>
					<div class="media-body">
						<div class="lv-title"><del>Шаг: База данных</del></div>
						<div class="lv-small"><i class="zmdi zmdi-timer-off zmdi-hc-fw c-red"></i> <del>Предыдущий шаг</del></del></div>
					</div>
				</div>

				<div class="lv-item media">
					<div class="lv-avatar bgm-orange pull-left">3</div>
					<div class="media-body">
						<div class="lv-title"><del>Шаг: Системные требования</div>
						<div class="lv-small"><i class="zmdi zmdi-timer-off zmdi-hc-fw c-blue"></i> <del>Предыдущий шаг</del></div>
					</div>
				</div>

				<div class="lv-item media">
					<div class="lv-avatar bgm-orange pull-left">4</div>
					<div class="media-body">
						<div class="lv-title">Шаг: Создание таблиц</div>
						<div class="lv-small"><i class="zmdi zmdi-timer-off zmdi-hc-fw c-blue"></i> <del>Предыдущий шаг</del></div>
					</div>
				</div>

				<div class="lv-item media active">
					<div class="lv-avatar bgm-red pull-left">5</div>
					<div class="media-body">
						<div class="lv-title">Шаг: Установка</div>
						<div class="lv-small"><i class="zmdi  zmdi-badge-check zmdi-hc-fw c-green"></i> Текущий шаг</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="ms-body" id="submit-main">
			<div class="listview lv-message">
				<div class="lv-header-alt clearfix">
					<div class="lvh-label">
						<span class="c-black">Информация</span>
					</div>
				</div>

				<div class="lv-body p-15">
					Наводите курсор мыши на кнопки '?' для получения дополнительной информации.
				</div>
				
				<div class="lv-header-alt clearfix">
					<div class="lvh-label">
						<span class="c-black">Создание главного администратора</span>
					</div>
				</div>
				
				<div class="lv-body p-15">
					<div class="col-sm-12" id="group.details">
						
						<div class="form-group col-sm-12">
							<label for="uname" class="col-sm-3 control-label"><?php echo HelpIcon("Главный админ", "Введите имя главного админа");?>Имя админа</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="text" class="form-control input-sm" id="uname" name="uname" placeholder="Введите данные" value="" />
								</div>
								<div id="server.msg"></div>
							</div>
						</div>
						<div class="form-group col-sm-12">
							<label for="pass1" class="col-sm-3 control-label"><?php echo HelpIcon("Пароль", "Введите пароь главного админа");?>Пароль админа</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="password" class="form-control input-sm" id="pass1" name="pass1" placeholder="Введите данные" value="" />
								</div>
								<div id="port.msg"></div>
							</div>
						</div>
						<div class="form-group col-sm-12">
							<label for="pass2" class="col-sm-3 control-label"><?php echo HelpIcon("Подтверждение", "Введите пароль ещё раз");?>Подтверждение пароля</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="password" class="form-control input-sm" id="pass2" name="pass2" placeholder="Введите данные" value="" />
								</div>
								<div id="user.msg"></div>
							</div>
						</div>
						<div class="form-group col-sm-12">
							<label for="steam" class="col-sm-3 control-label"><?php echo HelpIcon("STEAM", "Введите Ваш STEAM id");?>STEAM ID</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="text" class="form-control input-sm" id="steam" name="steam" placeholder="Введите данные" value="" />
								</div>
								<div id="user.msg"></div>
							</div>
						</div>
						<div class="form-group col-sm-12">
							<label for="email" class="col-sm-3 control-label"><?php echo HelpIcon("E-mail", "Введите Ваш Е-mail");?>E-mail</label>
							<div class="col-sm-9">
								<div class="fg-line">
									<input type="text" class="form-control input-sm" id="email" name="email" placeholder="Введите данные" value="" />
								</div>
								<div id="user.msg"></div>
							</div>
						</div>
								
					</div>
					<br /><br />
					<div class="p-10" align="center">
						<button type="submit" onclick="CheckInput();" name="button" class="btn btn-primary waves-effect" id="button">Ok</button>
					</div>
				</div>
				</div>
		</div>
		<br /><br />
	</div>
 
<input type="hidden" name="postd" value="1">
<input type="hidden" name="username" value="<?php echo $_POST['username']?>">
<input type="hidden" name="password" value="<?php echo $_POST['password']?>">
<input type="hidden" name="server" value="<?php echo $_POST['server']?>">
<input type="hidden" name="database" value="<?php echo $_POST['database']?>">
<input type="hidden" name="port" value="<?php echo $_POST['port']?>">
<input type="hidden" name="prefix" value="<?php echo $_POST['prefix']?>">
<input type="hidden" name="apikey" value="<?php echo $_POST['apikey']?>">
<input type="hidden" name="sb-wp-url" value="<?php echo $_POST['sb-wp-url']?>">
</form>

<script type="text/javascript">
$E('html').onkeydown = function(event){
	var event = new Event(event);
	if (event.key == 'enter' ) CheckInput();
};
function CheckInput()
{
	var error = 0;
	
	if($('uname').value == "")
		error++;
	if($('pass1').value == "")
		error++;
	if($('pass2').value == "")
		error++;
	if($('steam').value == "")
		error++;
	if($('email').value == "")
		error++;
		
	if(error > 0)
		ShowBox('Ошибка', 'Все поля должны быть заполнены.', 'red', '', true);
	else
		$('mfrm').submit();
}
</script>
