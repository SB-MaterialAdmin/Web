<?php /* Smarty version 2.6.29, created on 2018-09-18 18:53:25
         compiled from page_admin_edit_admins_details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'help_icon', 'page_admin_edit_admins_details.tpl', 66, false),array('function', 'sb_button', 'page_admin_edit_admins_details.tpl', 109, false),)), $this); ?>
<form action="" method="post">
<div class="form-horizontal" role="form" id="add-group">
			<div class="card-header">
				<h2>Детали Администратора <small>Измените информацию об администраторе в специальных полях.</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-5">
					<label for="adminname" class="col-sm-3 control-label">Логин</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="adminname" name="adminname" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['user']; ?>
"> 
						</div>
						<div id="adminname.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="steam" class="col-sm-3 control-label">SteamID</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=2 class="form-control" id="steam" name="steam" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['authid']; ?>
">
						</div>
						<div id="steam.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="email" class="col-sm-3 control-label">E-Mail</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=3 class="form-control" id="email" name="email" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['email']; ?>
">
						</div>
						<div id="email.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="password" class="col-sm-3 control-label">Пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" TABINDEX=4 class="form-control" id="password" name="password" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['password']; ?>
">
						</div>
						<div id="password.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="password2" class="col-sm-3 control-label">Повторите пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" TABINDEX=5 class="form-control" id="password2" name="password2" placeholder="Введите данные" value="<?php echo $this->_tpl_vars['password2']; ?>
">
						</div>
						<div id="password.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="a_useserverpass" class="col-sm-3 control-label">Пароль на сервер</label>
					<div class="col-sm-9 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="a_useserverpass" name="a_useserverpass" TABINDEX=6 onclick="$('a_serverpass').disabled = !$(this).checked;" hidden="hidden" /> 
							<label for="a_useserverpass" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label> Включить
						</div>
						<div class="fg-line">
							<input type="password" TABINDEX=7 class="form-control" id="a_serverpass" name="a_serverpass" placeholder="Введите данные(Не обязательно)" disabled>
						</div>
						<div id="a_serverpass.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="a_foreverperiod" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Изменить срок",'message' => "На какое кол-во дней изменить срок. Оставьте пустым, если не желаете изменять."), $this);?>
 Доступ</label>
					<div class="col-sm-9 p-t-10">
						<div class="toggle-switch p-b-5" data-ts-color="red">
							<input type="checkbox" id="a_foreverperiod" name="a_foreverperiod" TABINDEX=9 onclick="$('period').disabled = $(this).checked; $('permaadmin').value = $(this).checked;" hidden="hidden" /> 
							<label for="a_foreverperiod" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label>Навсегда
						</div>
						<div class="fg-line">
							<input type="text" TABINDEX=8 class="form-control" id="period" name="period">
						</div>
						<div id="period.msg"></div>
					</div>
					<input type="hidden" name="permaadmin" id="permaadmin" value="false">
				</div>
				<div class="form-group m-b-5">
					<label for="skype" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => 'Skype','message' => "Связь с админмистратором через Skype."), $this);?>
 Skype</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=9 class="form-control" id="skype" name="skype" placeholder="Введите данные(Не обязательно)" value="<?php echo $this->_tpl_vars['skype']; ?>
">
						</div>
						<div id="skype.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="comment" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Коментарий",'message' => "Напишите коментарий к администратору."), $this);?>
 Комментарий</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<textarea TABINDEX=10 class="form-control p-t-5" id="comment" name="comment" rows="3" placeholder="Введите желаемый текст(Не обязательно). Включена поддержка html."><?php echo $this->_tpl_vars['comment']; ?>
</textarea>
						</div>
						<div id="comment.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="vk" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "ВКонтакте",'message' => "Введите ID профиля, для генерации ссылки на страницу администратора в соцсети."), $this);?>
 VK(ID)</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=10 class="form-control" id="vk" name="vk" placeholder="Введите данные(Не обязательно)" value=<?php echo $this->_tpl_vars['vk']; ?>
>
						</div>
						<div id="vk.msg"></div>
					</div>
				</div>
			</div>
			
			<div class="card-body card-padding text-center">
				<?php echo smarty_function_sb_button(array('text' => "Сохранить изменения",'icon' => "<i class='zmdi zmdi-account-add'></i>",'class' => "bgm-green btn-icon-text",'id' => 'editmod','submit' => true), $this);?>

				&nbsp;
				<?php echo smarty_function_sb_button(array('text' => "Назад",'onclick' => "history.go(-1)",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'aback'), $this);?>

			</div>
        <?php echo $this->_tpl_vars['server_script']; ?>

		</div>
</form>