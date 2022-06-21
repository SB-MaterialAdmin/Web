<form action="" method="post">
	<div id="admin-page-content">
		<div id="0">
			<div id="msg-green" style="display:none;">
				<i><img src="./images/yay.png" alt="Внимание" /></i>
				<b>Блокировка обновлена</b>
				<br />
				Детали блокировки были обновлены.<br /><br />
				<i>Перенаправление на страницу блокировок...</i>
			</div>
			<div id="add-group">
		<h3>Детали блокировки</h3>
		За дополнительной информацией или помощью наведите курсор мыши на знак вопроса.<br /><br />
		<input type="hidden" name="insert_type" value="add">
			<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
			  <tr>
			    <td valign="top" width="35%">
				  <div class="rowdesc">
				    -{help_icon title="Ник" message="Ник заблокированного игрока."}-Ник
				  </div>
				</td>
			    <td>
				  <div align="left">
			        <input type="text" class="submit-fields" id="name" name="name" value="-{$ban_name}-" />
			      </div>
			      <div id="name.msg" class="badentry"></div></td>
			  </tr>

			  <tr>
    			<td valign="top">
    			  <div class="rowdesc">
    				-{help_icon title="Steam ID" message="Steam ID игрока. Можно использовать Community ID."}-Steam ID
    			  </div>
    			</td>
    		 	<td>
    			  <div align="left">
      				<input value="-{$ban_authid}-" type="text" TABINDEX=2 class="submit-fields" id="steam" name="steam" />
    			  </div>
    			  <div id="steam.msg" class="badentry"></div>
    			</td>
  			  </tr>
  			  <tr>
    		<td valign="top" width="35%">
    			<div class="rowdesc">
    				-{help_icon title="Тип блокировки" message="Выберите тип блокировки- чат или микрофон"}-Тип блокировки
    			</div>
    		</td>
    		<td>
    			<div align="left">
    				<select id="type" name="type" TABINDEX=2 class="submit-fields">
						<option value="1">Микрофон</option>
						<option value="2">Чат</option>
					</select>
    			</div>
    		</td>
 		  </tr>
 		  <tr>
    		<td valign="top" width="35%">
    			<div class="rowdesc">
    				-{help_icon title="Причина блокировки" message="Объясните подробно, почему дается блокировка."}-Причина блокировки
    			</div>
    		</td>
    		<td>
    			<div align="left">
    				<select id="listReason" name="listReason" TABINDEX=4 class="submit-fields" onChange="changeReason(this[this.selectedIndex].value);">
    					<option value="" selected> -- Выберите причину -- </option>
					<optgroup label="Нарушение">
						<option value="Непристойные язык">Непристойные язык</option>
						<option value="Оскорбление игроков">Оскорбление игроков</option>
                        <option value="Оскорбление админов">Оскорбление админов</option>
                        <option value="Неприемлемый язык">Неприемлемый язык</option>
						<option value="Торговля">Торговля</option>
						<option value="Спам в чат/микро">Спам</option>
						<option value="Реклама">Реклама</option>
					</optgroup>
					<option value="other">Своя</option>
				</select>

				<div id="dreason" style="display:none;">
     					<textarea class="submit-fields" TABINDEX=4 cols="30" rows="5" id="txtReason" name="txtReason"></textarea>
     				</div>
    			</div>
    			<div id="reason.msg" class="badentry"></div>
    		</td>
      </tr>
      <tr>
			    <td valign="top" width="35%"><div class="rowdesc">-{help_icon title="Срок" message="Выберите на сколько выдавать блокировку."}-Срок блокировки</div></td>
			    <td><div align="left">
			     <select id="banlength" name="banlength" TABINDEX=4 class="submit-fields">
									 <option value="0">Навсегда</option>
                        <optgroup label="минуты">
                            <option value="1">1 минута</option>
                            <option value="5">5 минут</option>
                            <option value="10">10 минут</option>
                            <option value="15">15 минут</option>
                            <option value="30">30 минут</option>
                            <option value="45">45 минут</option>
                        </optgroup>
                        <optgroup label="часы">
                            <option value="60">1 час</option>
                            <option value="120">2 часа</option>
                            <option value="180">3 часа</option>
                            <option value="240">4 часа</option>
                            <option value="480">8 часов</option>
                            <option value="720">12 часов</option>
                        </optgroup>
                        <optgroup label="дни">
                            <option value="1440">1 день</option>
                            <option value="2880">2 дня</option>
                            <option value="4320">3 дня</option>
                            <option value="5760">4 дня</option>
                            <option value="7200">5 дней</option>
                            <option value="8640">6 дней</option>
                        </optgroup>
                        <optgroup label="недели">
                            <option value="10080">1 неделя</option>
                            <option value="20160">2 недели</option>
                            <option value="30240">3 недели</option>
                        </optgroup>
                        <optgroup label="месяца">
                            <option value="43200">1 месяц</option>
                            <option value="86400">2 месяца</option>
                            <option value="129600">3 месяца</option>
                            <option value="259200">6 месяцев</option>
                            <option value="518400">12 месяцев</option>
						</optgroup>
				  </select>
			    </div><div id="length.msg" class="badentry"></div></td>
			  </tr>
			  <tr>
			    <td>&nbsp;</td>
			    <td>
			      <input type="hidden" name="did" id="did" value="" />
			      <input type="hidden" name="dname" id="dname" value="" />
			      	-{sb_button text="Сохранить" class="ok" id="editban" submit=true}-
			     	 &nbsp;
			     	 -{sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="back" submit=false}-
			      </td>
			  </tr>
        </table>
       </div>
		</div>
	</div>
</form>
