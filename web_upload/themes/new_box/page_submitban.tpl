<table style="width: 101%; margin: 0 0 -2px -2px;">
	<tr>
		<td colspan="3" class="listtable_top"><b>Пожаловаться на игрока</b></td>
	</tr>
</table>
<div id="submit-main">
	Здесь Вы можете подать заявку на бан игрока, нарушающего правила сервера. Когда подаёте заявку, заполняйте все поля, и донесите Ваш комментарий максимально информативно. Это послужит залогом скорейшего рассмотрения Вашей заявки.<br /><br />
    Краткая инструкция по записи демо <a href="javascript:void(0)" onclick="ShowBox('Как записать Демку?', 'В тот момент, когда Вы наблюдаете за нужным игроком, нажмите <b>~</b> (</b>`</b>/<b>Ё</b>) на Вашей клавиатуре. В открывшуюся консоль введите <b>record [demoname]</b> и нажмите <b>Enter</b>. Также пропишите команду <b>status</b> для получения дополнительной информации о сервере. Чтобы остановить запись, введите <b>stop</b>. Файл демки будет лежать в папке <b>cstrike</b>.', 'blue', '', true);">здесь</a><br /><br />
<form action="index.php?p=submit" method="post" enctype="multipart/form-data">
<input type="hidden" name="subban" value="1">
<table cellspacing='10' width='100%' align='center'>
<tr>
	<td colspan="3">
		Детали бана:	</td>
</tr>
<tr>
	<td width="20%">
		SteamID нарушителя:</td>
	<td>
		<input type="text" name="SteamID" size="40" maxlength="64" value="{$STEAMID}" class="textbox" style="width: 250px;" />
	</td>
</tr>
<tr>
	<td width="20%">
		IP нарушителя:</td>
	<td>
		<input type="text" name="BanIP" size="40" maxlength="64" value="{$ban_ip}" class="textbox" style="width: 250px;" />
	</td>
</tr>
<tr>
	<td width="20%">
        Никнейм нарушителя<span class="mandatory">*</span>:</td>
	<td>
        <input type="text" size="40" maxlength="70" name="PlayerName" value="{$player_name}" class="textbox" style="width: 250px;" /></td>
</tr>
<tr>
	<td width="20%" valign="top">
		Комментарий<span class="mandatory">*</span>:<br />
		(Пожалуйста, пишите информативные комментарии. Комментарии типа "читер" не рассматриваются.)	</td>
	<td><textarea name="BanReason" cols="30" rows="5" class="textbox" style="width: 250px;">{$ban_reason}</textarea></td>
    </tr>
<tr>
	<td width="20%">
		Ваш ник:	</td>
	<td>
		<input type="text" size="40" maxlength="70" name="SubmitName" value="{$subplayer_name}" class="textbox" style="width: 250px;" />	</td>
    </tr>

<tr>
	<td width="20%">
		Ваш Email<span class="mandatory">*</span>:	</td>
	<td>
		<input type="text" size="40" maxlength="70" name="EmailAddr" value="{$player_email}" class="textbox" style="width: 250px;" />	</td>
    </tr>
<tr>
	<td width="20%">
		Сервер<span class="mandatory">*</span>:	</td>
	<td colspan="2">
        <select id="server" name="server" class="select" style="width: 277px;">
			<option value="-1">-- Выберите сервер --</option>
			{foreach from="$server_list" item="server}
				<option value="{$server.sid}" {if $server_selected == $server.sid}selected{/if}>{$server.hostname}</option>
			{/foreach}
			<option value="0">Другой сервер, не представленный здесь</option>
		</select> 
    </td>
    </tr>
<tr>
	<td width="20%">
		Загрузка демо:	</td>
	<td>
		<input name="demo_file" type="file" size="25" class="file" style="width: 268px;" /><br />
		Примечание: Только форматы <b>.dem<b>, <a href="http://www.winzip.com" target="_blank">.zip</a>, <a href="http://www.rarlab.com" target="_blank">.rar</a>, <a href="http://www.7-zip.org" target="_blank">.7z</a>, <a href="http://www.bzip.org" target="_blank">.bz2</a> или <a href="http://www.gzip.org" target="_blank">.gz</a>.	</td>
    </tr>
<tr>
	<td width="20%"><span class="mandatory">*</span> = Обязательные поля</td>
	<td>
		{sb_button text=Отправить onclick="" class=ok id=save submit=true}
	</td>
    <td>&nbsp;</td>
</tr>
</table>
</form>
<b>Что случится, если кто-то окажется забаненным?</b><br />
Если кто-то получает бан, то его уникальный STEAMID или IP заносятся в Базу Данных SourceBans, и каждый раз, когда игрок попытается подключиться к серверу, он/она будут блокироваться с уведомлением о бане. 
</div>
