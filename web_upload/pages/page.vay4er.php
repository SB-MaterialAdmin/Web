<?php
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
global $theme, $userbank;
	if($GLOBALS['config']['page.vay4er']!="1"){
		CreateRedBox("Ошибка", "Страница отключена.");
		PageDie();
	}
if(isset($_POST['pay_v4']) && !empty($_POST['pay_v4']))
{
	if($_POST['kapcha'] != $_SESSION['rand_code']){
		//echo "Капча введена неверно";
		echo '<div class="alert alert-danger" role="alert" id="msg-red"><h4>Ошибка!</h4><span class="p-l-10">Проверочный код - не верен!</span></div>';
		require(TEMPLATES_PATH . "/footer.php");
		exit();
	}
	preg_match("@^(?:http://)?([^/]+)@i", $_SERVER['HTTP_HOST'], $match);	
	if($match[0] != $_SERVER['HTTP_HOST']) 
	{ 
		echo '<div class="alert alert-danger" role="alert" id="msg-red"><h4>Ошибка!</h4><span class="p-l-10">Произошла неизвестная ошибка.</span></div>';
	
		require(TEMPLATES_PATH . "/footer.php");
		$log = new CSystemLog("w", "Попытка взлома", "Попытка активации ваучера с использованием: " . $_SERVER['HTTP_HOST']);
		exit();
	}
	
	$validation = $_POST['pay_v4'];
	if(strlen($validation) < 19)
	{
		echo '<div class="alert alert-danger" role="alert" id="msg-red"><h4>Ошибка!</h4><span class="p-l-10">Ваучер слишком короткий.</span></div>';
		require(TEMPLATES_PATH . "/footer.php");
		exit();
	}
	
	$validation = str_replace("-", "", $validation);
	$validation = RemoveCode($validation);
	$validation = preg_replace("/[^0-9]/", '', $validation);
	$qwr = $GLOBALS['db']->GetOne("SELECT `activ` FROM `" . DB_PREFIX . "_vay4er` WHERE `value` = '".$validation."'");
	if($qwr != "0" && $qwr == "1"){
		$vaxye_vso = "1";
		//echo "VSO OK";
		$user_group_web = $GLOBALS['db']->GetOne("SELECT `group_web` FROM `" . DB_PREFIX . "_vay4er` WHERE `value` = '".$validation."'");
		if($user_group_web == "" || $user_group_web == "0"){
			$user_group_web = "Не указана/Нет группы";
		}
		$user_group_srv = $GLOBALS['db']->GetOne("SELECT `group_srv` FROM `" . DB_PREFIX . "_vay4er` WHERE `value` = '".$validation."'");
		if($user_group_srv == "" || $user_group_srv == "0"){
			$user_group_srv = "Не указана/Нет группы";
		}
		$pay_days = $GLOBALS['db']->GetOne("SELECT `days` FROM `" . DB_PREFIX . "_vay4er` WHERE `value` = '".$validation."'");
		if($pay_days == "0"){
			$pay_days_t = "Навсегда";
		}else{
			$pay_days_t = $pay_days." Дней";
		}
		$theme->assign('days', $pay_days_t);
		$theme->assign('gr_web', $user_group_web);
		$theme->assign('gr_srv', $user_group_srv);
		$theme->assign('klu4ik', $validation);
		
		$servers_num = $GLOBALS['db']->GetOne("SELECT `servers` FROM `" . DB_PREFIX . "_vay4er` WHERE `value` = '".$validation."'");
		$add_srv = "";
		$add_srv_sql = "''";
		
		if($servers_num == ""){
			$add_srv = "
			var el = document.getElementsByName('servers[]');
			var svr = '';
			for(i=0;i<el.length;i++){
				if(el[i].checked){
					svr = svr + ',' + el[i].value;
				}
			}";
			$add_srv_sql = "svr";
		}
		
		echo "<script>		
		function AddAdmin_pay(){	
			".$add_srv."
			xajax_AddAdmin_pay('','', document.getElementById('user_login').value, //Admin name
							document.getElementById('user_steamid').value, //Admin Steam
							document.getElementById('user_email').value, // Email
							document.getElementById('password').value,//passwrds
							document.getElementById('password2').value,
							'', //servergroup
							'', 
							'-1',
							0,
							0,
							'',
							".$add_srv_sql.",
							document.getElementById('skype').value,
							'',
							document.getElementById('vk').value,
							'".$validation."');}</script>";
		$theme->assign('servers', $servers_num);
	}else{
		$vaxye_vso = "0";
		//echo "VSO NE OK 1";
	}
}
else
{
	$vaxye_vso = "0";
	//echo "VSO NE OK 2";
}


$servers = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_servers`");
$server_list = array();
$serverscript = "<script type=\"text/javascript\">";
foreach($servers AS $server)
{
    $serverscript .= "xajax_ServerHostPlayers('".$server['sid']."', 'id', 'sa".$server['sid']."');";
	$info['sid'] = $server['sid'];
	$info['ip'] = $server['ip'];
	$info['port'] = $server['port'];
	array_push($server_list, $info);
}


$serverscript .= "</script>";

$theme->assign('server_list', $server_list);
$theme->assign('server_script', $serverscript);


$theme->assign('param', $vaxye_vso);
$theme->display('page_vay4er.tpl');
?>