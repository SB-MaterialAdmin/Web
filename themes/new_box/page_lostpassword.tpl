<div class="card" id="lostpassword">
	<div class="form-horizontal" role="form" id="login-content">
		<div class="card-header">
			<h2>Восстановление пароля <small>Впишите в поле ваш E-mail, чтобы на него отправилось подтверждение о сбросе пароля.</small></h2>
		</div>
		<div class="alert alert-success" role="alert" id="msg-blue" style="display:none;">Пожалуйста, проверьте свой почтовый ящик (и папку спам) для получения ссылки, которая поможет вам сбросить пароль.</div>
		<div class="alert alert-danger" role="alert" id="msg-red" style="display:none;">Адрес электронной почты не зарегистрирован в системе.</div>
		<div class="card-body card-padding p-b-0">
			<div class="form-group m-b-5" id="loginPasswordDiv">
				<label for="email" class="col-sm-3 control-label">E-Mail</label>
				<div class="col-sm-9">
					<div class="fg-line">
						<input type="text" TABINDEX=1 class="form-control" id="email" name="email" placeholder="Введите данные">
					</div>
				</div>
			</div>
		</div>
		
		<div class="card-body card-padding text-center" id="loginSubmit">
			{sb_button text="Сбросить" onclick="xajax_LostPassword($('email').value);" icon="<i class='zmdi zmdi-key'></i>" class="bgm-green btn-icon-text" id=alogin submit=false}
		</div>
		
	</div>
</div>
