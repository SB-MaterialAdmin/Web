{if NOT $permission_protests}
	Доступ запрещен!
{else}
	<div id="protests">
		<h3 style="margin-top:0px;">Протесты банов (<span id="protcount">{$protest_count}</span>)</h3>
		Кликните на имени игрока для просмотра подробностей бана<br /><br />
        <div id="banlist-nav"> 
        {$protest_nav}
        </div>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
            	<td width="40%" height='16' class="listtable_top"><strong>Ник</strong></td>
      			<td width="20%" height='16' class="listtable_top"><strong>SteamID</strong></td>
            	<td width="25%" height='16' class="listtable_top"><strong>Действие</strong></td>
			</tr>
			{foreach from="$protest_list" item="protest"}
				<tr id="pid_{$protest.pid}" class="opener2 tbl_out" onmouseout="this.className='tbl_out'" onmouseover="this.className='tbl_hover'">
          <td class="toggler listtable_1" height='16'><a href="./index.php?p=banlist&advSearch={$protest.authid}&advType=steamid" title="Показать бан">{$protest.name}</a></td>
          <td class="listtable_1" height='16'>{if $protest.authid!=""}{$protest.authid}{else}{$protest.ip}{/if}</td>
          <td class="listtable_1" height='16'>
          {if $permission_editban}
            <a href="#" onclick="RemoveProtest('{$protest.pid}', '{if $protest.authid!=""}{$protest.authid}{else}{$protest.ip}{/if}', '1');">Удалить</a> -
          {/if}
          <a href="index.php?p=admin&c=bans&o=email&type=p&id={$protest.pid}">Контакты</a>
          </td>
				</tr>
				<tr id="pid_{$protest.pid}a" >
					<td colspan="3" align="center" id="ban_details_{$protest.pid}">
						<div class="opener2">
							<table width="90%" cellspacing="0" cellpadding="0" class="listtable">
          						<tr>
            						<td height="16" align="left" class="listtable_top" colspan="3">
										<b>Подробности</b>
									</td>
          						</tr>
          						<tr align="left">
            						<td width="20%" height="16" class="listtable_1">Игрок</td>
            						<td height="16" class="listtable_1">{$protest.name}</td>
									<td width="30%" rowspan="11" class="listtable_2">
										<div class="ban-edit">
						                    <ul>
						                      <li>{$protest.protaddcomment}</li>	
						                    </ul>
										</div>
				  					</td>
          						</tr>
          						<tr align="left">
            						<td width="20%" height="16" class="listtable_1">SteamID</td>
            						<td height="16" class="listtable_1">
									{if $protest.authid == ""}
										<i><font color="#677882">SteamID не предоставлен</font></i>
									{else}
										{$protest.authid}
									{/if}
									</td>
            					</tr>
          						<tr align="left">
            						<td width="20%" height="16" class="listtable_1">IP address</td>
	     							<td height="16" class="listtable_1">
		     							{if $protest.ip == 'none' || $protest.ip == ''}
		     								<i><font color="#677882">IP адрес не предоставлен</font></i>
		     							{else}
		     								{$protest.ip}
		     							{/if}
	     							</td>
          						</tr>
          						<tr align="left">
            						<td width="20%" height="16" class="listtable_1">Прислан</td>
            						<td height="16" class="listtable_1">{$protest.date}</td>
            					</tr>
          						<tr align="left">
            						<td width="20%" height="16" class="listtable_1">Истекает</td>
            						<td height="16" class="listtable_1">
            							{if $protest.ends == 'never'}
		     								<i><font color="#677882">Никогда.</font></i>
		     							{else}
		     								{$protest.ends}
		     							{/if}
		     						</td>
            					</tr>
						        <tr align="left">
            						<td width="20%" height="16" class="listtable_1">Причина</td>
            						<td height="16" class="listtable_1">{$protest.ban_reason}</td>
            					</tr>
          						<tr align="left">
            						<td width="20%" height="16" class="listtable_1">Забанен админом</td>
            						<td height="16" class="listtable_1">{$protest.admin}</td>
            					</tr>
          						<tr align="left">
            						<td width="20%" height="16" class="listtable_1">Забанен на сервере</td>
            						<td height="16" class="listtable_1">{$protest.server}</td>
            					</tr>
								<tr align="left">
            						<td width="20%" height="16" class="listtable_1">IP адрес протестующего</td>
            						<td height="16" class="listtable_1">{$protest.pip}</td>
            					</tr>
								<tr align="left">
            						<td width="20%" height="16" class="listtable_1">Дата протеста</td>
            						<td height="16" class="listtable_1">{$protest.datesubmitted}</td>
            					</tr>
          						<tr align="left">
            						<td width="20%" height="16" class="listtable_1">Сообщение</td>
            						<td height="16" class="listtable_1">{$protest.reason}</td>
          						</tr>
                      <tr align="left">
                        <td width="20%" height="16" class="listtable_1">Комментарии</td>
                        <td height="60" class="listtable_1" colspan="3">
                        {if $protest.commentdata != "None"}
                        <table width="100%" border="0">
                          {foreach from=$protest.commentdata item=commenta}
                            {if $commenta.morecom}
                            <tr>
                            <td colspan="3">
                              <hr />
                            </td>
                            </tr>
                            {/if}
                            <tr>
                            <td>
                                {if !empty($commenta.comname)}
                                    <b>{$commenta.comname|escape:'html'}</b>
                                {else}
                                    <i><font color="#677882">Админ удалён</font></i>
                                {/if}
                            </td><td align="right"><b>{$commenta.added}</b>
                            </td>
                            {if $commenta.editcomlink != ""}
                            <td align="right">
                              {$commenta.editcomlink} {$commenta.delcomlink}
                            </td>
                            {/if}
                            </tr>
                            <tr>
                            <td colspan="2" style="word-break: break-all;word-wrap: break-word;">
                              {$commenta.commenttxt}
                            </td>
                            </tr>
                            {if !empty($commenta.edittime)}
                            <tr>
                            <td colspan="3">
                              <span style="font-size:6pt;color:grey;">Последнее редактирование: {$commenta.edittime} админом {if !empty($commenta.editname)}{$commenta.editname}{else}<i><font color="#677882">Админ удалён</font></i>{/if}</span>
                            </td>
                            </tr>
                            {/if}
                          {/foreach}
                        </table>
                        {/if}
                        {if $protest.commentdata == "None"}
                          {$protest.commentdata}
                        {/if}
                        </td>
                      </tr>
          					</table>
          				</div>
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
	<script>InitAccordion('tr.opener2', 'div.opener2', 'protests');</script>
{/if}
