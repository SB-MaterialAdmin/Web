<?php
/**************************************************************************
 * Эта программа является частью SourceBans MATERIAL Admin.
 *
 * Все права защищены © 2016-2017 Sergey Gut <webmaster@kruzefag.ru>
 *
 * SourceBans MATERIAL Admin распространяется под лицензией
 * Creative Commons Attribution-NonCommercial-ShareAlike 3.0.
 *
 * Вы должны были получить копию лицензии вместе с этой работой. Если нет,
 * см. <http://creativecommons.org/licenses/by-nc-sa/3.0/>.
 *
 * ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО
 * ГАРАНТИЙ, ЯВНЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ,
 * ГАРАНТИИ ПРИГОДНОСТИ ДЛЯ КОНКРЕТНЫХ ЦЕЛЕЙ И НЕНАРУШЕНИЯ. НИ ПРИ КАКИХ
 * ОБСТОЯТЕЛЬСТВАХ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ЗА
 * ЛЮБЫЕ ПРЕТЕНЗИИ, ИЛИ УБЫТКИ, НЕЗАВИСИМО ОТ ДЕЙСТВИЯ ДОГОВОРА,
 * ГРАЖДАНСКОГО ПРАВОНАРУШЕНИЯ ИЛИ ИНАЧЕ, ВОЗНИКАЮЩИЕ ИЗ, ИЛИ В СВЯЗИ С
 * ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ
 * ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ.
 *
 * Эта программа базируется на работе, охватываемой следующим авторским
 *                                                           правом (ами):
 *
 *  * SourceBans ++
 *    Copyright © 2014-2016 Sarabveer Singh
 *    Выпущено под лицензией CC BY-NC-SA 3.0
 *    Страница: <https://sbpp.github.io/>
 *
 ***************************************************************************/

global $theme, $userbank;

if(isset($_GET['validation'],$_GET['email']) && !empty($_GET['email']) && !empty($_GET['validation']))
{  
	$email = $_GET['email'];
	$validation = $_GET['validation'];
	$tryHack = false;
	
	if (is_array($email) || is_array($validation))
		$tryHack = true;
	
	if ($tryHack) {
		CreateRedBox("Ошибка", "Была зафиксирована попытка взлома системы через некорректно построенный запрос. Данная попытка была записана в системный лог.");
		require(TEMPLATES_PATH . "/footer.php");
		$log = new CSystemLog("e", "Попытка взлома", "Произошла попытка взлома системы с использованием некорректно построенного запроса SQL.");
		exit();
	}
	
	preg_match("@^(?:http://)?([^/]+)@i", $_SERVER['HTTP_HOST'], $match);

	if($match[0] != $_SERVER['HTTP_HOST']) 
	{ 
		echo '<div class="alert alert-danger" role="alert" id="msg-red"><h4>Ошибка!</h4><span class="p-l-10">Произошла неизвестная ошибка.</span></div>';
	
		require(TEMPLATES_PATH . "/footer.php");
		$log = new CSystemLog("w", "Попытка взлома", "Попытка сброса пароля с использованием: " . $_SERVER['HTTP_HOST']);
		exit();
	}

	if(strlen($validation) < 60)
	{
		echo '<div class="alert alert-danger" role="alert" id="msg-red"><h4>Ошибка!</h4><span class="p-l-10">Строка проверки является слишком короткой.</span></div>';
	
		require(TEMPLATES_PATH . "/footer.php");
		exit();
	}
	
	$q = $GLOBALS['db']->GetRow("SELECT aid, user FROM `" . DB_PREFIX . "_admins` WHERE `email` = ? && `validate` IS NOT NULL && `validate` = ?", array($email, $validation));
	if($q)
	{
		$newpass = generate_salt(MIN_PASS_LENGTH+8);
		$query = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET `password` = '" . $userbank->encrypt_password($newpass) . "', validate = NULL WHERE `aid` = ?", array($q['aid']));
		$message = "Привет " . $q['user'] . ",\n\n";
		$message .= "Ваш пароль был успешно сброшен.\n";
		$message .= "Ваш пароль изменен на: ".$newpass."\n\n";
		$message .= "Войдите в ваш аккаунт SourceBans и смените пароль.\n";

		$headers = 'From: SourceBans@' . $_SERVER['HTTP_HOST'] . "\n" .
		'X-Mailer: PHP/' . phpversion();
		$m = EMail($email, "Сброс пароля SourceBans", $message, $headers);
		
		echo '<div class="alert alert-success" role="alert" id="msg-blue"><h4>Успешно!</h4><span class="p-l-10">Ваш пароль был сброшен и отправлен вам на почту.<br />Проверьте папку "Спам" тоже.<br />Пожалуйста, войдите, используя этот пароль, затем смените пароль в вашей учетной записи на свой, нормальный :).</span></div>';
	}
	else 
	{
		echo '<div class="alert alert-danger" role="alert" id="msg-red"><h4>Ошибка!</h4><span class="p-l-10">Строка проверки не соответствует адресу электронной почты для запроса на сброс.</span></div>';
	}
}else 
{
	$theme->display('page_lostpassword.tpl');
}
?>
