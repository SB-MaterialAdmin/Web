-{if NOT $permission_rcon}-
	Доступ запрещен!
-{else}-
<div id="admin-page-content">
<div id="1">


<h3>RCON консоль</h3>
<div align="center" width="90%">
<div id="rcon" style="overflow:auto;
			background-color:#efefef;
			border: 1px solid #999;
			padding: 3px;
			height: 250px;
			width: 90%;" align="left">

<pre>



<div id="rcon_con">***********************************************************<br />**&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**<br />*&nbsp;SourceBans RCON console&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*<br />*&nbsp;Введите команду ниже и нажмите "Enter"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*<br />*&nbsp;Введите 'clr' чтобы очистить консоль&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*<br />**&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**<br />***********************************************************<br />
</div>
</pre>
</div>
<br />
Команда: <input type="text" style="font-family:verdana, tahoma, arial;font-size:10px;width:500px" id="cmd"> 
<input type="button" onclick="SendRcon();" id="rcon_btn" value="Отправить">
</div>
</div></div>
<script>

$E('html').onkeydown = function(event){
    var event = new Event(event);
    if (event.key == 'enter' ) SendRcon();
};

function SendRcon()
{
	xajax_SendRcon('-{$id}-', $('cmd').value, true);
	 $('cmd').value='Выполнение, ждите...'; $('cmd').disabled='true'; $('rcon_btn').disabled='true';
	 
}
</script>
-{/if}-
