{if $comment}
<!--Код Комментариев-->
<div class="row">
	<div class="card">
		<div class="card-header">
			<h2>{$commenttype} Комментарий</h2>
		</div>
		<div class="tv-comments">
			<ul class="tvc-lists">
				{foreach from="$othercomments" item="com"}
					<li class="media">
						<a href="#" class="tvh-user pull-left">
							<img class="img-responsive" style="width: 46px;height: 46px;border-radius: 50%;" src="themes/new_box/img/profile-pics/1.jpg" alt="">
						</a>
						<div class="media-body">
						<strong class="d-block">{$com.comname}</strong>
						<small class="c-gray">{$com.added} {if $com.editname != ''}last edit {$com.edittime} by {$com.editname}{/if}</small>

						<div class="m-t-10">{$com.commenttxt}</div>

						</div>
					</li>
				{/foreach}
				<li class="p-20">
					<div class="fg-line">
						<textarea class="form-control auto-size" rows="5" placeholder="Ваш комментарий...." id="commenttext" name="commenttext">{$commenttext}</textarea>
						<div id="commenttext.msg" class="badentry"></div>
					</div>
					<input type="hidden" name="bid" id="bid" value="{$comment}">
					<input type="hidden" name="ctype" id="ctype" value="{$ctype}">
					{if $cid != ""}
						<input type="hidden" name="cid" id="cid" value="{$cid}">
					{else}
						<input type="hidden" name="cid" id="cid" value="-1">
					{/if}
					<input type="hidden" name="page" id="page" value="{$page}">
					{sb_button text="$commenttype Комментарий" onclick="ProcessComment();" class="m-t-15 btn-primary btn-sm" id="acom" submit=false}&nbsp;
					{sb_button text="Назад" onclick="history.go(-1)" class="m-t-15 btn btn-sm" id="aback"}
				</li>
			</ul>
		</div>
    </div>
</div>
<!--Код Комментариев-->
{else}
<div class="card">
	<div class="card-header">
		<h2>Список банов
			<small>
				Общее количество: {$total_bans}{$ban_nav_p}
			</small>
		</h2>
		
		<div class="actions" id="banlist-nav">
			{$ban_nav}
		</div>
	</div>
	
	<div class="alert alert-info" role="alert" id="bans_hidden" style="display:none;">Вам был выведен список банов, которые активны на данный момент.</div>
	<div class="alert" role="alert" id="tickswitchlink" style="display:none;"></div>
	
	<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				{if $view_bans}
					<th width="1%" title="Select All" name="tickswitch" id="tickswitch" onclick="TickSelectAll()" onmouseout="this.className=''" onmouseover="this.className='active'"></th>
				{/if}
				<th width="5%" class="text-center">Игра</th>
				<th width="11%" class="text-center">Дата</th>
				<th class="text-center">Игрок</th>
				{if !$hideadminname}
					<th width="15%" class="text-center">Админ</th>
				{/if}
				<th width="20%" class="text-center">Срок</th>  
			</tr>
		</thead>
		<tbody>
			{foreach from=$ban_list item=ban name=banlist}
				<tr class="opener" {if $ban.server_id != 0}onclick="xajax_ServerHostPlayers({$ban.server_id}, {$ban.ban_id});"{/if} style="cursor: pointer;">
					{if $view_bans}
						<td>
							<label class="checkbox checkbox-inline m-r-20" for="chkb_{$smarty.foreach.banlist.index}" onclick="event.cancelBubble = true;">
                                <input type="checkbox" name="chkb_{$smarty.foreach.banlist.index}" id="chkb_{$smarty.foreach.banlist.index}" value="{$ban.ban_id}" hidden="hidden" />
                                <i class="input-helper"></i>
                            </label>
						</td>
					{/if}
					<td class="text-center">{$ban.mod_icon}</td>
					<td class="text-center">{$ban.ban_date_info}</td>
					<td>
						<div style="float:left;">{if not $nocountryshow}{$ban.country_icon}{/if}
							{if empty($ban.player)}
								<i>имя игрока не указано</i>
							{else}
								{$ban.player|escape:'html'|stripslashes}
							{/if}
						</div>
						{if $ban.demo_available}
							<div style="float:right;" class="f-20">
								<i class="zmdi zmdi-videocam"></i>
							</div>
						{/if}
						{if $view_comments && $ban.commentdata != "Нет" && $ban.commentdata|@count > 0}
							<div style="float:right;padding-right: 5px;">
								{$ban.commentdata|@count} <img src="themes/new_box/img/comm.png" alt="Comments" title="Комментарии" style="height:14px;width:14px;" />
							</div>
						{/if}
					</td>
					{if !$hideadminname}
						<td class="text-center">
							{if !empty($ban.admin)}
								{$ban.admin|escape:'html'}
							{else}
								<i>Администратор снят</i>
							{/if}
						</td>
					{/if}
					<td class="{$ban.class}">{if not $ban.ub_reason}
						{$ban.banlength}
					{else}
						{$ban.ub_reason}
					{/if}
						</td>
				</tr>
				<!-- ###############[ Start Sliding Panel ]################## -->
				<tr>
					<td colspan="7" style="padding: 0px;border-top: 0px solid #FFFFFF;">
						<div class="opener"> 
								<div class="card-header bgm-bluegray">
									<h2>Информация о бане:</h2>

									<ul class="actions actions-alt">
										<li class="dropdown">
											<a href="#" data-toggle="dropdown" aria-expanded="false">
												<i class="zmdi zmdi-more-vert"></i>
											</a>

											<ul class="dropdown-menu dropdown-menu-right">
												{if $view_bans}
													{if $ban.unbanned && $ban.reban_link != false}
														<li>{$ban.reban_link}</li>
													{/if}
														<li>{$ban.blockcomm_link}</li>
													{if $ban.demo_available}
														<li>{$ban.demo_link}</li>
													{/if}
													<li>{$ban.addcomment}</li>
													{if $ban.type == 0}
														{if $groupban}
															<li>{$ban.groups_link}</li>
														{/if}
														{if $friendsban}
															<li>{$ban.friend_ban_link}</li>
														{/if}
													{/if}
													{if ($ban.view_edit && !$ban.unbanned)} 
														<li>{$ban.edit_link}</li>
													{/if}
													{if ($ban.unbanned == false && $ban.view_unban)}
														<li>{$ban.unban_link}</li>
													{/if}
													{if $ban.view_delete}
														<li>{$ban.delete_link}</li>
													{/if}
												{else}
													<li>{$ban.demo_link}</li>
												{/if}
											</ul>
										</li>
									</ul>
								</div>
								<div class="card-body card-padding">
									<div class="form-group col-sm-7" style="font-size: 14px;">
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i>  Игрок</label>
											<div class="col-sm-8">
												{if empty($ban.player)}
													<i>имя игрока не указано.</i>
												{else}
													{$ban.player|escape:'html'|stripslashes}
												{/if}
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Steam ID</label>
											<div class="col-sm-8">
												{if empty($ban.steamid)}
													<i>Steam ID игрока не указан.</i>
												{else}
													{$ban.steamid}
												{/if}
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Steam3 ID</label>
											<div class="col-sm-8">
												{if empty($ban.steamid)}
													<i>Steam3 ID игрока не указан.</i>
												{else}
													<a href="http://steamcommunity.com/profiles/{$ban.steamid3}" target="_blank">{$ban.steamid3}</a>
												{/if}
											</div>
										</div>
										{if $ban.type == 0}
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Steam Community</label>
											<div class="col-sm-8">
												<a href="http://steamcommunity.com/profiles/{$ban.communityid}" target="_blank">{$ban.communityid}</a>
											</div>
										</div>
										{/if}
										{if !$hideplayerips}
											{if $ban.ip != "none"}
												<div class="form-group col-sm-12 m-b-5">
													<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> IP адрес</label>
													<div class="col-sm-8">
														{$ban.ip}
													</div>
												</div>
											{/if}
										{/if}
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Был выдан</label>
											<div class="col-sm-8">
												{$ban.ban_date}
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Длительность</label>
											<div class="col-sm-8">
												{if $ban.ub_reason}<del>{$ban.banlength}</del> {$ban.ub_reason}{else}{$ban.banlength}{/if}
											</div>
										</div>
										{if $ban.unbanned}
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Причина разбана</label>
											<div class="col-sm-8">
												{if $ban.ureason == ""}
													<i>Причина разбана не указана.</i>
												{else}
													{$ban.ureason}
												{/if}
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Разбанен админом</label>
											<div class="col-sm-8">
												 {if !empty($ban.removedby)}
													{$ban.removedby|escape:'html'}
												{else}
													<i>Администратор снят.</i>
												{/if}
											</div>
										</div>
										{/if}
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Будет снят</label>
											<div class="col-sm-8">
												{if $ban.expires == "never"}
													<i>Никогда.</i>
												{else}
													{$ban.expires}
												{/if}
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Причина бана</label>
											<div class="col-sm-8">
												{$ban.reason|escape:'html'}
											</div>
										</div>
										<!--
										{if !$hideadminname}
											<div class="form-group col-sm-12 m-b-5">
												<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Забанен админом</label>
												<div class="col-sm-8">
													{if !empty($ban.admin)}
														{$ban.admin|escape:'html'}
													{else}
														<i>Администратор снят.</i>
													{/if}
												</div>
											</div>
										{/if}
										-->
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Сервер</label>
											<div class="col-sm-8" id="ban_server_{$ban.ban_id}">
												{if $ban.server_id == 0}
													Веб-бан
												{else}
													Пожалуйста, подождите...
												{/if}
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Предыдущие баны</label>
											<div class="col-sm-8">
												{$ban.prevoff_link}
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Блокировок ({$ban.blockcount})</label>
											<div class="col-sm-8">
												{if $ban.banlog == ""}
													<i>никогда...</i>
												{else}
													{if $ban.blockcount >= "5"}
														{help_icon title="Блокировки" message="У данного игрока было слишком много блокировок при входе на сервер, поэтому список был укомплектован."} 
														<a data-toggle="modal" href="#block_spoiler_{$ban.ban_id}_ply">
															Показать все {$ban.blockcount} блокировок.
														</a>
														<div class="modal fade" id="block_spoiler_{$ban.ban_id}_ply" tabindex="-1" role="dialog" aria-hidden="true">
															<div class="modal-dialog modal-lg">
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title">
																			Список блокировок игрока: 
																			{if empty($ban.player)}
																				<i><del>Скрыто :(</del></i>
																			{else}
																				{$ban.player|escape:'html'|stripslashes}
																			{/if}
																		</h4>
																	</div>
																	<div class="modal-body">
																		<p>{$ban.banlog}</p>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-link bgm-blue c-white waves-effect" data-dismiss="modal">Закрыть</button>
																	</div>
																</div>
															</div>
														</div>
													{else}
														{$ban.banlog}
													{/if}
												{/if}
											</div>
										</div>
									</div>
									<div class="form-group col-sm-5">
									{if !$hideadminname}
											<div class="wall-comment-list">
												
												<div class="pmo-block pmo-contact" style="font-size: 14px;">
													<div class="lv-header c-bluegray p-b-5 p-t-0" style="border-bottom: 2px solid #607d8b;">Блокировку Выдал:</div>
													<ul>
														<li class="p-b-5">
															<i class="zmdi zmdi-star c-red f-20"></i> 
																{if !empty($ban.admin)}
																	<b>{$ban.admin|escape:'html'}</b>
																{else}
																	<b>Администратор был удален</b>
																{/if}
														</li>
														{if $ban.admin != "CONSOLE"}
															{if !empty($ban.admin)}
																{if $admininfos}
																	<li class="p-b-5"><i class="zmdi zmdi-steam"></i> {if !empty($ban.admin_authid)}{$ban.admin_authid} (<a href="http://steamcommunity.com/profiles/{$ban.admin_authid_link}" target="_blank">Профиль</a>){else}Нет данных...{/if}</li>
																	<li class="p-b-5"><i class="zmdi zmdi-vk"></i> {if !empty($ban.admin_vk)}<a href="https://vk.com/{$ban.admin_vk}" target="_blank">Линк</a>{else}Нет данных...{/if}</li>
																	<li class="p-b-5"><i class="zmdi zmdi-account-box-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Skype"></i> {if !empty($ban.admin_skype)}{$ban.admin_skype}{else}Нет данных...{/if}</li>
																	<li class="p-b-5">
																		<i class="zmdi zmdi-info-outline" data-toggle="tooltip" data-placement="top" title="" data-original-title="Характеристика"></i>
																		<address class="m-b-0 ng-binding">
																			{if !empty($ban.admin_comm)}
																				{$ban.admin_comm}
																			{else}
																				Нет данных. Обычный рядовой, контролирует порядок на серверах.
																			{/if}
																		</address>
																	</li>
																{/if}
															{/if}
														{/if}
													</ul>
												</div>
											</div>
										{/if}
										<!-- COMMENT CODik-->
										{if $view_comments}
											<hr class="m-t-10 m-b-10" />
											<div class="wall-comment-list">
												{if $ban.commentdata != "Нет"}
													<div class="wcl-list">
														{foreach from=$ban.commentdata item=commenta}
															<div class="media">
																<a href="#" class="pull-left">
																	<img src="themes/new_box/img/profile-pics/4.jpg" alt="" class="lv-img-sm">
																</a>
										 
																<div class="media-body">
																	<a href="#" class="a-title">{if !empty($commenta.comname)}{$commenta.comname|escape:'html'}{else}<i>Админ удален</i>{/if}</a> {if $commenta.edittime != "none"}<small class="c-gray m-l-10">редактировал {if $commenta.editname != "none"}{$commenta.editname}{else}<i>Админ удален</i>{/if} в {$commenta.edittime}</small>{/if}
																	<p class="m-t-5 m-b-0" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">{$commenta.commenttxt}</p>
																</div>
																
																	<ul class="actions" style="right: -1px;">
																		<li class="dropdown">
																			<a href="#" data-toggle="dropdown" aria-expanded="false">
																				<i class="zmdi zmdi-more-vert"></i>
																			</a>

																			<ul class="dropdown-menu dropdown-menu-right">
																				{if $commenta.editcomlink != "none"}<li>{$commenta.editcomlink}</li>{/if}
																				{if $commenta.delcomlink != "none"}<li>{$commenta.delcomlink}</li>{/if}
																			</ul>
																		</li>
																	</ul>
															</div>
														{/foreach}
													</div>
												{else}
													<div class="wcl-list">
														<div class="media">
															<div class="media-body">
																<p class="m-t-5 m-b-0">Комментарии отсутствуют.</p>
															</div>
														</div>
													</div>
												{/if}
												<!-- Comment form -->
													<div class="wcl-form m-t-15">
														<div class="wc-comment">
															<a href="{$ban.addcomment_link}">
																<div class="wcc-inner">
																	Добавить комментарий...
																</div>
															</a>
														</div>
													</div>
											</div>
										{/if}
									<!-- COMMENT CODik-->
										</div>
								</div>
						</div>
					</td>
				</tr>
				<!-- ###############[ End Sliding Panel ]################## -->
				<!-- 
				<div class="modal fade" id="mod_{$ban.admin_gid}_mod" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"><b>{$ban.admin}</b></h4>
                                        </div>
                                        <div class="modal-body f-15">
                                            <p>Доступный список данных администратора:</p>
											<p>
												<ul class="clist clist-angle">
													{if !empty($ban.admin_skype)}<li>Skype: {$ban.admin_skype}</li>{/if}
													{if !empty($ban.admin_vk)}<li>VK: <a href="https://vk.com/{$ban.admin_vk}">Линк</a></li>{/if}
												</ul>
											</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-link" data-dismiss="modal">Закрыть</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
				-->
			{/foreach}
		</tbody>
	</table>
	</div>
	{if $general_unban || $can_delete}
		<div class="card-body card-padding">
			<div class="col-sm-12 p-l-0">
				<div class="col-sm-2 p-0">
					<select class="selectpicker " name="bulk_action" id="bulk_action" onchange="BulkEdit(this,'{$admin_postkey}');">
						<option value="-1">Выберите</option>
						{if $general_unban}
						<option value="U">Разбан</option>
						{/if}
						{if $can_delete}
						<option value="D">Удалить</option>
						{/if}
					</select>
				</div>
				{if $can_export }
					<div class="col-sm-7 p-t-10 text-center">
						Скачать перманентные&nbsp;(&nbsp;<a href="./exportbans.php?type=steam" title="Экспорт перманентных SteamID банов">SteamID</a>&nbsp;/&nbsp;
						<a href="./exportbans.php?type=ip" title="Экспорт перманентных IP банов">IP</a>&nbsp;)&nbsp; баны.
					</div>
				{/if}
				<div class="col-sm-3 p-r-0 text-right" style="float:right;">
					<button class="btn bgm-bluegray waves-effect" onclick="window.location.href='index.php?p=banlist&hideinactive={if $hidetext_darf == '1'}true{else}false{/if}{$searchlink|htmlspecialchars}'">{$hidetext}&nbsp;баны</button>
				</div>
			</div>
		</div>&nbsp;
	{/if}
</div>

{literal}
<script type="text/javascript">window.addEvent('domready', function(){	
InitAccordion('tr.opener', 'div.opener', 'content');
{/literal}
{if $view_bans}
$('tickswitch').value=0;
{/if}
{literal}
}); 
</script>
{/literal}
{/if}
