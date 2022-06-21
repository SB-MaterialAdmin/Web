<?php
	if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}
	$errors = 0;
	$warnings = 0;

	require(ROOT . "../includes/adodb/adodb.inc.php");
	include_once(ROOT . "../includes/adodb/adodb-errorhandler.inc.php");
	$server = "mysqli://" . $_POST['username'] . ":" . $_POST['password'] . "@" . $_POST['server'] . ":" . $_POST['port'] . "/" . $_POST['database'];
	$db = ADONewConnection($server);
	$db->Execute("SET NAMES `utf8`");
	
	$file = file_get_contents(INCLUDES_PATH . "/struc.sql");
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

				<div class="lv-item media active">
					<div class="lv-avatar bgm-red pull-left">4</div>
					<div class="media-body">
						<div class="lv-title">Шаг: Создание таблиц</div>
						<div class="lv-small"><i class="zmdi zmdi-badge-check zmdi-hc-fw c-green"></i> Текущий шаг</div>
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
		
		<div class="ms-body" id="submit-main">
			<div class="listview lv-message">
				<div class="lv-header-alt clearfix">
					<div class="lvh-label">
						<span class="c-black">Информация</span>
					</div>
				</div>

				<div class="lv-body p-15">
					На этой странице будет происходишь установка данных в базу данных.
				</div>
				
				<div class="lv-header-alt clearfix">
					<div class="lvh-label">
						<span class="c-black">Установка таблиц</span>
					</div>
				</div>
				
				<div class="lv-body p-15">
					<div class="col-sm-12">
						<?php if($errors > 0){
							?>
							<script>setTimeout("ShowBox('Ошибка', 'Ошибка создания структуры базы данных. Пожалуйста, прочитайте сообщения выше, чтобы исправить проблемы.', 'red', '', true);", 1200);</script>
							<?php
						}else{
							?>
							<script>setTimeout("ShowBox('Успешно', 'Таблицы успешно созданы', 'green', '', true);", 1200);</script>
							<?php
						}
						?>
						
						<form action="index.php?step=5" method="post" name="send" id="send">
							<input type="hidden" name="username" value="<?php echo $_POST['username']?>">
							<input type="hidden" name="password" value="<?php echo $_POST['password']?>">
							<input type="hidden" name="server" value="<?php echo $_POST['server']?>">
							<input type="hidden" name="database" value="<?php echo $_POST['database']?>">
							<input type="hidden" name="port" value="<?php echo $_POST['port']?>">
							<input type="hidden" name="prefix" value="<?php echo $_POST['prefix']?>">
							<input type="hidden" name="apikey" value="<?php echo $_POST['apikey']?>">
							<input type="hidden" name="sb-wp-url" value="<?php echo $_POST['sb-wp-url']?>">
						</form>
					</div>
					<br /><br />
					<div class="p-10" align="center">
						<button type="submit" onclick="next()" name="button" class="btn btn-primary waves-effect" id="button">Ok</button>
					</div>
				</div>
			</div>
			<br /><br />
		</div>
</div>

<script type="text/javascript">
$E('html').onkeydown = function(event){
	var event = new Event(event);
	if (event.key == 'enter' ) next();
};
function next()
{
	var errors = <?php echo $errors?>;
	if(errors > 0)
		ShowBox('Ошибки', 'Есть некоторые ошибки, поэтому SourceBans не может быть установлен. <br />Прочтите документацию и устраните ошибки.', 'red', '', true);
	else
		$('send').submit();
}
</script>
