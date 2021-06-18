<?php 
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
?>

<div class="col-xs-6 p-b-10">
	<a data-toggle="modal" href="#modal_srv" class="btn bgm-blue btn-block waves-effect">Настроить Серверную группу</a>
</div>

<div class="modal fade" id="modal_srv" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">{title}</h4>
			</div>
			<div class="modal-body">
				
				<div class="card">
				<div class="card-body table-responsive">
				<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table">
				  <thead>
					  <tr>
						<th colspan="2" class="tablerow4">Имя</th>
						<th class="tablerow4">Флаг</th>
						<th colspan="2" class="tablerow4">Назначение</th>
					  </tr>
				  </thead>
				  <tr id="srootcheckbox" name="srootcheckbox">
					<td colspan="2" class="tablerow2">Главный админ (Полный доступ)</td>
					<td class="tablerow2" align="center">z</td>
					<td class="tablerow2"> Включает в себя все флаги.</td>
					<td align="center" class="tablerow2"><input type="checkbox" name="s14" id="s14" /></td>
				  </tr>
				  <tr>
					<th colspan="5" class="tablerow4">Стандартные разрешения администратора сервера </th>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Резервные слоты </td>
					<td class="tablerow1" align="center">a</td>
					<td class="tablerow1"> Резервный слот доступа.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s1" id="s1" value="1" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Администратор</td>
					<td class="tablerow1" align="center">b</td>
					<td class="tablerow1"> Родовой администратор; требуется для администраторов.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s23" id="s23" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Кик </td>
					<td class="tablerow1" align="center">c</td>
					<td class="tablerow1"> Кик других игроков.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s2" id="s2" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Бан </td>
					<td class="tablerow1" align="center">d</td>
					<td class="tablerow1"> Бан игроков.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s3" id="s3" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Разбан </td>
					<td align="center" class="tablerow1">e</td>
					<td class="tablerow1"> Разбан игроков.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s4" id="s4" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Убить</td>
					<td align="center" class="tablerow1">f</td>
					<td class="tablerow1"> Убить/нанести вред игроку.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s5" id="s5" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Смена карт </td>
					<td align="center" class="tablerow1">g</td>
					<td class="tablerow1"> Изменение карты или основных особенностей геймплея.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s6" id="s6" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Квар </td>
					<td align="center" class="tablerow1">h</td>
					<td class="tablerow1"> Изменение кваров.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s7" id="s7" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Конфиг </td>
					<td class="tablerow1" align="center">i</td>
					<td class="tablerow1"> Выполнение конфигурационных файлов.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s8" id="s8" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Чат админа  </td>
					<td class="tablerow1" align="center">j</td>
					<td class="tablerow1"> Спец привелегии в чате.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s9" id="s9" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Голосование </td>
					<td class="tablerow1" align="center">k</td>
					<td class="tablerow1"> Управление голосованиями.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s10" id="s10" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Пароль сервера </td>
					<td class="tablerow1" align="center">l</td>
					<td class="tablerow1"> Установка пароля на сервер.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s11" id="s11" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">RCON </td>
					<td class="tablerow1" align="center">m</td>
					<td class="tablerow1"> Выполнение RCON команд.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s12" id="s12" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Читы </td>
					<td class="tablerow1" align="center">n</td>
					<td class="tablerow1"> Изменение sv_cheats или использование читов.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s13" id="s13" /></td>
				  </tr>
				  <tr>
					<th colspan="5" class="tablerow4">Иммунитет </th>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Иммунитет </td>
					<td class="tablerow1" align="center"></td>
					<td class="tablerow1">Выберите уровень иммунитета. Чем выше число, тем больше иммунитет.<br /><div align="center"><input type="text" width="5" name="immunity" id="immunity" /></div></td>
					<td align="center" class="tablerow1"></td>
				  </tr>
				  <tr>
					<th colspan="5" class="tablerow4">Пользовательские разрешения администратора сервера </th>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Пользовательский флаг </td>
					<td class="tablerow1" align="center">o</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s17" id="s17" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Пользовательский флаг </td>
					<td class="tablerow1" align="center">p</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s18" id="s18" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Пользовательский флаг </td>
					<td class="tablerow1" align="center">q</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s19" id="s19" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Пользовательский флаг </td>
					<td class="tablerow1" align="center">r</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s20" id="s20" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Пользовательский флаг </td>
					<td class="tablerow1" align="center">s</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s21" id="s21" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Пользовательский флаг </td>
					<td class="tablerow1" align="center">t</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s22" id="s22" /></td>
				  </tr>
				</table>
				</div>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Готово</button>
			</div>
		</div>
	</div>
</div>