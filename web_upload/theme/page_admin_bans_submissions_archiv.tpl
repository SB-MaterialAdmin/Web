{if NOT $permissions_submissions}
	Доступ запрещен!
{else}
	<h3 style="margin-top:0px;">Архив заявок на бан (<span id="subcountarchiv">{$submission_count_archiv}</span>)</h3>
	Кликните на имени игрока для просмотра подробностей<br /><br />
    <div id="banlist-nav">
        {$asubmission_nav}
    </div>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr  class="tbl_out">
        	<td width="40%" height='16' class="listtable_top"><strong>Ник</strong></td>
			<td width="20%" height='16' class="listtable_top"><strong>SteamID</strong></td>
            <td width="25%" height='16' class="listtable_top"><strong>Действие</strong></td>
		</tr>
		{foreach from="$submission_list_archiv" item="sub"}
			<tr id="asid_{$sub.subid}" class="opener4 tbl_out" {if $sub.hostname == ""}onclick="xajax_ServerHostPlayers('{$sub.server}', 'id', 'suba{$sub.subid}');"{/if} onmouseout="this.className='tbl_out'" onmouseover="this.className='tbl_hover'">
	            <td style="border-bottom: solid 1px #ccc" height='16'>{$sub.name}</td>
				<td style="border-bottom: solid 1px #ccc" height='16'>{if $sub.SteamId!=""}{$sub.SteamId}{else}{$sub.sip}{/if}</td>
	            <td style="border-bottom: solid 1px #ccc" height='16'>
					{if $sub.archiv != "2" and $sub.archiv != "3"}
		            <a href="#" onclick="xajax_SetupBan({$sub.subid});">Забанить</a> -
					{if $permissions_editsub}
					<a href="#" onclick="RemoveSubmission({$sub.subid}, '{$sub.name|stripslashes|stripquotes}', '2');">Восстановить</a> -
					{/if}
					{/if}
		            {if $permissions_editsub}
		           		<a href="#" onclick="RemoveSubmission({$sub.subid}, '{$sub.name|stripslashes|stripquotes}', '0');">Удалить</a> -
		           	{/if}
					<a href="index.php?p=admin&c=bans&o=email&type=s&id={$sub.subid}">Контакты</a>
				</td>
			</tr>
			<tr id="asid_{$sub.subid}a">
				<td colspan="3">
					<div class="opener4" width="100%" align="center">
						<table width="90%" cellspacing="0" cellpadding="0" class="listtable">
          					<tr>
            					<td height="16" align="left" class="listtable_top" colspan="3">
									<b>Детали бана</b>            
								</td>
          					</tr>
							<tr align="left">
									<td height="16" align="left" class="listtable_1" colspan="2">
										<b>В архиве по причине {$sub.archive}</b>
									</td>
									<td width="30%" rowspan="11" class="listtable_2">
									<div class="ban-edit">
					                    <ul>
					                      <li>{$sub.demo}</li>		
					                      <li>{$sub.subaddcomment}</li>	
					                    </ul>
									</div>
			  					</td>
							</tr>
          					<tr align="left">
            					<td width="20%" height="16" class="listtable_1">Игрок</td>
            					<td height="16" class="listtable_1">{$sub.name}</td>
       						</tr>
       						<tr align="left">
            					<td width="20%" height="16" class="listtable_1">Добавлено</td>
            					<td height="16" class="listtable_1">{$sub.submitted}</td>
     						</tr>
      						<tr align="left">
            					<td width="20%" height="16" class="listtable_1">SteamID</td>
            					<td height="16" class="listtable_1">
								{if $sub.SteamId == ""}
									<i><font color="#677882">SteamID не предоставлен</font></i>
								{else}
									{$sub.SteamId}
								{/if}
								</td>
      						</tr>
							<tr align="left">
            					<td width="20%" height="16" class="listtable_1">IP адрес</td>
            					<td height="16" class="listtable_1">
								{if $sub.sip == ""}
									<i><font color="#677882">IP адрес не предоставлен</font></i>
								{else}
									{$sub.sip}
								{/if}
								</td>
      						</tr>
      						<tr align="left">
            					<td width="20%" height="16" class="listtable_1">Причина</td>
            					<td height="" class="listtable_1">{$sub.reason}</td>
      						</tr>
							<tr align="left">
            					<td width="20%" height="16" class="listtable_1">Сервер</td>
            					<td height="" class="listtable_1" id="suba{$sub.subid}">{if $sub.hostname == ""}<i>Получаем имя сервера...</i>{else}{$sub.hostname}{/if}</td>
      						</tr>
      						<tr align="left">
            					<td width="20%" height="16" class="listtable_1">МОД</td>
            					<td height="" class="listtable_1">{$sub.mod}</td>
      						</tr>
							<tr align="left">
            					<td width="20%" height="16" class="listtable_1">Имя заявителя</td>
            					<td height="" class="listtable_1">
								{if $sub.subname == ""}
									<i><font color="#677882">Имя не предоставлено</font></i>
								{else}
									{$sub.subname}
								{/if}
								</td>
      						</tr>
      						<tr align="left">
            					<td width="20%" height="16" class="listtable_1">IP адрес заявителя</td>
            					<td height="" class="listtable_1">{$sub.ip}</td>
      						</tr>
                            <tr align="left">
            					<td width="20%" height="16" class="listtable_1">Отправил в архив</td>
            					<td height="" class="listtable_1">
                                {if !empty($sub.archivedby)}
                                    {$sub.archivedby}
                                {else}
                                    <i><font color="#677882">Админ удалён.</font></i>
                                {/if}
                                </td>
      						</tr>
							<tr align="left">
									<td width="20%" height="16" class="listtable_1">Комментарии</td>
									<td height="60" class="listtable_1" colspan="3">
									{if $sub.commentdata != "None"}
									<table width="100%" border="0">
										{foreach from=$sub.commentdata item=commenta}
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
                                              <span style="font-size:6pt;color:grey;">последнее редактирование: {$commenta.edittime} админом {if !empty($commenta.editname)}{$commenta.editname}{else}<i><font color="#677882">Админ удалён</font></i>{/if}</span>
                                            </td>
                                            </tr>
                                            {/if}
                                          {/foreach}
									</table>
									{/if}
									{if $sub.commentdata == "None"}
										{$sub.commentdata}
									{/if}
								</td>
							</tr>
					</table>
				</div>
			</td>
		</tr>
	{/foreach}
</table>
<script>InitAccordion('tr.opener4', 'div.opener4', 'mainwrapper');</script>
{/if}
