<?php
	if (!defined('IN_SB')) {echo("Вы не должны быть здесь. Используйте только ссылки внутри системы!");die();}
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
				Перед установкой этого программного обеспечения, Вы должны прочитать и принять условия лицензионного соглашения, представленного ниже (<a href="https://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank">CC BY-NC-SA 3.0</a>)
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
 Эта программа является частью SourceBans MATERIAL Admin.
 
 Все права защищены © 2016-2017 Sergey Gut <webmaster@kruzefag.ru>
 
 SourceBans MATERIAL Admin распространяется под лицензией
 Creative Commons Attribution-NonCommercial-ShareAlike 3.0.
 
 Вы должны были получить копию лицензии вместе с этой работой. Если нет,
 см. <http://creativecommons.org/licenses/by-nc-sa/3.0/>.
 
 ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО
 ГАРАНТИЙ, ЯВНЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ,
 ГАРАНТИИ ПРИГОДНОСТИ ДЛЯ КОНКРЕТНЫХ ЦЕЛЕЙ И НЕНАРУШЕНИЯ. НИ ПРИ КАКИХ
 ОБСТОЯТЕЛЬСТВАХ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ЗА
 ЛЮБЫЕ ПРЕТЕНЗИИ, ИЛИ УБЫТКИ, НЕЗАВИСИМО ОТ ДЕЙСТВИЯ ДОГОВОРА,
 ГРАЖДАНСКОГО ПРАВОНАРУШЕНИЯ ИЛИ ИНАЧЕ, ВОЗНИКАЮЩИЕ ИЗ, ИЛИ В СВЯЗИ С
 ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ
 ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ.

 Эта программа базируется на работе, охватываемой следующим авторским
                                                           правом (ами):

  * SourceBans ++
    Copyright © 2014-2016 Sarabveer Singh
    Выпущено под лицензией CC BY-NC-SA 3.0
    Страница: <https://sbpp.github.io/>
						</textarea>
					</div>
				</form>

				<div class="col-sm-12 p-l-0 m-10">
					<div class="col-sm-6">
						<div class="checkbox m-b-15">
							<label for="accept">
								<input type="checkbox" name="accept" id="accept" hidden="hidden" />
								<i class="input-helper"></i> Я принимаю условия лицензионного соглашения
							</label>
						</div>
					</div>

					<div class="col-sm-6" align="right">
						<button onclick="checkAccept()" class="btn btn-primary waves-effect" id="button" name="button">Продолжить</button>
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
