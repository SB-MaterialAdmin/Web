<table style="width: 101%; margin: 0 0 -2px -2px;">
	<tr>
		<td colspan="3" class="listtable_top"><b>Апелляция бана</b></td>
	</tr>
</table>
<div id="submit-main">
Перед тем как продолжить, проверьте <a href="index.php?p=banlist">банлист</a> на наличие Вашего бана.<br />
Если Вы считаете, что бан выдан ложно, то тогда можете написать протест.<br /><br />
<form action="index.php?p=protest" method="post">
<input type="hidden" name="subprotest" value="1">
<table cellspacing='10' width='100%' align='center'>
<tr>
	<td colspan="3">
		Детали заявки:	</td>
</tr>
<tr>
	<td width="20%">Тип бана:</td>
	<td>
		<select id="Type" name="Type" class="select" style="width: 250px;" onChange="changeType(this[this.selectedIndex].value);">
			<option value="0">Steam ID</option>
			<option value="1">IP Адрес</option>
		</select>
	</td>
</tr>
<tr id="steam.row">
	<td width="20%">
		Ваш SteamID<span class="mandatory">*</span>:</td>
	<td>
		<input type="text" name="SteamID" size="40" maxlength="64" value="{$steam_id}" class="textbox" style="width: 223px;" />
	</td>
</tr>
<tr id="ip.row" style="display: none;">
	<td width="20%">
		Ваш IP<span class="mandatory">*</span>:</td>
	<td>
		<input type="text" name="IP" size="40" maxlength="64" value="{$ip}" class="textbox" style="width: 223px;" />
	</td>
</tr>
<tr>
	<td width="20%">
        Ник<span class="mandatory">*</span>:</td>
	<td>
        <input type="text" size="40" maxlength="70" name="PlayerName" value="{$player_name}" class="textbox" style="width: 223px;" /></td>
    </tr>
<tr>
	<td width="20%" valign="top">
		Причина, по которой Вас должны разбанить <span class="mandatory">*</span>: (От информативности/убедительности зависит Ваш разбан.) </td>
	<td><textarea name="BanReason" cols="30" rows="5" class="textbox" style="width: 223px;">{$reason}</textarea></td>
    </tr>
<tr>
	<td width="20%">
		Ваш Email<span class="mandatory">*</span>:	</td>
	<td>
		<input type="text" size="40" maxlength="70" name="EmailAddr" value="{$player_email}" class="textbox" style="width: 223px;" /></td>
    </tr>
<tr>
	<td width="20%"><span class="mandatory">*</span> = Обязательные поля</td>
	<td>
		{sb_button text=Отправить class=ok id=alogin submit=true}
	</td>
    <td>&nbsp;</td>
</tr>
</table>
</form>
<b>Что произойдёт когда я отправлю аппеляцию бана?</b><br />
  Администрация будет уведомлена о Вашем протесте. После они обязательно проверят детали и обстоятельства бана. Обычно срок рассмотрения заявки 24 часа, но помните, что для каждой заявки срок индивидуален.<br /><br />
  <b>Примечание:</b> Отправление Администрации заявок с угрозами или мольбами о разбане приведут лишь к удалению Вашей заявки. Уважайте труд и время Администрации!
</div>
