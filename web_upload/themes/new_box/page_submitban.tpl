<table style="width: 101%; margin: 0 0 -2px -2px;">
	<tr>
		<td colspan="3" class="listtable_top"><b>Пожаловаться на игрока</b></td>
	</tr>
</table>
<div id="submit-main">
	Здесь вы сможете подать жалобу на игрока, который нарушает правила игрового сервера. При подаче жалобы мы просим Вас заполнить все поля, и быть максимально информативным в комментариях. Это гарантирует, что ваша жалоба обработается гораздо быстрее.<br /><br />
    Чтобы узнать как записывать Демо нажмите <a href="javascript:void(0)" onclick="ShowBox('Как записать демо', 'В то время как вы наблюдаете за нарушителем, нажмите клавишу "~(ё)" на клавиатуре чтобы вызвать консоль. В консоли введите record [имя_записи] и нажмите клавишу Enter. Файл записи будет находиться в папке с Модом', 'blue', '', true);">здесь</a><br /><br />
<form action="index.php?p=submit" method="post" enctype="multipart/form-data">
<input type="hidden" name="subban" value="1">
<table cellspacing='10' width='100%' align='center'>
<tr>
	<td colspan="3">
		Детали бана:	</td>
</tr>
<tr>
	<td width="20%">
		SteamID игрока:</td>
	<td>
		<input type="text" name="SteamID" size="40" maxlength="64" value="{$STEAMID}" class="textbox" style="width: 250px;" />
	</td>
</tr>
<tr>
	<td width="20%">
		IP игрока:</td>
	<td>
		<input type="text" name="BanIP" size="40" maxlength="64" value="{$ban_ip}" class="textbox" style="width: 250px;" />
	</td>
</tr>
<tr>
	<td width="20%">
        Ник игрока<span class="mandatory">*</span>:</td>
	<td>
        <input type="text" size="40" maxlength="70" name="PlayerName" value="{$player_name}" class="textbox" style="width: 250px;" /></td>
</tr>
<tr>
	<td width="20%" valign="top">
		Причина бана<span class="mandatory">*</span>:<br />
		(Введите расширенный комментарий к бану. Никаких комментариев типа: "Читак")	</td>
	<td><textarea name="BanReason" cols="30" rows="5" class="textbox" style="width: 250px;">{$ban_reason}</textarea></td>
    </tr>
<tr>
	<td width="20%">
		Ваше имя:	</td>
	<td>
		<input type="text" size="40" maxlength="70" name="SubmitName" value="{$subplayer_name}" class="textbox" style="width: 250px;" />	</td>
    </tr>

<tr>
	<td width="20%">
		Ваш E-mail<span class="mandatory">*</span>:	</td>
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
			<option value="0">Другой сервер / Нет в списке</option>
		</select> 
    </td>
    </tr>
<tr>
	<td width="20%">
		Загрузить демо:	</td>
	<td>
		<input name="demo_file" type="file" size="25" class="file" style="width: 268px;" /><br />
		Предупреждение: Разрешено загружать файлы только в формате <a href="http://www.winzip.com" target="_blank">ZIP</a>, <a href="http://www.rarlab.com" target="_blank">RAR</a>, <a href="http://www.7-zip.org" target="_blank">7Z</a>, <a href="http://www.bzip.org" target="_blank">BZ2</a> или <a href="http://www.gzip.org" target="_blank">GZ</a> </td>
    </tr>
<tr>
	<td width="20%"><span class="mandatory">*</span> = Обязательные поля</td>
	<td>
		{sb_button text=Подтвердить onclick="" class=ok id=save submit=true}
	</td>
    <td>&nbsp;</td>
</tr>
</table>
</form>
<b>Что случиться если кого-то забанят?</b><br />
Если кого-то забанят, то его STEAMID или IP адрес будут включены в эту базу данных SourceBans, и каждый раз этот игрок пытается подключиться к одному из наших серверов он / она будет заблокирован и получит сообщение о том, что заблокирован в SourceBans. 
</div>
