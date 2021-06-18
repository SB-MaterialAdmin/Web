<?php
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
global $userbank, $theme;

	echo '<div id="admin-page-content">';
	if(!$userbank->HasAccess(ADMIN_OWNER))
	{
		echo '<div id="0" style="display:none;">Доступ запрещен!</div>';
	} else {
		
		if(($_GET['o'] == "del") && isset($_GET['o'])){
			if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
				echo '<script>setTimeout(\'ShowBox("Ошибка", "ID бана не указан!", "red", "index.php");\', 1200);</script>';
				PageDie();
			}else{
				$qwer = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_vay4er` WHERE aid = '".(int)$_GET['id']."'");
				if($qwer){
					$qww = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_vay4er` WHERE aid = '".(int)$_GET['id']."'");
					if($qww){
						echo '<script>setTimeout(\'ShowBox("Успешно", "Ваучер был успешно удален!", "green", "index.php?p=admin&c=pay_card");\', 1200);</script>';
					}
				}else{
					echo '<script>setTimeout(\'ShowBox("Ошибка", "ID бана не указан!", "red", "index.php");\', 1200);</script>';
				}
			}
		}
		#########[list]###############
		echo '<div id="0" style="display:none;">';
			
			
			
			$cards = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_vay4er` ORDER BY `activ` DESC");
			$card_list = array();
			foreach($cards AS $card)
			{
				$info['aid'] = $card['aid'];
				$info['activ'] = $card['activ'];
				$info['value'] = $card['value'];
				$info['days'] = $card['days'];
				$info['group_web'] = $card['group_web'];
				$info['group_srv'] = $card['group_srv'];
				$info['servers'] = $card['servers'];
				array_push($card_list, $info);
			}
			$theme->assign('card_list', $card_list);
			
					
			$theme->display('page_admin_pay_list.tpl');	
		echo '</div>';
		#########/[list]###############
		
		#########[add]###############
		echo '<div id="1" style="display:none;">';
			
			//
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
			//
			
			// Add Page
			$server_admin_group_list = 	$GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_srvgroups`");
			$server_group_list = 		$GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_groups` WHERE type != 3");

			echo '<div id="1" style="display:none;">';
				$theme->assign('server_admin_group_list', $server_admin_group_list);
				$theme->assign('server_group_list', $server_group_list);
				$theme->display('page_admin_admins_add.tpl');
			echo '</div>';


			
			if(isset($_POST['pay_card_admin'])){
				if ($_POST['pay_card_admin'] == "pay_card_add"){
					if(($_POST['card_key'] != "") && ($_POST['card_exp'] >= 0) && ($_POST['card_gr_web'] != "")){
						
						$key_vr = $_POST['card_key'];
						$key_vr = preg_replace("/[^0-9]/", '', $key_vr);
						$exp_vr = $_POST['card_exp'];
						$exp_vr = preg_replace("/[^0-9]/", '', $exp_vr);
						if($exp_vr == ""){
							$exp_vr = "0";
						}
						$gr_web_vr = $_POST['card_gr_web'];
						$gr_srv_vr = $_POST['card_gr_srv'];
						$srv_check = $_POST['srv_check_int'];
						
						if((stristr($srv_check, ',') && stristr($srv_check, 's')) == FALSE){
							if($srv_check != "-1"){
								$srv_check = "";
							}
						}
						
						$ifvay4_shon = $GLOBALS['db']->GetOne("SELECT COUNT(`value`) FROM `".DB_PREFIX."_vay4er` WHERE value = '".$key_vr."'");
						// err
						if(strlen($key_vr) <= 15){
							echo "<script>setTimeout(\"ShowBox('Ваучер', 'Ошибка, ключ должен содержать 16 символов!', 'red', 'index.php?p=admin&c=pay_card#^1');\", 1200);</script>";
						}elseif($ifvay4_shon == "0" || $ifvay4_shon == "" || $ifvay4_shon < 1){
							
							$edit = $GLOBALS['db']->Execute("INSERT INTO `sb_vay4er` (`activ`, `value`, `days`, `group_web`, `group_srv`, `servers`)
								VALUES (1, ?, ?, ?, ?, ?)", array($key_vr, $exp_vr, $gr_web_vr, $gr_srv_vr, $srv_check));
							
							if($edit){
								echo "<script>setTimeout(\"ShowBox('Ваучер', 'Ваучер был успешно добавлен!', 'green', 'index.php?p=admin&c=pay_card');\", 1200);</script>";
							}else{
								echo "<script>setTimeout(\"ShowBox('Ваучер', 'Ошибка, не могу добавить ключ!', 'red', 'index.php?p=admin&c=pay_card');\", 1200);</script>";
							}
						}else{
							echo "<script>setTimeout(\"ShowBox('Ваучер', 'Ошибка, данный ключ уже есть в системе!', 'red', '', true);\", 1200);</script>";
						}
					}else{
						echo "<script>setTimeout(\"ShowBox('Ваучер', 'Ошибка, заполните все данные правильно!', 'red', '', true);\", 1200);</script>";
					}
				}
			}
			$theme->display('page_admin_pay_add.tpl');	
		echo '</div>';
		#########/[add]###############
	}
?>
<script>
			//function getRandomNum(lbound, ubound) {
            //    return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
			//}
			//function getRandomChar() {
			//	var upperChars = "0123456789";
			//	var charSet = "";
			//	charSet += upperChars;
			//	return charSet.charAt(getRandomNum(0, charSet.length));
			//}
			//function getPassword(length) {
			//	var rc = "";
			//	if (length > 0)
			//		for (var idx = 0; idx < length; ++idx) {
			//			rc = rc + getRandomChar();
			//		}
			//	return rc;
			//	
			//}
			function getRandomInt(min, max) {
				return Math.floor(Math.random() * (max - min)) + min;
			}
			function getPassword(length) {
				var rc = "";
				if (length > 0)
					for (var idx = 0; idx < length; ++idx)
						rc = rc + getRandomInt(0,9);
				return rc;
			}
			$('card_key').value = getPassword(16);
			
			function Check_cal(){
				var el = document.getElementsByName('servers[]');
				var svr_vv = '';
				for(i=0;i<el.length;i++){
					if(el[i].checked){
						if(el[i].value == "-1"){
							svr_vv = "-1";
						}else{
							svr_vv = svr_vv + ',' + el[i].value;
						}
					}
				}
				$('srv_check_int').value = svr_vv;
			}

</script>