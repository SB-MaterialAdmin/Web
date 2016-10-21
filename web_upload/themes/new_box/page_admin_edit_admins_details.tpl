<form action="" method="post">
<div id="admin-page-content">

<div id="add-group">
<h3>Детали админа</h3>
<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
  <tr>
    <td valign="top" width="35%"><div class="rowdesc">{help_icon title="Логин админа" message="Это имя пользователя будет использоваться администратором, для входа в панель управления make."}Логин админа </div></td>
    <td><div align="left">
        <input type="text" class="textbox" id="adminname" name="adminname" value="{$user}" />
      </div>
        <div id="adminname.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">{help_icon title="Steam ID" message="STEAM ID админа. Для использования админских прав на сервере."}STEAM ID админа</div></td>
    <td><div align="left">
      <input type="text" class="textbox" id="steam" name="steam" value="{$authid}" />
    </div><div id="steam.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">{help_icon title="Email админа" message="E-mail админа. Необходимо для отправки сообщений от автоматизированной системы или изменения паролей."}E-mail админа </div></td>
    <td><div align="left">
        <input type="text" class="textbox" id="email" name="email" value="{$email}" />
      </div>
        <div id="email.msg" class="badentry"></div></td>
  </tr>
  
  {if $change_pass}
  <tr>
    <td valign="top"><div class="rowdesc">{help_icon title="Пароль" message="Пароль админа для доступа к веб-панели."}Пароль админа </div></td>
    <td><div align="left">
        <input type="password" class="textbox" id="password" name="password" />
      </div>
        <div id="password.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">{help_icon title="Пароль" message="Повторите пароль."}Подтверждение пароля </div></td>
    <td><div align="left">
        <input type="password" class="textbox" id="password2" name="password2" />
      </div>
        <div id="password2.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top" width="35%">
      <div class="rowdesc">
        {help_icon title="Серверный пароль" message="Если этот флажок установлен, то вам нужно указать пароль в игровом сервере, для использования серверных прав админа."}Серверный пароль <small>(<a href="http://wiki.alliedmods.net/Adding_Admins_%28SourceMod%29#Passwords" title="SourceMod Password Info" target="_blank">Подробнее</a>)</small>
      </div>
    </td>
    <td>
      <div align="left">
        <input type="checkbox" id="a_useserverpass" name="a_useserverpass"{if $a_spass} checked="checked"{/if} TABINDEX=6 onclick="$('a_serverpass').disabled = !$(this).checked;" /> <input type="password" TABINDEX=7 class="textbox" name="a_serverpass" id="a_serverpass"{if !$a_spass} disabled="disabled"{/if} />
      </div>
      <div id="a_serverpass.msg" class="badentry"></div>
    </td>
  </tr>
  
  <tr>
    <td valign="middle"><div class="rowdesc">Админка истекает через:</div></td>
    <td><div align="left">
        <div>{$expired_text}</div>
      </div>
  </tr>
  <tr>
    <td valign="middle"><div class="rowdesc">{help_icon title="Срок" message="Введите срок админа в днях. Если хотите изменить срок на 'Навсегда', напишите '000'."}Изменить срок </div></td>
    <td><div align="left">
        <input type="text" class="textbox" id="period" name="period" value="" />
      </div>
        <div id="period.msg" class="badentry"></div></td>
  </tr>
  
  <tr>
    <td valign="top" width="35%">
      <div class="rowdesc">
		{help_icon title="Skype" message="Введите skype админа для связи с ним через Skype."}skype
      </div>
    </td>
    <td>
      <div align="left">
        <input type="text" TABINDEX=7 class="textbox" name="skype" id="skype" value="{$skype}" />
      </div>
      <div id="skype.msg" class="badentry"></div>
    </td>
  </tr>
  
  <tr>
    <td valign="top" width="35%">
      <div class="rowdesc">
		{help_icon title="Комментарий" message="Введите комментарий для админа."}Коммент админу
      </div>
    </td>
    <td>
      <div align="left">
        <input type="text" TABINDEX=7 class="textbox" name="comment" id="comment" value="{$comment}" />
      </div>
      <div id="comment.msg" class="badentry"></div>
    </td>
  </tr>
  
  <tr>
    <td valign="top" width="35%">
      <div class="rowdesc">
		{help_icon title="VK" message="Введите ID админа в соцсети 'ВКонтакте'."}ВК админа
      </div>
    </td>
    <td>
      <div align="left">
        <input type="text" TABINDEX=7 class="textbox" name="vk" id="vk" value="{$vk}" />
      </div>
      <div id="vk.msg" class="badentry"></div>
    </td>
  </tr>
  
  {/if}
  
 </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
      {sb_button text="Сохранить изменения" class="ok" id="editmod" submit=true}
	&nbsp;
	  {sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="back" submit=false} 
      </td>
  </tr>
</table>
</div></div></form>
