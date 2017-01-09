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
							<input type="text" TABINDEX=1 class="form-control" id="adminname" name="adminname" placeholder="Введите данные" value="{$user}"> 
						</div>
						<div id="adminname.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="steam" class="col-sm-3 control-label">SteamID</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=2 class="form-control" id="steam" name="steam" placeholder="Введите данные" value="{$authid}">
						</div>
						<div id="steam.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="email" class="col-sm-3 control-label">E-Mail</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=3 class="form-control" id="email" name="email" placeholder="Введите данные" value="{$email}">
						</div>
						<div id="email.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="password" class="col-sm-3 control-label">Пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" TABINDEX=4 class="form-control" id="password" name="password" placeholder="Введите данные" value="{$password}">
						</div>
						<div id="password.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="password2" class="col-sm-3 control-label">Повторите пароль</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="password" TABINDEX=5 class="form-control" id="password2" name="password2" placeholder="Введите данные" value="{$password2}">
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
					<label for="a_foreverperiod" class="col-sm-3 control-label">{help_icon title="Изменить срок" message="На какое кол-во дней изменить срок. Оставьте пустым, если не желаете изменять."} Доступ</label>
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
					<label for="skype" class="col-sm-3 control-label">{help_icon title="Skype" message="Связь с админмистратором через Skype."} Skype</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=9 class="form-control" id="skype" name="skype" placeholder="Введите данные(Не обязательно)" value="{$skype}">
						</div>
						<div id="skype.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="comment" class="col-sm-3 control-label">{help_icon title="Коментарий" message="Напишите коментарий к администратору."} Комментарий</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<textarea TABINDEX=10 class="form-control p-t-5" id="comment" name="comment" rows="3" placeholder="Введите желаемый текст(Не обязательно). Включена поддержка html.">{$comment}</textarea>
						</div>
						<div id="comment.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="vk" class="col-sm-3 control-label">{help_icon title="ВКонтакте" message="Введите ID профиля, для генерации ссылки на страницу администратора в соцсети."} VK(ID)</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=10 class="form-control" id="vk" name="vk" placeholder="Введите данные(Не обязательно)" value={$vk}>
						</div>
						<div id="vk.msg"></div>
					</div>
				</div>
			</div>
			
			<div class="card-body card-padding text-center">
				{sb_button text="Сохранить изменения" icon="<i class='zmdi zmdi-account-add'></i>" class="bgm-green btn-icon-text" id="editmod" submit=true}
				&nbsp;
				{sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aback"}
			</div>
        {$server_script}
		</div>
</form>
