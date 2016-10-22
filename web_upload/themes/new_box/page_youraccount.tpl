<div class="admin-content" id="admin-page-content">


<div id="0"> <!-- div ID 0 is the first 'panel' to be shown -->
	<div class="card">
		<div class="card-header">
			<h2>Привелегии <small>Ниже приведен список разрешений, которые вам доступны.</small></h2>
		</div>
		<div class="card-body card-padding">
			<div class="table-responsive" id="banlist">
				<table cellspacing="0" cellpadding="0" class="table">
					<tr>
						<td width="33%" valign="top">-{$web_permissions}-</td>
						<td width="33%" valign="top">-{$server_permissions}-</td>
						<td width="34%" valign="top"><p class="c-blue">Срок окончания</p><ul class="clist clist-star"><li>-{$expired_time}-</li></ul></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

-{if $allow_change_inf}-
<div id="4" style="display:none;"> <!-- div ID 0 is the first 'panel' to be shown -->
	<div class="card">
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Связь <small>Ваша контактная информация, для связи с Вами.</small></h2>
			</div>
			<div class="card-body card-padding" id="group.details">
					<div class="form-group">
						<label for="current_vk" class="col-sm-3 control-label">ВКонтакте</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="text" class="form-control input-sm" id="current_vk" name="current_vk" -{if NOT $vk}- placeholder="Введите данные(только ID, без 'https://vk.com/')" -{else}- value="-{$vk}-"-{/if}->
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="current_skype" class="col-sm-3 control-label">Skype</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="text" class="form-control input-sm" id="current_skype" name="current_skype" -{if NOT $skype}- placeholder="Введите данные(логин)"-{else}-value="-{$skype}-"-{/if}->
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label"></label>
						<div class="col-sm-9">
							<div class="fg-line">
								<button type="submit" onclick="xajax_ChangeAdminsInfos(-{$user_aid}-, $('current_vk').value, $('current_skype').value);" name="button" class="btn btn btn-primary btn-sm waves-effect" id="button">Сохранить</button>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>
-{/if}-

<div id="1" style="display:none;"> <!-- div ID 1 is the second 'panel' to be shown -->
	<div class="card">
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Безопасность <small>Если ваш аккаунт под угрозой, срочно смените пароль от своего аккаунта!</small></h2>
			</div>
			<div class="card-body card-padding" id="group.details">
					<div class="form-group">
						<label for="current" class="col-sm-3 control-label">Текущий пароль</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="password" onblur="xajax_CheckPassword(-{$user_aid}-, $('current').value);" class="form-control input-sm" id="current" name="current" placeholder="Введите данные">
							</div>
							<div id="current.msg"></div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="pass1" class="col-sm-3 control-label">-{help_icon title="Новый пароль" message="Введите новый, желаемый пароль тут. Минимальная длинна должна быть: $min_pass_len"}- Новый пароль</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="password" onkeyup="checkYourAcctPass();" class="form-control input-sm" id="pass1" name="pass1" placeholder="Введите данные">
							</div>
							<div id="pass1.msg"></div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="pass2" class="col-sm-3 control-label">-{help_icon title="Повторите пароль" message="Введите новый, желаемый пароль еще раз."}- Повторите пароль</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="password" onkeyup="checkYourAcctPass();" class="form-control input-sm" id="pass2" name="pass2" placeholder="Введите данные">
							</div>
							<div id="pass2.msg"></div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label"></label>
						<div class="col-sm-9">
							<div class="fg-line">
								<button type="submit" onclick="xajax_CheckPassword(-{$user_aid}-, $('current').value);dispatch();" name="button" class="btn btn btn-primary btn-sm waves-effect" id="button">Сохранить</button>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>


<div id="2" style="display:none;"> <!-- div ID 2 is the third 'panel' to be shown -->
	<div class="card">
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Смена серверного пароля <small>Вам нужно будет указать пароль на игровом сервере, прежде чем вы сможете использовать ваши права администратора.<br />Кликните <a href="http://wiki.alliedmods.net/Adding_Admins_%28SourceMod%29#Passwords" title="Информация о паролях в SourceMod" target="_blank"><b>здесь</b></a> для дополнительной информации.</small></h2>
			</div>
			<div class="card-body card-padding">
				-{if $srvpwset}-
					<div class="form-group">
						<label for="pass1" class="col-sm-3 control-label">-{help_icon title="Текущий пароль" message="Введите текущий пароль, чтобы мы знали, что это именно Вы."}- Текущий пароль</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="password" onblur="xajax_CheckSrvPassword(-{$user_aid}-, $('scurrent').value);" class="form-control input-sm" id="scurrent" name="scurrent" placeholder="Введите данные" />
							</div>
							<div id="scurrent.msg"></div>
						</div>
					</div>
				-{/if}-
				
					
				<div class="form-group">
					<label for="pass1" class="col-sm-3 control-label">-{help_icon title="Новый пароль" message="Введите новый пароль сервера. Минимальная длина должна быть: $min_pass_len"}- Новый пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" onkeyup="checkYourSrvPass();" id="spass1" value="" class="form-control input-sm" name="spass1" placeholder="Введите данные" />
						</div>
						<div id="spass1.msg"></div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="pass1" class="col-sm-3 control-label">-{help_icon title="Подтвердите пароль" message="Подтвердите пароль, чтобы не было ошибки."}- Подтверждение</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" onkeyup="checkYourSrvPass();" id="spass2" value="" class="form-control input-sm" name="spass2" placeholder="Введите данные" />
						</div>
						<div id="spass2.msg"></div>
					</div>
				</div>
				
				-{if $srvpwset}-
					<div class="form-group">
						<label for="pass1" class="col-sm-3 control-label">-{help_icon title="Удалить пароль сервера" message="Поставьте галочку, чтобы удалить пароль сервера."}- Удалить пароль</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="checkbox" id="delspass" name="delspass" />
							</div>
						</div>
					</div>
				-{/if}-
  
				<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-9">
						<div class="fg-line">
							<button type="submit" onclick="-{if $srvpwset}-xajax_CheckSrvPassword(-{$user_aid}-, $('scurrent').value);-{/if}-srvdispatch();" name="button" class="btn btn btn-primary btn-sm waves-effect" id="button">Сохранить</button>
						</div>
					</div>
				</div>
					
					
			</div>
		</div>
	</div>
</div>


<div id="3" style="display:none;"> <!-- div ID 3 is the fourth 'panel' to be shown -->
	<div class="card">
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Сменить E-Mail <small>E-Mail Позволяет Вам восстановить доступ к аккаунту, при утере данных.</small></h2>
			</div>
			<div class="card-body card-padding">
				
				<div class="form-group">
					<label class="col-sm-3 control-label">-{help_icon title="Текущий E-Mail" message="Это Ваш текущий E-mail адрес."}- Текущий E-Mail</label>
					<div class="col-sm-9 control-label" style="text-align: left;">
						-{$email}-
					</div>
				</div>
				
				<div class="form-group">
					<label for="pass1" class="col-sm-3 control-label">-{help_icon title="Текущий пароль" message="Введите пароль."}- Пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" onkeyup="checkYourSrvPass();" id="emailpw" value="" class="form-control input-sm" name="emailpw" placeholder="Введите данные" />
						</div>
						<div id="emailpw.msg"></div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="pass1" class="col-sm-3 control-label">-{help_icon title="Новый E-mail" message="Введите Ваш новый адрес e-mail."}- Новый E-mail</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" onkeyup="checkYourSrvPass();" id="email1" value="" class="form-control input-sm" name="email1" placeholder="Введите данные" />
						</div>
						<div id="email1.msg"></div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="pass1" class="col-sm-3 control-label">-{help_icon title="Подтвердить E-mail" message="Введите адрес e-mail для исключения ошибки."}- Подтвердить E-mail</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" onkeyup="checkYourSrvPass();" id="email2" value="" class="form-control input-sm" name="email2" placeholder="Введите данные" />
						</div>
						<div id="email2.msg"></div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-9">
						<div class="fg-line">
							<button type="submit" onclick="checkmail();" name="button" class="btn btn btn-primary btn-sm waves-effect" id="button">Сохранить</button>
						</div>
					</div>
				</div>
					
				
			</div>
		</div>
	</div>
</div>


<script>
var error = 0;
	function set_error(count)
	{
		error = count;
	}
function checkYourAcctPass()
	{
		var err = 0;
		
		if($('pass1').value.length < -{$min_pass_len}-)
		{
			$('pass1.msg').setStyle('display', 'block');
			$('pass1.msg').setHTML('Пароль должен быть не менее -{$min_pass_len}- символов');
			err++;
		}
		else
		{
			$('pass1.msg').setStyle('display', 'none');
		}
		if($('pass2').value != "" && $('pass2').value != $('pass1').value)
		{	
			$('pass2.msg').setStyle('display', 'block');
			$('pass2.msg').setHTML('Пароли не совпадают');
			err++;
		}else{
			$('pass2.msg').setStyle('display', 'none');
		}
		if(err > 0)
		{
			set_error(1);
			return false;
		}
		else
		{
			set_error(0);
			return true;
		}	
	}
	function dispatch()
	{
		if($('current.msg').innerHTML == "Неверный пароль.")
		{
			alert("Incorrect Password");
			return false;
		}
		if(checkYourAcctPass() && error == 0)
		{
			xajax_ChangePassword(-{$user_aid}-, $('pass2').value);
		}
	}
	function checkYourSrvPass()
	{
		if(!$('delspass') || $('delspass').checked == false)
		{
			var err = 0;
			
			if($('spass1').value.length < -{$min_pass_len}-)
			{
				$('spass1.msg').setStyle('display', 'block');
				$('spass1.msg').setHTML('Пароль должен быть не менее -{$min_pass_len}- символов');
				err++;
			}
			else
			{
				$('spass1.msg').setStyle('display', 'none');
			}
			if($('spass2').value != "" && $('spass2').value != $('spass1').value)
			{	
				$('spass2.msg').setStyle('display', 'block');
				$('spass2.msg').setHTML('Пароли не совпадают');
				err++;
			}else{
				$('spass2.msg').setStyle('display', 'none');
			}
			if(err > 0)
			{
				set_error(1);
				return false;
			}
			else
			{
				set_error(0);
				return true;
			}	
		}
		else
		{
			set_error(0);
			return true;
		}	
	}
	function srvdispatch()
	{
		-{if $srvpwset}-
		if($('scurrent.msg').innerHTML == "Неверный пароль.")
		{
			alert("Неверный пароль");
			return false;
		}
		-{/if}-
		if(checkYourSrvPass() && error == 0 && (!$('delspass') || $('delspass').checked == false))
		{
			xajax_ChangeSrvPassword(-{$user_aid}-, $('spass2').value);
		}
		if($('delspass').checked == true)
		{
			xajax_ChangeSrvPassword(-{$user_aid}-, 'NULL');
		}
	}
	function checkmail()
	{
		var err = 0;
        if($('email1').value == "")
        {
            $('email1.msg').setStyle('display', 'block');
			$('email1.msg').setHTML('Введите новый E-mail.');
			err++;
		}else{
			$('email1.msg').setStyle('display', 'none');
		}
        
        if($('email2').value == "")
        {
            $('email2.msg').setStyle('display', 'block');
			$('email2.msg').setHTML('Подтвердите новый E-mail.');
			err++;
		}else{
			$('email2.msg').setStyle('display', 'none');
		}
         
		if(err == 0 && $('email2').value != $('email1').value)
		{	
			$('email2.msg').setStyle('display', 'block');
			$('email2.msg').setHTML('Введенные E-mail адреса не совпадают.');
			err++;
		}
        
        if($('emailpw').value == "")
        {
            $('emailpw.msg').setStyle('display', 'block');
			$('emailpw.msg').setHTML('Введите Ваш пароль.');
			err++;
		}else{
			$('emailpw.msg').setStyle('display', 'none');
		}
        
		if(err > 0)
		{
			set_error(1);
			return false;
		}
		else
		{
			set_error(0);
		}
		if(error == 0)
		{
			xajax_ChangeEmail(-{$user_aid}-, $('email2').value, $('emailpw').value);
		}
	}
</script>
</div>	
