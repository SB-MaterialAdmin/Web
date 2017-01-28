{if not $permission_listadmin}
	Доступ запрещен
{else}
	<button onclick="window.location.href='{$btn_href}'" class="btn btn-float btn-danger m-btn" {$btn_helpa}><i class="zmdi {$btn_icon}"></i></button>
	<div class="card-header">
		<h2>Список администраторов ({if not $btn_rem}Активных: {else}Истекших: {/if}<span id="admincount">{$admin_count}</span>) 
			<small>
				Нажмите на нужного вам администратора в таблице, чтобы узнать больше информации о нем. {$admin_nav_p}
			</small>
		</h2>

		<ul class="actions" id="banlist-nav">
			{$admin_nav}
		</ul>
		{if $btn_rem}<br>{$btn_rem}{/if}
	</div>
	{php} require (TEMPLATES_PATH . "/admin.admins.search.php");{/php}
	
	<div class="table-responsive" id="banlist">
		<table cellspacing="0" cellpadding="0" class="table table-striped">
			<tr>
				<th>Имя</th>
				<th>Группа доступа к серверу</th>
				<th>Группа доступа к ВЕб-панели</th>
				<th class="text-right">Истекает</th>
			</tr>
			{foreach from="$admins" item="admin"}
				<tr onmouseout="this.className='opener'" onmouseover="this.className='info opener'" class="opener" style="cursor: pointer;">
					<td>
						{$admin.user} / <mark data-toggle="tooltip" data-placement="right" title="" data-original-title="Имммунитет администратора">{$admin.immunity}</mark>
					</td>
					<td>{$admin.server_group}</td>
					<td>{$admin.web_group}</td>
					<td class="text-right">{$admin.expired_text}</td>
				</tr>
				<tr>
					<td colspan="4" style="padding: 0px;border-top: 0px solid #FFFFFF;">
						<div class="opener" style="visibility: visible; zoom: 1; opacity: 1; height: 449px; padding-top: 0px; border-top-style: none; padding-bottom: 0px; border-bottom-style: none; overflow: hidden;">
							<div class="p-20">
							<div class="card" id="profile-main">
								<div class="pm-overview c-overflow">
									<div class="pmo-pic">
										<div class="p-relative">
											<a href="#">
												<img src="{$admin.avatar}">
											</a>
											<a href="http://steamcommunity.com/profiles/{$admin.communityid_profile}" class="pmop-edit" target="_blank">
												<i class="zmdi zmdi-steam"></i> <span class="hidden-xs">Профиль стима</span>
											</a>
										</div>
									</div>
									<div class="pmo-block pmo-contact hidden-xs p-t-0">
										<div style="text-align: center;padding-bottom: 20px;"></div>
										<h2>Связь</h2>
										<ul>
											<li><i class="zmdi zmdi-steam" data-toggle="tooltip" data-placement="top" title="" data-original-title="Steam"></i> {$admin.steam_id_amd}</li>
											<li><i class="zmdi zmdi-account-box-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Skype"></i> {$admin.sk_profile}</li>
											<li><i class="zmdi zmdi-email"></i> {$admin.email_profile} (<a href="mailto:{$admin.email_profile}">написать</a>)</li>
											<li><i class="zmdi zmdi-vk"></i> {$admin.vk_profile}</li>
										</ul>
									</div>
									
									<div class="pmo-block hidden-xs p-t-0">
										<h2>Права</h2>
										
												<a class="btn btn-primary btn-block waves-effect" data-toggle="modal" href="#modalWider_srv{$admin.aid}">
													Серверные
												</a>
												<br>
												<br>
												<a class="btn btn-primary btn-block waves-effect" data-toggle="modal" href="#modalWider_web{$admin.aid}">
													Веб
												</a>
									</div>
									
									<!-- Modal -->	
									<div class="modal fade" id="modalWider_srv{$admin.aid}" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-sm">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title">Информация</h4>
												</div>
												<div class="modal-body">
													{$admin.server_flag_string}
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-link" data-dismiss="modal">Закрыть</button>
												</div>
											</div>
										</div>
									</div>
									<!-- Modal -->
									
									<!-- Modal -->	
									<div class="modal fade" id="modalWider_web{$admin.aid}" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-sm">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title">Информация</h4>
												</div>
												<div class="modal-body">
													{$admin.web_flag_string}
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-link" data-dismiss="modal">Закрыть</button>
												</div>
											</div>
										</div>
									</div>
									<!-- Modal -->	
									
									
								</div>
                        
								<div class="pm-body clearfix" id="accordionRed-one" class="collapse in" role="tabpanel">
									{if $permission_editadmin}
										<ul class="tab-nav tn-justified">
											<li class="bgm-lightblue waves-effect"><a class="c-white" href="index.php?p=admin&c=admins&o=editdetails&id={$admin.aid}">Детали</a></li>
											<li class="bgm-lightblue waves-effect"><a class="c-white" href="index.php?p=admin&c=admins&o=editpermissions&id={$admin.aid}">Привилегии</a></li>
											<li class="bgm-lightblue waves-effect"><a class="c-white" href="index.php?p=admin&c=admins&o=editservers&id={$admin.aid}">Сервер</a></li>
											<li class="bgm-lightblue waves-effect"><a class="c-white" href="index.php?p=admin&c=admins&o=editgroup&id={$admin.aid}">Группа</a></li>
											{if $allow_warnings}
												<li class="btn-warning waves-effect"><a class="c-white" href="index.php?p=admin&c=admins&o=warnings&id={$admin.aid}">Предупреждения ({$admin.warnings} из {$maxWarnings})</a></li>
											{/if}
											{if $permission_deleteadmin}
												<li class="btn-danger waves-effect"><a class="c-white" href="#" onclick="{$admin.del_link_d}">Удалить</a></li>
											{/if}
										</ul>
									{/if}
									
									
									<div class="pmb-block  p-t-30">
										<div class="pmbb-header">
											<h2><i class="zmdi zmdi-comment m-r-5"></i> Комментарий</h2>
										</div>
										<div class="pmbb-body p-l-30">
											<div class="pmbb-view">
												{$admin.comment_profile|escape}
											</div>
										</div>
									</div>
									
									<div class="pmb-block p-t-10">
										<div class="pmbb-header">
											<h2><i class="zmdi zmdi-hourglass-alt m-r-5"></i> Визит и срок</h2>
										</div>
										<div class="pmbb-body p-l-30">
											<div class="pmbb-view">
												<dl class="dl-horizontal">
													<dt>Доступ</dt>
													<dd>{$admin.expired_cv}</dd>
												</dl>
												<dl class="dl-horizontal">
													<dt>Последний визит</dt>
													<dd>{$admin.lastvisit}</dd>
												</dl>
											</div>
										</div>
									</div>
							   
								
									<div class="pmb-block p-t-10">
										<div class="pmbb-header">
											<h2><i class="zmdi zmdi-fire m-r-5"></i> Баны</h2>
										</div>
										<div class="pmbb-body p-l-30">
											<div class="pmbb-view">
												<dl class="dl-horizontal">
													<dt>Баны без демо</dt>
													<dd>{$admin.bancount} <a href="./index.php?p=banlist&advSearch={$admin.aid}&advType=admin">(найти)</a></dd>
												</dl>
												<dl class="dl-horizontal">
													<dt>Баны с демо</dt>
													<dd>{$admin.nodemocount} <a href="./index.php?p=banlist&advSearch={$admin.aid}&advType=nodemo">(найти)</a></dd>
												</dl>
											</div>
										</div>
									</div>
									<div class="pmb-block p-t-10 m-b-0">
										<div class="pmbb-header">
											<h2>{help_icon title="Поддержка" message="Можете добавить данного администратора в список(который находится возле поиска) авторов данного РеФорка, под категорией 'Администраторы'." style="padding-top: 3px;"} Support-List</h2>
										</div>
										<div class="pmbb-body p-l-30">
											<div class="pmbb-view">
												<dl class="dl-horizontal">
													<dt>Добавить в список?</dt>
													<dd>
														<div class="toggle-switch p-b-5" data-ts-color="red">
															<input type="checkbox" id="add_support_{$admin.aid}" name="add_support_{$admin.aid}" TABINDEX=9 onclick="xajax_AddSupport({$admin.aid});" hidden="hidden" /> 
															<label for="add_support_{$admin.aid}" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label>
														</div>
													</dd>
												</dl>
											</div>
										</div>
									</div>
								</div>
							</div>
							</div>
						</div>
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
	
	<script type="text/javascript">
		{foreach from=$checked_if item=kek}
			$("add_support_{$kek.kid}").checked = 1;
		{/foreach}
	</script>
	<script type="text/javascript">InitAccordion('tr.opener', 'div.opener', 'content');</script>
{/if}
