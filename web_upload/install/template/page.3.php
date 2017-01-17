<?php
if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}
$errors = 0;
$warnings = 0;

if(isset($_POST['username'], $_POST['password'], $_POST['server'], $_POST['port'], $_POST['database'])) {
	require(ROOT . "../includes/adodb/adodb.inc.php");
	include_once(ROOT . "../includes/adodb/adodb-errorhandler.inc.php");
	$server = "mysqli://" . $_POST['username'] . ":" . $_POST['password'] . "@" . $_POST['server'] . ":" . $_POST['port'] . "/" . $_POST['database'];
	$db = ADONewConnection($server);
	$db->Execute("SET NAMES `utf8`");
	$vars = $db->Execute("SHOW VARIABLES");
	$sql_version = "";
	while(!$vars->EOF)
	{
		if($vars->fields['Variable_name'] == "version")
		{
			$sql_version = $vars->fields['Value'];
			break;
		}
		$vars->MoveNext();
	}
} else {
	$sql_version = "Невозможно соединиться, не введены детали базы данных. (Вернитесь назад и введите данные заново.)";
}
?>


<div class="card m-b-0" id="messages-main">
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
						<div class="lv-small"><i class="zmdi zmdi-timer-off zmdi-hc-fw c-red"></i> <del>Предыдущий шаг</del></div>
					</div>
				</div>

				<div class="lv-item media active">
					<div class="lv-avatar bgm-red pull-left">3</div>
					<div class="media-body">
						<div class="lv-title">Шаг: Системные требования</div>
						<div class="lv-small"><i class="zmdi zmdi-badge-check zmdi-hc-fw c-green"></i> Текущий шаг</div>
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
		
		<div class="ms-body" id="submit-main-full">
			<div class="listview lv-message">
				<div class="lv-header-alt clearfix">
					<div class="lvh-label">
						<span class="c-black">Информация</span>
					</div>
				</div>

				<div class="lv-body p-15">
					На этой странице перечислены все требования для работы веб-панели SourceBans. Система сверит их с текущими данными. На этой странице будут также перечислены некоторые рекомендациями.
				</div>
				
				<div class="lv-header-alt clearfix">
					<div class="lvh-label">
						<span class="c-black">Требования PHP</span>
					</div>
				</div>
				<div class="lv-body p-15">
					<div class="col-sm-12">
						
						<table class="table table-hover">
							<thead>
								<tr>
									<th width="30%">Настройка</th>
									<th>Рекомендуется</th>
									<th>Требуется</th>
									<th width="30%">Значения сервера</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Версия PHP</td>
									<td>5.5</td>
									<td>5.4</td>
									<?php 
										if(version_compare(PHP_VERSION, "5.4") != -1)
											$class = "success c-white";
										else {  $class = "danger c-white"; $errors++;}
									?>
									<td class="<?= $class ?>"><?= PHP_VERSION ?></td>
								</tr>
								<tr>
									<td>Поддержка bcmath</td>
									<td>Н/А</td>
									<td>Да</td>
									<?php
										$bcmath = function_exists('bcadd');
										if($bcmath)
											$class = "success c-white";
										else { $class = "danger c-white"; $errors++; }
									?>
									<td class="<?= $class ?>"><?= $bcmath ? 'Да' : 'Нет' ?></td>
								</tr>
								<tr>
									<td>Загрузка файлов</td>
									<td>Н/А</td>
									<td>Вкл</td>
									<?php 
										$uploads = ini_get("file_uploads");
										if($uploads)
											$class = "success c-white";
										else {  $class = "danger c-white"; $errors++; }
									?>
									<td class="<?= $class ?>"><?= $uploads ? 'Вкл' : 'Выкл' ?></td>
								</tr>
								<tr>
									<td>Поддержка XML</td>
									<td>Н/А</td>
									<td>Вкл</td>
									<?php 
										$xml = extension_loaded('xml');
										if($xml)
											$class = "success c-white";
										else { $class = "danger c-white"; $errors++; }
									?>
									<td class="<?= $class ?>"><?= $xml ? 'Вкл' : 'Выкл' ?></td>
								</tr>
								<tr>
									<td>Глобальные переменные</td>
									<td>Выкл</td>
									<td>Н/A</td>
									<?php 
										$rg = ini_get("register_globals");
										if(!$rg)
											$class = "success c-white";
										else {  $class = "active"; $errors++;}
									?>
									<td class="<?= $class ?>"><?= $rg=="" ? 'Выкл' : 'Вкл' ;?></td>
								</tr>
								<tr>
									<td>Safe Mode</td>
									<td>Выкл</td>
									<td>Н/A</td>
									<?php 
										if(ini_get('safe_mode')==0) {
											$class = "success c-white";
											$safem = "Выкл";
										}
										else {	
											$safem = "Вкл";
											$class = "active"; 
											$warnings++;
										}
									?>
									<td class="<?= $class ?>"><?= $safem ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					&nbsp;
				</div>
				
				<div class="lv-header-alt clearfix">
					<div class="lvh-label">
						<span class="c-black">Требования MySQL</span>
					</div>
				</div>
				<div class="lv-body p-15">
					<div class="col-sm-12">
						
						<table class="table table-hover">
							<thead>
								<tr>
									<th width="30%">Настройка</th>
									<th>Рекомендуется</th>
									<th>Требуется</th>
									<th width="30%">Значения сервера</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Версия MySQL</td>
									<td>Н/A</td>
									<td>5.0</td>
									<?php 
										if(version_compare($sql_version, "5") != -1){
											$class = "success c-white";
											$par = "width=\"30%\"";
										} else { $class = "danger c-white"; $errors++; $par = "width=\"50%\"";}
									?>
									<td class="<?= $class ?>" <?= $par ?>><?= $sql_version ?></td>
								</tr>
							</tbody>
						</table>
						
					</div>
					&nbsp;
				</div>
				
				<div class="lv-header-alt clearfix">
					<div class="lvh-label">
						<span class="c-black">Требования файловой системы</span>
					</div>
				</div>
				<div class="lv-body p-15">
					<div class="col-sm-12">
						
						<table class="table table-hover">
							<thead>
								<tr>
									<th width="30%">Настройка</th>
									<th>Рекомендуется</th>
									<th>Требуется</th>
									<th>Значения сервера</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Папка для демок (/demos)</td>
									<td>Н/A</td>
									<td>Перезаписываемая</td>
									<?php 
										if(is_writable("../demos")){
											$class = "success c-white";
										} else { $class = "danger c-white"; $errors++; }
									?>
									<td class="<?= $class ?>"><?= is_writable("../demos") ? "Да" : "Нет" ?></td>
								</tr>
								<tr>
									<td>Папка кэша (/themes_c)</td>
									<td>Н/A</td>
									<td>Перезаписываемая</td>
									<?php 
										if(is_writable("../themes_c")){
											$class = "success c-white";
										} else {  $class = "danger c-white"; $errors++; }
									?>
									<td class="<?= $class ?>"><?= is_writable("../themes_c") ? "Да" : "Нет" ?></td>
								</tr>
								<tr>
									<td>Папка иконок МОДов (/images/games)</td>
									<td>Н/A</td>
									<td>Перезаписываемая</td>
									<?php 
										if(is_writable("../images/games")){
											$class = "success c-white";
										} else {  $class = "danger c-white"; $errors++; }
									?>
									<td class="<?= $class ?>"><?= is_writable("../images/games") ? "Да" : "Нет" ?></td>
								</tr>
								<tr>
									<td>Папка изображений карт (/images/maps)</td>
									<td>Н/A</td>
									<td>Перезаписываемая</td>
									<?php 
										if(is_writable("../images/maps")){
											$class = "success c-white";
										} else {  $class = "danger c-white"; $errors++; }
									?>
									<td class="<?= $class ?>"><?= is_writable("../images/maps") ? "Да" : "Нет" ?></td>
								</tr>
								<tr>
									<td>Конфигурационный файл (/config.php)</td>
									<td>Н/A</td>
									<td>Перезаписываемая</td>
									<?php 
										if(is_writable("../config.php")){
											$class = "success c-white";
										} else {  $class = "danger c-white"; $errors++; }
									?>
									<td class="<?= $class ?>"><?= is_writable("../config.php") ? "Да" : "Нет" ?></td>
								</tr>
							</tbody>
						</table>
						<?php /* WhiteWolf: This is a hack to make sure the user didn't refresh the page, in the future we should tell them what they did. */
							if(!isset($_POST['username'], $_POST['password'], $_POST['server'], $_POST['database'], $_POST['port'], $_POST['prefix'])) {
						?>
						<form action="index.php?step=2" method="post" name="send" id="send">
							<!-- We don't even include the body here, since the javascript shouldn't let them go forward -->
						</form>
						<form action="index.php?step=2" method="post" name="sendback" id="sendback">
						</form>
						<?php
						}
						else
						{
						?>
						<form action="index.php?step=4" method="post" name="send" id="send">
							<input type="hidden" name="username" value="<?php echo $_POST['username']?>">
							<input type="hidden" name="password" value="<?php echo $_POST['password']?>">
							<input type="hidden" name="server" value="<?php echo $_POST['server']?>">
							<input type="hidden" name="database" value="<?php echo $_POST['database']?>">
							<input type="hidden" name="port" value="<?php echo $_POST['port']?>">
							<input type="hidden" name="prefix" value="<?php echo $_POST['prefix']?>">
							<input type="hidden" name="apikey" value="<?php echo $_POST['apikey']?>">
							<input type="hidden" name="sb-wp-url" value="<?php echo $_POST['sb-wp-url']?>">
						</form>
						<form action="index.php?step=3" method="post" name="sendback" id="sendback">
							<input type="hidden" name="username" value="<?php echo $_POST['username']?>">
							<input type="hidden" name="password" value="<?php echo $_POST['password']?>">
							<input type="hidden" name="server" value="<?php echo $_POST['server']?>">
							<input type="hidden" name="database" value="<?php echo $_POST['database']?>">
							<input type="hidden" name="port" value="<?php echo $_POST['port']?>">
							<input type="hidden" name="prefix" value="<?php echo $_POST['prefix']?>">
							<input type="hidden" name="apikey" value="<?php echo $_POST['apikey']?>">
							<input type="hidden" name="sb-wp-url" value="<?php echo $_POST['sb-wp-url']?>">
						</form>
						<?php
						}
						?>
					</div>
					&nbsp;
					<div class="p-10" align="center">
						<button onclick="next()" class="btn btn-primary waves-effect" id="button" name="button">Далее</button>
						<button onclick="$('sendback').submit();" name="button" class="btn btn-info waves-effect" id="button">Перепроверить</button>
					</div>
					<input type="hidden" name="postd" value="1">
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
setTimeout("<?php if($errors > 0) { echo "ShowBox('Ошибки', 'Есть ошибки, из-за которых SourceBans не может быть установлен... <br />Устраните ошибки.', 'red', '', true);"; } elseif($warnings > 0) { echo "ShowBox('Предупреждения', 'Есть некоторые предупреждения. SourceBans будет установлен, но некоторые функции не будут работать.', 'red', '', true);"; }?>", 800);
$E('html').onkeydown = function(event){
	var event = new Event(event);
	if (event.key == 'enter' ) next();
};
function next()
{
	var errors = <?php echo $errors?>;
	if(errors > 0)
		ShowBox('Ошибки', 'Есть ошибки, из-за которых SourceBans не может быть установлен... <br />Прочтите документацию и устраните ошибки.', 'red', '', true);
	else
		$('send').submit();
}
</script>
