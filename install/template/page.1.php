<?php
	if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}
?>

<div class="card m-b-0" id="messages-main">
	<div class="ms-menu">
		<div class="ms-block p-10">
			<span class="c-black"><b>Процесс</b></span>
		</div>

		<div class="listview lv-user" id="install-progress">
			<div class="lv-item media active">
				<div class="lv-avatar bgm-red pull-left">1</div>
				<div class="media-body">
					<div class="lv-title">Шаг: Лицензия</div>
					<div class="lv-small"><i class="zmdi zmdi-badge-check zmdi-hc-fw c-green"></i> Текущий шаг</div>
				</div>
			</div>

			<div class="lv-item media">
				<div class="lv-avatar bgm-orange pull-left">2</div>
				<div class="media-body">
					<div class="lv-title">Шаг: База данных</div>
					<div class="lv-small"><i class="zmdi zmdi-time zmdi-hc-fw c-blue"></i> Следующий шаг</div>
				</div>
			</div>

			<div class="lv-item media">
				<div class="lv-avatar bgm-orange pull-left">3</div>
				<div class="media-body">
					<div class="lv-title">Шаг: Системные требования</div>
					<div class="lv-small"><i class="zmdi zmdi-time zmdi-hc-fw c-blue"></i> Следующий шаг</div>
				</div>
			</div>

			<div class="lv-item media">
				<div class="lv-avatar bgm-orange pull-left">4</div>
				<div class="media-body">
					<div class="lv-title">Шаг: Создание таблиц</div>
					<div class="lv-small"><i class="zmdi zmdi-time zmdi-hc-fw c-blue"></i> Следующий шаг</div>
				</div>
			</div>

			<div class="lv-item media">
				<div class="lv-avatar bgm-orange pull-left">5</div>
				<div class="media-body">
					<div class="lv-title">Шаг: Установка</div>
					<div class="lv-small"><i class="zmdi zmdi-time zmdi-hc-fw c-blue"></i> Следующий шаг</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="ms-body">
		<div class="listview lv-message">
			<div class="lv-header-alt clearfix">
				<div class="lvh-label">
					<span class="c-black">Ознакомление</span>
				</div>
			</div>

			<div class="lv-body p-15">                                    
				Перед установкой этого программного обеспечения, Вы должны прочесть и принять условия лицензионного соглашения. Если Вы не согласны с условиями, создавайте свою систему банов.<br />
				Объяснения этого лицензионного соглашения можно прочесть <a href="https://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank">здесь</a>.
			</div>

			<div class="lv-header-alt clearfix">
				<div class="lvh-label">
					<span class="c-black">Creative Commons - Attribution-NonCommercial-ShareAlike 3.0</span>
				</div>
			</div>
			<div class="lv-body p-15" id="submit-introduction">
				<form action="index.php?p=submit" method="POST" enctype="multipart/form-data">
					<div id="submit-main">
						<textarea class="form-control" id="license" cols="105" rows="15" name="license">
Эта программа является частью SourceBans ++.

Все права защищены © 2014-2016 Sarabveer Singh <me@sarabveer.me>

SourceBans++ is под лицензией
Creative Commons Attribution-NonCommercial-ShareAlike 3.0.

Вы должны были получить копию лицензии вместе с этой работой. Если нет, см <http://creativecommons.org/licenses/by-nc-sa/3.0/>.

ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ, ГАРАНТИИ ПРИГОДНОСТИ ДЛЯ КОНКРЕТНЫХ ЦЕЛЕЙ И НЕНАРУШЕНИЯ. НИ ПРИ КАКИХ ОБСТОЯТЕЛЬСТВАХ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ЗА ЛЮБЫЕ ПРЕТЕНЗИИ, ИЛИ УБЫТКИ, НЕЗАВИСИМО ОТ ДЕЙСТВИЯ ДОГОВОРА, ГРАЖДАНСКОГО ПРАВОНАРУШЕНИЯ ИЛИ ИНАЧЕ, ВОЗНИКАЮЩИЕ ИЗ, ИЛИ В СВЯЗИ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ.

Эта программа базируется на работе, охватываемой следующим авторским правом (ами):
	SourceBans 1.4.11
	Copyright © 2007-2014 SourceBans Team - Part of GameConnect
	Licensed under CC BY-NC-SA 3.0
	Страница: <http://www.sourcebans.net/> - <http://www.gameconnect.net/>

	SourceBans TF2 Theme v1.0
	Copyright © 2014 IceMan
	Страница: <https://forums.alliedmods.net/showthread.php?t=252533>
						</textarea>
					</div>
				</form>

				<div class="col-sm-12 p-l-0 m-10">
					<div class="col-sm-6">
						<div class="checkbox m-b-15">
							<label for="accept">
								<input type="checkbox" name="accept" id="accept" hidden="hidden" />
								<i class="input-helper"></i> Я прочёл и принимаю условия
							</label>
						</div>
					</div>

					<div class="col-sm-6" align="right">
						<button onclick="checkAccept()" class="btn btn-primary waves-effect" id="button" name="button">Принимаю</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	
<script type="text/javascript">
$E('html').onkeydown = function(event){
	var event = new Event(event);
	if (event.key == 'enter' ) checkAccept();
};
function checkAccept()
{
	if($('accept').checked)
		window.location = "index.php?step=2";
	else
	{
		ShowBox('Ошибка', 'Если Вы не принимаете условия - откажитесь от установки этого ПО.', 'red', '', true);
	}
}
</script>
