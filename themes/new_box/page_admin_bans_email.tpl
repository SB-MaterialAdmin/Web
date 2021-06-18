<h3>E-mail игрока  <i>({$email_addr})</i></h3>
<table width="90%" style="border-collapse:collapse;" id="group.details" cellpadding="3">
	<tr>
    	<td valign="top" width="35%">
    		<div class="rowdesc">{help_icon title="Тема" message="Введите тему сообщения."}Тема </div>
    	</td>
    	
    <td><div align="left">
      <input type="text" TABINDEX=1 class="textbox" id="subject" name="subject" />
    </div><div id="subject.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">{help_icon title="Сообщение" message="Пишите Ваше сообщение тут."}Сообщение </div></td>
    <td><div align="left">
       <textarea class="textbox" TABINDEX=2 cols="35" rows="7" id="message" name="message"></textarea>
    </div><div id="message.msg" class="badentry"></div></td>
  </tr>
 	

 <tr>
    <td>&nbsp;</td>
		<td>
      		{sb_button text="Отправить E-mail" onclick="$email_js" class="ok" id="aemail" submit=false}
     		 &nbsp;
      		{sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="back" submit=false}
     	</td>
 	</tr>
</table>

