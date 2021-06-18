{if $dashboard_text == "<p><br></p>" || $dashboard_text == ""}
{if $dashboard_info_block == "1"}
	<div class="card go-social">
		<div class="card-header">
			<h2>Главная<small>Ниже указана главная информация о данном ресурсе.</small></h2>
		</div>
		<div class="card-body card-padding clearfix">
			<div class="col-sm-8">
				<blockquote class="m-b-25">
					<p>
						{if $dashboard_info_block_text != ""}{$dashboard_info_block_text}{else}Информация не указана. :({/if}
					</p>
					{if $dashboard_info_block_text_p != ""}<footer>{$dashboard_info_block_text_p}</footer>{/if}
				</blockquote>
			</div>
			<div class="col-sm-4">
				<blockquote class="m-b-25">
					<p>Мы в социальных сетях:</p>
				</blockquote>
				<div class="col-sm-12">
					{if $dashboard_info_steam !=""}
						<a class="col-xs-3" href="{$dashboard_info_steam}">
							<img src="themes/new_box/img/social/steam-128.png" class="img-responsive" alt="">
						</a>
					{/if}
					{if $dashboard_info_vk !=""}
						<a class="col-xs-3" href="{$dashboard_info_vk}">
						<img src="themes/new_box/img/social/vk-128.png" class="img-responsive" alt="">
					</a>
					{/if}
					{if $dashboard_info_yout !=""}
						<a class="col-xs-3" href="{$dashboard_info_yout}">
						<img src="themes/new_box/img/social/youtube-128.png" class="img-responsive" alt="">
					</a>
					{/if}
					{if $dashboard_info_face !=""}
						<a class="col-xs-3" href="{$dashboard_info_face}">
						<img src="themes/new_box/img/social/facebook-128.png" class="img-responsive" alt="">
					</a>
					{/if}
				</div>
			</div>
		</div>
	</div>
{/if}
{else}
	<div class="card go-social">
		<div class="card-header">
			<h2>Главная<small>Ниже указана главная информация о данном ресурсе.</small></h2>
		</div>
		<div class="card-body card-padding">
			{if $dashboard_info_block == "1"}
			<ul class="tab-nav tn-justified tn-icon" role="tablist">
				<li role="presentation" class="active">
					<a class="col-sx-4" href="#tab-home" aria-controls="tab-1" role="tab" data-toggle="tab">
						<i class="zmdi zmdi-home icon-tab"></i>
					</a>
				</li>
				<li role="presentation">
					<a class="col-xs-4" href="#tab-rek" aria-controls="tab-2" role="tab" data-toggle="tab">
						<i class="zmdi zmdi-star icon-tab"></i>
					</a>
				</li>
			</ul>
			<div class="tab-content p-20">
				<div role="tabpanel" class="tab-pane animated fadeIn in active" id="tab-home">
					{$dashboard_text}
				</div>
				<div role="tabpanel" class="tab-pane animated fadeIn clearfix" id="tab-rek">
					<div class="col-sm-8">
						<blockquote class="m-b-25">
							<p>
								{if $dashboard_info_block_text != ""}{$dashboard_info_block_text}{else}Информация не указана. :({/if}
							</p>
							{if $dashboard_info_block_text_p != ""}<footer>{$dashboard_info_block_text_p}</footer>{/if}
						</blockquote>
					</div>
					<div class="col-sm-4">
						<blockquote class="m-b-25">
							<p>Мы в социальных сетях:</p>
						</blockquote>
						<div class="col-sm-12">
							{if $dashboard_info_steam !=""}
								<a class="col-xs-3" href="{$dashboard_info_steam}">
									<img src="themes/new_box/img/social/steam-128.png" class="img-responsive" alt="">
								</a>
							{/if}
							{if $dashboard_info_vk !=""}
								<a class="col-xs-3" href="{$dashboard_info_vk}">
								<img src="themes/new_box/img/social/vk-128.png" class="img-responsive" alt="">
							</a>
							{/if}
							{if $dashboard_info_yout !=""}
								<a class="col-xs-3" href="{$dashboard_info_yout}">
								<img src="themes/new_box/img/social/youtube-128.png" class="img-responsive" alt="">
							</a>
							{/if}
							{if $dashboard_info_face !=""}
								<a class="col-xs-3" href="{$dashboard_info_face}">
								<img src="themes/new_box/img/social/facebook-128.png" class="img-responsive" alt="">
							</a>
							{/if}
						</div>
					</div>
				</div>
			</div>
			{else}
				{$dashboard_text}
			{/if}
		</div>
	</div>
{/if}

<div id="front-servers" class="login-content">
	{include file='page_servers.tpl'}
</div>


<div class="row">
	
	{if $listing_block == "2" || $listing_block == "3"}
	<!--КОММС-->
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header ch-alt">
				<h2>Блокировки коммуникаций<small>Общее количество: {$total_comms}</small></h2>
			</div>
		<div class="card-body m-t-0">
				<table class="table table-inner table-vmiddle">
					<thead>
						<tr>
							<th width="16">Тип</th>
							<th width="24%" align="center">Дата/Время</th>
							<th>Игрок</th>
							<th width="23%">Срок</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$players_commed item=player}
							<tr {if $player.unbanned}class="info f-500"{/if} onclick="{$player.link_url}" style="cursor:pointer;" height="16">
								<td class="listtable_1" align="center"><img src="{$player.type}" width="16" alt="{if $player.type == "images/type_v.png"}Голосовой чат{else}Текстовый чат{/if}" title="{if $player.type == "images/type_v.png"}Голосовой чат{else}Текстовый чат{/if}" /></td>
								<td class="listtable_1"><span data-toggle="tooltip" data-placement="top" title="" data-original-title="{$player.created_info}">{$player.created}</span></td>
								<td class="listtable_1">
									{if empty($player.short_name)}
										<i>Имя игрока не указано.</i>
									{else}
										{$player.short_name|escape:'html'}
									{/if}
								</td>
								<td class="{if $player.unbanned}c-cyan{else}c-cyan{/if}" {if $player.unbanned}data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Срок блокировки коммуникации вышел или игрок был разбанен." title="" data-original-title="Подсказка"{/if}>{if $player.unbanned}<del>{$player.length}</del>{else}{$player.length}{/if}</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
				<!--<a class="lv-footer m-t-0 p-20 f-13" href="index.php?p=commslist">Посмотреть все</a>-->
			</div>
        </div>
	</div>
	{/if}
	<!--КОММС-->
	{if $listing_block == "1" || $listing_block == "3"}
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header ch-alt">
				<h2>Список последних банов <small>Общее количество: {$total_bans}</small></h2>
			</div>
			<div class="card-body m-t-0">
				<table class="table table-inner table-vmiddle">
					<thead>
						<tr>
							<th style="width: 10px">Игра</th>
							<th style="width: 115px">Дата/Время</th>
							<th>Игрок</th>
							<th style="width: 120px">Срок</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$players_banned item=player}
							<tr {if $player.unbanned}class="info f-500"{/if} onclick="{$player.link_url}" style="cursor:pointer;">
								<td align="center"><img src="images/games/{$player.icon}" width="16" alt="MOD" title="MOD" /></td>
								<td class="{if $player.unbanned}c-cyan{else}c-cyan{/if}"><span data-toggle="tooltip" data-placement="top" title="" data-original-title="{$player.created_info}">{$player.created}</span></td>
								<td>
									{if empty($player.short_name)}
										<i>Имя игрока не указано.</i>
									{else}
										{$player.short_name|escape:'html'}
									{/if}
								</td>
								<td class="{if $player.unbanned}c-cyan{else}c-cyan{/if}" {if $player.unbanned}data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Срок бана вышел или игрок был разбанен." title="" data-original-title="Подсказка"{/if}>{if $player.unbanned}<del>{$player.length}</del>{else}{$player.length}{/if}</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
				<!--<a class="lv-footer m-t-0 p-20 f-13" href="index.php?p=banlist">Посмотреть все</a>-->
			</div>
        </div>
	</div>
	
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header ch-alt">
				<h2>Список последних блоков <small>Всего остановлено: {$total_blocked}</small></h2>
			</div>
			<div class="card-body m-t-0">
				<table class="table table-inner table-vmiddle">
					<thead>
						<tr>
							<th style="width: 10px">&nbsp;</th>
							<th style="width: 115px">Дата/Время</th>
							<th>Игрок</th>
						</tr>
					</thead>
					<tbody>
						{if $total_blocked == "0"}
							<tr>
								<td></td>
								<td></td>
								<td>Нет игроков</td>
							</tr>
						{else}
							{foreach from=$players_blocked item=player}
								<tr {if $dashboard_lognopopup}onclick="{$player.link_url}" {else}onclick="{$player.popup}" {/if}onmouseout="this.className='tbl_out'" onmouseover="this.className='tbl_hover'" style="cursor: pointer;" id="{$player.server}" title="Подключение к серверу...">
									<td><img src="images/forbidden.png" width="16" height="16" alt="Заблокированные игроки" /></td>
									<td>{$player.date}</td>
									<td>{$player.short_name|escape:'html'}</td>
								</tr>
							{/foreach}
						{/if}
					</tbody>
				</table>
			</div>
        </div>
	</div>
	{/if}


</div>
{if $stats}
<div class="mini-charts">
                    <div class="row">
                        <a href="index.php?p=adminlist">
                            <div class="col-sm-6 col-md-3">
                                <div class="mini-charts-item bgm-cyan">
                                    <div class="clearfix">
                                        <div class="chart stats-bar"></div>
                                        <div class="count">
                                            <small class="f-14">Всего админов</small>
                                            <h2>{$total_admins}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</a>
                            
                        <a href="index.php?p=banlist">    
							<div class="col-sm-6 col-md-3">
                                <div class="mini-charts-item bgm-lightgreen">
                                    <div class="clearfix">
                                        <div class="chart stats-bar-2"></div>
                                        <div class="count">
                                            <small class="f-14">Всего банов</small>
                                            <h2>{$total_bans}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</a>
                            
                        <a href="index.php?p=servers">    
							<div class="col-sm-6 col-md-3">
                                <div class="mini-charts-item bgm-orange">
                                    <div class="clearfix">
                                        <div class="chart stats-line"></div>
                                        <div class="count">
                                            <small class="f-14">Всего серверов</small>
                                            <h2>{$total_servers}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</a>
                            
                        <a href="index.php?p=commslist">    
							<div class="col-sm-6 col-md-3">
                                <div class="mini-charts-item bgm-bluegray">
                                    <div class="clearfix">
                                        <div class="chart stats-line-2"></div>
                                        <div class="count">
                                            <small class="f-14">Всего Мутов/Гагов</small>
                                            <h2>{$total_comms}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</a>
                    </div>
                </div>
{/if}
