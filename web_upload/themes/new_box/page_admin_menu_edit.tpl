<form action="" method="post">
	<div class="card" id="admin-page-content">
		<input type="hidden" name="Link" value="edit" />
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Меню <small>Позволяет управлять ссылками в главном меню SourceBans.</small></h2>
			</div>
			<div class="alert alert-info" role="alert">Вы можете добавлять или заменять иконки ссылок! Иконки используются из фреймворка <i>Material Design Iconic Font</i>. Доступные иконки вы можете просмотреть <a href="http://zavoloklom.github.io/material-design-iconic-font/examples.html" target="_blank">здесь</a>.
			{if $system}<br /><br />Ссылка, выбранная Вами, <i>является системной</i>. Вы не имеете права <i>редактировать URL-адрес этого элемента меню</i> и <i>удалять его навсегда из системы</i>.{/if}</div>
			<div class="card-body card-padding p-b-0">
				<div class="form-group m-b-5">
					<label for="names_link" class="col-sm-3 control-label">{help_icon title="Заголовок" message="Введите заголовок названия ссылки. Грубо говоря 'Имя' ссылки."} Заголовок</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="names_link" name="names_link" value="{$text}" placeholder="Введите данные" />
						</div>
						<div id="names_link.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="des_link" class="col-sm-3 control-label">{help_icon title="Описание" message="Введите описание ссылки, которое вылазиет при наводе курсором мыши на ссылку."} Описание</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="des_link" name="des_link" value="{$des}" placeholder="Введите данные" />
						</div>
						<div id="des_link.msg"></div>
					</div>
				</div>
				{if $system}<input type="hidden" name="url_link" value="{$url}" />{else}<div class="form-group m-b-5">
					<label for="url_link" class="col-sm-3 control-label">{help_icon title="Линк" message="Линк, на который переадресует пользователя, после нажатия на заголовок ссылки."} URL</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="url_link" name="url_link" value="{$url}" placeholder="Введите данные" />
						</div>
						<div id="url_link.msg"></div>
					</div>
				</div>{/if}
				<div class="form-group m-b-5">
					<label for="priora_link" class="col-sm-3 control-label">{help_icon title="Приоритет" message="Приоритет ссылки, позволяет вставить ссылку в определенное место, тем самым сортируя показ ссылки в главном меню SourceBans."} Приоритет</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="priora_link" name="priora_link" value="{$prior}" placeholder="Введите данные" />
						</div>
						<div id="priora_link.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="on_link" class="col-sm-3 control-label"> Статус</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="on_link">
									<input type="checkbox" name="on_link" id="on_link" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				{display_material_checkbox name="onNewTab" help_title="Открывать в новой вкладке" help_text="При щелчке по пункту в меню, он будет открываться в новой вкладке браузера, если здесь установлена галочка."}
				
			</div>
			<div class="card-body card-padding text-center">
				{sb_button text="Сохранить" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" submit=true}
			    &nbsp;
			    {sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="back" submit=false}
			</div>
		</div>
	</div>
	
</form>
