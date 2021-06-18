<?php 
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
?>

<div class="col-xs-6 p-b-10">
	<a data-toggle="modal" href="#modal_web" class="btn bgm-blue btn-block waves-effect">Настроить ВЕБ группу</a>
</div>

<div class="modal fade" id="modal_web" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">{title}</h4>
			</div>
			<div class="modal-body">
				
				<div class="card">
				<div class="card-body table-responsive">
					<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table">
					  <tr id="wrootcheckbox" name="wrootcheckbox">
						<td colspan="2" class="tablerow2">Главный админ (Полный доступ)</td>
						<td align="center" class="tablerow2"><input type="checkbox" name="p2" id="p2" onclick="UpdateCheckBox(2, 3, 39);" value="1" /></td>
					  </tr>
					  <tr>
						<td colspan="2" class="tablerow4">Управление админами </td>
						<td align="center" class="tablerow4"><input type="checkbox" name="p3" id="p3" onclick="UpdateCheckBox(3, 4, 7);" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Просмотр админов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p4" id="p4" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Добавление админов</td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p5" id="p5" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Редактирование админов</td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p6" id="p6" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Удаление админов</td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p7" id="p7" /></td>
					  </tr>
					  <tr class="tablerow4">
						<td colspan="2" class="tablerow4">Управление серверами </td>
						<td align="center" class="tablerow4"><input type="checkbox" name="p8" id="p8" onclick="UpdateCheckBox(8, 9, 12);"/></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Просмотр серверов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p9" id="p9" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Добавление серверов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p10" id="p10" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Редактирование серверов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p11" id="p11" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Удаление серверов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p12" id="p12" /></td>
					  </tr>
					  <tr>
						<td colspan="2" class="tablerow4">Управление банами </td>
						<td align="center" class="tablerow4"><input type="checkbox" name="p13" id="p13" onclick="UpdateCheckBox(13, 14, 20, 32, 33, 34, 38, 39);"/></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Добавление банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p14" id="p14" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Редактирование своих банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p16" id="p16" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Редактирование банов групп </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p17" id="p17" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Редактирование всех банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p18" id="p18" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Протесты банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p19" id="p19" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Предложения банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p20" id="p20" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Разбан своих банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p38" id="p38" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Разбан банов групп </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p39" id="p39" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Разбан всех банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p32" id="p32" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Удаление банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p33" id="p33" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td width="15%">&nbsp;</td>
						<td class="tablerow1">Импорт банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p34" id="p34" /></td>
					  </tr>
					  <tr>
						<td colspan="2" class="tablerow4">Управление группами </td>
						<td align="center" class="tablerow4"><input type="checkbox" name="p21" id="p21" onclick="UpdateCheckBox(21, 22, 25);" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td>&nbsp;</td>
						<td class="tablerow1">Просмотр групп </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p22" id="p22" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td>&nbsp;</td>
						<td class="tablerow1">Добавление групп </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p23" id="p23" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td>&nbsp;</td>
						<td class="tablerow1">Редактирование групп </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p24" id="p24" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td>&nbsp;</td>
						<td class="tablerow1">Удаление групп </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p25" id="p25" /></td>
					  </tr>
					  <tr>
						<td colspan="2" class="tablerow4">Email уведомления </td>
						<td align="center" class="tablerow4"><input type="checkbox" name="p35" id="p35" onclick="UpdateCheckBox(35, 36, 37);"/></td>
					  </tr>
					  <tr class="tablerow1">
						<td>&nbsp;</td>
						<td class="tablerow1">Уведомления о предложениях банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p36" id="p36" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td>&nbsp;</td>
						<td class="tablerow1">Уведомления о протестах банов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p37" id="p37" /></td>
					  </tr>
					  <tr>
						<td colspan="2" class="tablerow4">Настройки ВЕБ панели SourceBans </td>
						<td align="center" class="tablerow4"><input type="checkbox" name="p26" id="p26" /></td>
					  </tr>
					  <tr>
						<td colspan="2" class="tablerow4">Управление МОДами </td>
						<td align="center" class="tablerow4"><input type="checkbox" name="p27" id="p27" onclick="UpdateCheckBox(27, 28, 31);" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td>&nbsp;</td>
						<td class="tablerow1">Просмотр МОДов</td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p28" id="p28" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td>&nbsp;</td>
						<td class="tablerow1">Добавление МОДов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p29" id="p29" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td>&nbsp;</td>
						<td class="tablerow1">Редактирование МОДов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p30" id="p30" /></td>
					  </tr>
					  <tr class="tablerow1">
						<td>&nbsp;</td>
						<td class="tablerow1">Удаление МОДов </td>
						<td align="center" class="tablerow1"><input type="checkbox" name="p31" id="p31" /></td>
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