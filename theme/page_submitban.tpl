<div class="card">
	<div class="form-horizontal" role="form" id="submit-main">
		<div class="card-header">
			<h2>Пожаловаться на игрока</h2>
		</div>
		<div class="alert alert-info" role="alert">Здесь Вы можете подать заявку на бан игрока, нарушающего правила сервера. Когда подаёте заявку, заполняйте все поля, и донесите Ваш комментарий максимально информативно. Это послужит залогом скорейшего рассмотрения Вашей заявки.
		Краткая инструкция по записи демо <a href="javascript:void(0)" onclick="ShowBox('Как записать Демку?', 'В тот момент, когда Вы наблюдаете за нужным игроком, нажмите <b>~</b> (</b>`</b>/<b>Ё</b>) на Вашей клавиатуре. В открывшуюся консоль введите <b>record [demoname]</b> и нажмите <b>Enter</b>. Также пропишите команду <b>status</b> для получения дополнительной информации о сервере. Чтобы остановить запись, введите <b>stop</b>. Файл демки будет лежать в папке <b>cstrike</b>.', 'red', '', true);">здесь</a>
		</div>
			<form action="index.php?p=submit" method="post" enctype="multipart/form-data">
			<input type="hidden" name="subban" value="1">
		<div class="card-body card-padding p-b-0">
			<div class="form-group m-b-5">
				<label for="SteamID" class="col-sm-3 control-label">SteamID нарушителя:</label>
				<div class="col-sm-9">
					<div class="fg-line">
					<input type="text" class="form-control" value="{$STEAMID}">
					</div>
				</div>
			</div>
			<div class="form-group m-b-5">
				<label for="BanIP" class="col-sm-3 control-label">IP нарушителя:</label>
				<div class="col-sm-9">
					<div class="fg-line">
						<input type="text" TABINDEX=1 class="form-control" name="BanIP" placeholder="Введите данные">
					</div>
				</div>
			</div>
			<div class="form-group m-b-5">
				<label for="PlayerName" class="col-sm-3 control-label">Никнейм нарушителя<span class="mandatory">*</span>:</label>
				<div class="col-sm-9">
					<div class="fg-line">
					<input type="text" class="form-control" name="PlayerName" placeholder="Введите данные{$player_name}">
					</div>
				</div>
			</div>
			<div class="form-group m-b-5">
				<label for="BanReason" class="col-sm-3 control-label">Комментарий<span class="mandatory">*</span>:</label>
					<div class="col-sm-9">
					<div class="fg-line">
					<textarea class="form-control auto-size" name="BanReason" placeholder="Пожалуйста, пишите информативные комментарии. Комментарии типа 'читер' не рассматриваются {$ban_reason}" style="overflow: hidden; word-wrap: break-word; height: 43px;"></textarea>
					</div>
				</div>
			</div>
			<div class="form-group m-b-5">
					<label for="SubmitName" class="col-sm-3 control-label">Ваш ник:</label>
				<div class="col-sm-9">
					<div class="fg-line">
					<input type="text" class="form-control" name="SubmitName" placeholder="Введите данные{$subplayer_name}">
					</div>
				</div>
			</div>
			<div class="form-group m-b-5">
				<label for="EmailAddr" class="col-sm-3 control-label">Ваш Email<span class="mandatory">*</span>:</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="EmailAddr" placeholder="Введите данные{$player_email}">
				</div>
			</div>
			<div class="form-group m-b-5">
				<label for="server" class="col-sm-3 control-label">Сервер<span class="mandatory">*</span>:</label>
				<div class="col-sm-3">
				<select class="selectpicker" id="server" name="server">
						<option value="-1">Выберите сервер</option>
						{foreach from="$server_list" item="server}
							<option value="{$server.sid}" {if $server_selected == $server.sid}selected{/if}>{$server.HostName}</option>
						{/foreach}
						<option value="0">Другой сервер, не представленный здесь</option>
					</select> 
				</div>
			</div>
			<div class="form-group m-b-5">
				<label class="col-sm-3 control-label">Загрузка демо:</label>
				<div class="fileinput fileinput-new" data-provides="fileinput">
					<span class="btn btn-primary btn-file m-r-10">
						<span class="fileinput-new">Выберите файл</span>
							<input type="file" name="...">
					</span>
					<span class="fileinput-filename"></span>
					<a href="#" class="close fileinput-exists" data-dismiss="fileinput">&times;</a>
				</div>
			</div>
			<div class="card-body card-padding">
					<p>Примечание: Только форматы .dem, <a href="http://www.winzip.com" target="_blank">.zip</a>, <a href="http://www.rarlab.com" target="_blank">.rar</a>, <a href="http://www.7-zip.org" target="_blank">.7z</a>, <a href="http://www.bzip.org" target="_blank">.bz2</a> или <a href="http://www.gzip.org" target="_blank">.gz</a>.</p>
				<p><span class="mandatory">*</span> = Обязательные поля</p>
			</div>
		<div class="card-body card-padding text-center">
			{sb_button text=Отправить onclick="" icon="<i class='zmdi zmdi-shield-security'></i>" class="bgm-green btn-icon-text" id=save submit=true}
		</div>
		</div>
		<div class="alert alert-info" role="alert">Что случится, если кто-то окажется забаненным?</b><br />
		Если кто-то получает бан, то его уникальный STEAMID или IP заносятся в Базу Данных SourceBans, и каждый раз, когда игрок попытается подключиться к серверу, он/она будут блокироваться с уведомлением о бане.
		</div>
	</div>
</div>
