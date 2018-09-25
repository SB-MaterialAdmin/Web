<?php /* Smarty version 2.6.29, created on 2018-09-18 17:07:19
         compiled from page_youraccount.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'page_youraccount.tpl', 18, false),array('modifier', 'date_format', 'page_youraccount.tpl', 24, false),array('function', 'help_icon', 'page_youraccount.tpl', 70, false),)), $this); ?>
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
						<td width="<?php if ($this->_tpl_vars['warnings_enabled']): ?>30<?php else: ?>33<?php endif; ?>%" valign="top"><?php echo $this->_tpl_vars['web_permissions']; ?>
</td>
						<td width="<?php if ($this->_tpl_vars['warnings_enabled']): ?>25<?php else: ?>33<?php endif; ?>%" valign="top"><?php echo $this->_tpl_vars['server_permissions']; ?>
</td>
						<td width="<?php if ($this->_tpl_vars['warnings_enabled']): ?>15<?php else: ?>34<?php endif; ?>%" valign="top"><p class="c-blue">Срок окончания</p><ul class="clist clist-star"><li><?php echo $this->_tpl_vars['expired_time']; ?>
</li></ul></td>
						<?php if ($this->_tpl_vars['warnings_enabled']): ?>
						<td width="25%" valign="top">
							<p class="c-blue">Предупреждения: <?php echo count($this->_tpl_vars['warnings']); ?>
 из <?php echo $this->_tpl_vars['max_warnings']; ?>
</p>
							<ul class="clist clist-star">
							<?php if (count($this->_tpl_vars['warnings']) == 0): ?>
								<li>Предупреждений <b>нет</b>.</li>
							<?php else: ?>
								<?php $_from = $this->_tpl_vars['warnings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['warning']):
?>
								<li><?php echo $this->_tpl_vars['warning']['reason']; ?>
 (активно <?php if ($this->_tpl_vars['warning']['expires'] != 0): ?>до <i><?php echo ((is_array($_tmp=$this->_tpl_vars['warning']['expires'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</i><?php else: ?><i>навсегда</i><?php endif; ?>)</li>
								<?php endforeach; endif; unset($_from); ?>
							<?php endif; ?>
							</ul>
						</td>
						<?php endif; ?>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="1" style="display:none;">
	<div class="card">

		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Сменить E-Mail <small>E-Mail позволяет Вам восстановить доступ к аккаунту, при утере данных.</small></h2>
			</div>
			<div class="card-body card-padding">
				
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Текущий E-Mail",'message' => "Это Ваш текущий E-mail адрес."), $this);?>
 Текущий E-Mail</label>
					<div class="col-sm-9 control-label" style="text-align: left;">
						<?php echo $this->_tpl_vars['email']; ?>

					</div>
				</div>
				
				<div class="form-group">
					<label for="pass1" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Текущий пароль",'message' => "Введите пароль."), $this);?>
 Пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" onkeyup="checkYourSrvPass();" id="emailpw" value="" class="form-control input-sm" name="emailpw" placeholder="Введите данные" />
						</div>
						<div id="emailpw.msg"></div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="pass1" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Новый E-mail",'message' => "Введите Ваш новый адрес e-mail."), $this);?>
 Новый E-mail</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" onkeyup="checkYourSrvPass();" id="email1" value="" class="form-control input-sm" name="email1" placeholder="Введите данные" />
						</div>
						<div id="email1.msg"></div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="pass1" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Подтвердить E-mail",'message' => "Введите адрес e-mail для исключения ошибки."), $this);?>
 Подтвердить E-mail</label>
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
							<button type="submit" onclick="checkmail();" name="button" class="btn btn-primary btn-sm waves-effect" id="button">Сохранить</button>
						</div>
					</div>
				</div>
					
				
			</div>
		</div>

		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Безопасность <small>Если ваш аккаунт под угрозой, срочно смените пароль от своего аккаунта!</small></h2>
			</div>
			<div class="card-body card-padding" id="group.details">
					<div class="form-group">
						<label for="current" class="col-sm-3 control-label">Текущий пароль</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="password" onblur="xajax_CheckPassword(<?php echo $this->_tpl_vars['user_aid']; ?>
, $('current').value);" class="form-control input-sm" id="current" name="current" placeholder="Введите данные">
							</div>
							<div id="current.msg"></div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="pass1" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Новый пароль",'message' => "Введите новый, желаемый пароль тут. Минимальная длинна должна быть: ".($this->_tpl_vars['min_pass_len'])), $this);?>
 Новый пароль</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="password" onkeyup="checkYourAcctPass();" class="form-control input-sm" id="pass1" name="pass1" placeholder="Введите данные">
							</div>
							<div id="pass1.msg"></div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="pass2" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Повторите пароль",'message' => "Введите новый, желаемый пароль еще раз."), $this);?>
 Повторите пароль</label>
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
								<button type="submit" onclick="xajax_CheckPassword(<?php echo $this->_tpl_vars['user_aid']; ?>
, $('current').value);dispatch();" name="button" class="btn btn btn-primary btn-sm waves-effect" id="button">Сохранить</button>
							</div>
						</div>
					</div>
			</div>
		</div>

		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Смена серверного пароля <small>Вам нужно будет указать пароль на игровом сервере, прежде чем вы сможете использовать ваши права администратора.<br />Кликните <a href="http://wiki.alliedmods.net/Adding_Admins_%28SourceMod%29#Passwords" title="Информация о паролях в SourceMod" target="_blank"><b>здесь</b></a> для дополнительной информации.</small></h2>
			</div>
			<div class="card-body card-padding">
				<?php if ($this->_tpl_vars['srvpwset']): ?>
					<div class="form-group">
						<label for="pass1" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Текущий пароль",'message' => "Введите текущий пароль, чтобы мы знали, что это именно Вы."), $this);?>
 Текущий пароль</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="password" onblur="xajax_CheckSrvPassword(<?php echo $this->_tpl_vars['user_aid']; ?>
, $('scurrent').value);" class="form-control input-sm" id="scurrent" name="scurrent" placeholder="Введите данные" />
							</div>
							<div id="scurrent.msg"></div>
						</div>
					</div>
				<?php endif; ?>
				
					
				<div class="form-group">
					<label for="pass1" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Новый пароль",'message' => "Введите новый пароль сервера. Минимальная длина должна быть: ".($this->_tpl_vars['min_pass_len'])), $this);?>
 Новый пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" onkeyup="checkYourSrvPass();" id="spass1" value="" class="form-control input-sm" name="spass1" placeholder="Введите данные" />
						</div>
						<div id="spass1.msg"></div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="pass1" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Подтвердите пароль",'message' => "Подтвердите пароль, чтобы не было ошибки."), $this);?>
 Подтверждение</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" onkeyup="checkYourSrvPass();" id="spass2" value="" class="form-control input-sm" name="spass2" placeholder="Введите данные" />
						</div>
						<div id="spass2.msg"></div>
					</div>
				</div>
				
				<?php if ($this->_tpl_vars['srvpwset']): ?>
					<div class="form-group">
						<label for="pass1" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Удалить пароль сервера",'message' => "Поставьте галочку, чтобы удалить пароль сервера."), $this);?>
 Удалить пароль</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="checkbox" id="delspass" name="delspass" />
							</div>
						</div>
					</div>
				<?php endif; ?>
  
				<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-9">
						<div class="fg-line">
							<button type="submit" onclick="<?php if ($this->_tpl_vars['srvpwset']): ?>xajax_CheckSrvPassword(<?php echo $this->_tpl_vars['user_aid']; ?>
, $('scurrent').value);<?php endif; ?>srvdispatch();" name="button" class="btn btn btn-primary btn-sm waves-effect" id="button">Сохранить</button>
						</div>
					</div>
				</div>
					
					
			</div>
		</div>

		<?php if ($this->_tpl_vars['allow_change_inf']): ?>
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Связь <small>Ваша контактная информация, для связи с Вами.</small></h2>
			</div>
			<div class="card-body card-padding" id="group.details">
					<div class="form-group">
						<label for="current_vk" class="col-sm-3 control-label">ВКонтакте</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="text" class="form-control input-sm" id="current_vk" name="current_vk" <?php if (! $this->_tpl_vars['vk']): ?> placeholder="Ваш ID Вконтакте (без https://vk.com/)" <?php else: ?> value="<?php echo $this->_tpl_vars['vk']; ?>
"<?php endif; ?>>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="current_skype" class="col-sm-3 control-label">Skype</label>
						<div class="col-sm-9">
							<div class="fg-line">
								<input type="text" class="form-control input-sm" id="current_skype" name="current_skype" <?php if (! $this->_tpl_vars['skype']): ?> placeholder="Ваш логин Skype"<?php else: ?>value="<?php echo $this->_tpl_vars['skype']; ?>
"<?php endif; ?>>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label"></label>
						<div class="col-sm-9">
							<div class="fg-line">
								<button type="submit" onclick="xajax_ChangeAdminsInfos(<?php echo $this->_tpl_vars['user_aid']; ?>
, $('current_vk').value, $('current_skype').value);" name="button" class="btn btn btn-primary btn-sm waves-effect" id="button">Сохранить</button>
							</div>
						</div>
					</div>
			</div>
		</div>
		<?php endif; ?>
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
		
		if($('pass1').value.length < <?php echo $this->_tpl_vars['min_pass_len']; ?>
)
		{
			$('pass1.msg').setStyle('display', 'block');
			$('pass1.msg').setHTML('Пароль должен быть не менее <?php echo $this->_tpl_vars['min_pass_len']; ?>
 символов');
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
			xajax_ChangePassword(<?php echo $this->_tpl_vars['user_aid']; ?>
, $('pass2').value);
		}
	}
	function checkYourSrvPass()
	{
		if(!$('delspass') || $('delspass').checked == false)
		{
			var err = 0;
			
			if($('spass1').value.length < <?php echo $this->_tpl_vars['min_pass_len']; ?>
)
			{
				$('spass1.msg').setStyle('display', 'block');
				$('spass1.msg').setHTML('Пароль должен быть не менее <?php echo $this->_tpl_vars['min_pass_len']; ?>
 символов');
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
		<?php if ($this->_tpl_vars['srvpwset']): ?>
		if($('scurrent.msg').innerHTML == "Неверный пароль.")
		{
			alert("Неверный пароль");
			return false;
		}
		<?php endif; ?>
		if(checkYourSrvPass() && error == 0 && (!$('delspass') || $('delspass').checked == false))
		{
			xajax_ChangeSrvPassword(<?php echo $this->_tpl_vars['user_aid']; ?>
, $('spass2').value);
		}
		if($('delspass').checked == true)
		{
			xajax_ChangeSrvPassword(<?php echo $this->_tpl_vars['user_aid']; ?>
, 'NULL');
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
			xajax_ChangeEmail(<?php echo $this->_tpl_vars['user_aid']; ?>
, $('email2').value, $('emailpw').value);
		}
	}
</script>
</div>	