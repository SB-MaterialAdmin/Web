<form action="" method="post">
    <input type="hidden" name="settingsGroup" value="features" />
    <div class="card" id="group.features">
		<div class="form-horizontal" role="form">
			<div class="card-header">
				<h2>Опции<small>За дополнительной информацией или помощью наведите курсор мыши на знак вопроса.</small></h2>
			</div>
			<div class="card-body card-padding p-b-0">
			
				<div class="form-group m-b-5">
					<label for="export_public" class="col-sm-3 control-label">{help_icon title="Включить публичный список банов" message="Установите этот флажок, чтобы все могли скачать список банов."} Разрешить экспорт банов</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="export_public">
								<input type="checkbox" name="export_public" id="export_public" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_kickit" class="col-sm-3 control-label">{help_icon title="Включить кик" message="Установите этот флажок, чтобы кикнуть игрока, когда бан добавлен в базу."} Включить кик</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_kickit">
								<input type="checkbox" name="enable_kickit" id="enable_kickit" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_groupbanning" class="col-sm-3 control-label">{help_icon title="Включить групповые баны" message="Установите этот флажок, если вы хотите включить бан групп."} Включить групповые баны</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_groupbanning">
								<input type="checkbox" name="enable_groupbanning" id="enable_groupbanning" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
							<div id="enable_groupbanning.msg"></div>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_friendsbanning" class="col-sm-3 control-label">{help_icon title="Включить баны друзей" message="Установите этот флажок, если вы хотите включить бан всех друзей игрока."} Включить баны друзей</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_friendsbanning">
								<input type="checkbox" name="enable_friendsbanning" id="enable_friendsbanning" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
							<div id="enable_friendsbanning.msg"></div>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_adminrehashing" class="col-sm-3 control-label">{help_icon title="Включить перезагрузку списка администраторов" message="Установите этот флажок, если вы хотите, чтобы права администраторов перезагружались при любом изменении админов.групп."} Включить перезагрузку списка администраторов</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_adminrehashing">
								<input type="checkbox" name="enable_adminrehashing" id="enable_adminrehashing" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
							<div id="enable_adminrehashing.msg"></div>
						</div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="enable_admininfo" class="col-sm-3 control-label">{help_icon title="Информация об Администраторе" message="Показывает информацию(скайп, вк, STEAMID) о забанившем игрока Администраторе в банлисте или мут/гаг листе."} Информация об адмнистраторе</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="enable_admininfo">
								<input type="checkbox" name="enable_admininfo" id="enable_admininfo" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body card-padding text-center">
				{sb_button text="Сохранить" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" id="fsettings" submit=true}
				&nbsp;
				{sb_button text="Назад" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="fback"}
			</div>
		</div>
	</div>
</form>
