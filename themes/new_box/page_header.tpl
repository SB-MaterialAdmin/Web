<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{if $header_title != ""}{$header_title}{else}SourceBans :: MATERIAL{/if}</title>
<link rel="Shortcut Icon" href="./images/favicon.ico" />

<!--SB -->
<script type="text/javascript" src="./scripts/sourcebans.js"></script>
<script type="text/javascript" src="./scripts/mootools.js"></script>
<script type="text/javascript" src="./scripts/contextMenoo.js"></script>

<!-- Vendor CSS -->
<link href="themes/{$theme_name}/vendors/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet" />
<link href="themes/{$theme_name}/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet" />
<link href="themes/{$theme_name}/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet" />
<link href="themes/{$theme_name}/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet" />
<link href="themes/{$theme_name}/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet" />
<link href='https://fonts.googleapis.com/css?family=Roboto:900italic,900,700italic,700,500italic,500,400italic,400,300italic,300' rel='stylesheet' type='text/css' />
<link href="themes/{$theme_name}/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" />
<link href="themes/{$theme_name}/vendors/bower_components/chosen/chosen.min.css" rel="stylesheet" />
<link href="themes/{$theme_name}/vendors/summernote/dist/summernote.css" rel="stylesheet" />   
<!-- CSS -->
<link href="themes/{$theme_name}/css/app.min.1.css" rel="stylesheet" />
<link href="themes/{$theme_name}/css/app.min.2.css" rel="stylesheet" />
<link href="themes/{$theme_name}/css/css_sup.css" rel="stylesheet" />

{$tiny_mce_js}
{$xajax_functions}

</head>
<body {$def_body_chenger} {$theme_bg}>
        <header id="header" class="clearfix" {$theme_color}>
            <ul class="header-inner">
                <li id="menu-trigger" data-trigger="#sidebar">
                    <div class="line-wrap">
                        <div class="line top"></div>
                        <div class="line center"></div>
                        <div class="line bottom"></div>
                    </div>
                </li>
				
				{if $header_logo == ""}
					<li class="logo hidden-xs">
						<a href="index.php">
							{$header_title}
						</a>
					</li>
				{else}
					<li class="hidden-xs">
						<a href="index.php">
							<img src="{$header_logo}" alt="" />
						</a>
					</li>
				{/if}
					
                <li class="pull-right">
                    <ul class="top-menu">
						{$def_ch_chenger}
						
						{if $logged_in}
							<li class="dropdown">
								<a data-toggle="dropdown" href="#"><i class="tm-icon zmdi zmdi zmdi-fire"></i></a>
								<ul class="dropdown-menu dm-icon pull-right" id="nav">
								</ul>
							</li>
						{/if}
							
						<li class="dropdown">
                            <a data-toggle="dropdown" href="#">
                                <i class="tm-icon zmdi zmdi-search"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg pull-right">
                                <div class="listview">
                                    <div class="lv-header bgm-bluegray c-white">
                                        Быстрый Поиск
                                    </div>
                                    <div class="lv-body p-b-20" style="min-height: 0px;">
                                        <div class="row">
											<div class="col-xs-12">
												<div class="input-group input-group-lg">
													<span class="input-group-addon"><i class="zmdi zmdi-globe-lock zmdi-hc-fw"></i></span>
													<div class="fg-line">
														<form method="get" action="index.php" >
															<input type="hidden" name="p" value="banlist" />
															<input type="text" class="form-control input-lg" placeholder="Поиск Банов" name="searchText" />
														</form>
													</div>
												</div>
												<div class="input-group input-group-lg">
													<span class="input-group-addon"><i class="zmdi zmdi-mic-setting zmdi-hc-fw"></i></span>
													<div class="fg-line">
														<form method="get" action="index.php">
															<input type="hidden" name="p" value="commslist" />
															<input type="text" class="form-control input-lg" placeholder="Поиск Мутов" name="searchText" />
														</form>
													</div>
												</div>
												<div class="lv-item">
													<div class="media">
														<div class="media-body">
															<div class="lv-title">Информация</div>
															<small class="lv-small">Поиск выполняется по всем критериям.<br />Можете использовать: SteamID, Имя игрока...</small>
														</div>
													</div>
												</div>
											</div>
										</div>
                                    </div>
									<div class="lv-header bgm-bluegray c-white" style="border-top: 1px solid #F0F0F0;">
                                        Подробный Поиск
                                    </div>
									
									<div class="lv-body" style="min-height: 0px;">
                                        <div class="row">
											<div class="col-xs-12">
												<div class="p-t-5 p-b-10 p-r-10 p-l-10 text-center">
													<button class="btn btn-primary btn-block btn-icon-text waves-effect" onclick="window.location.href='?p=search_bans'"><i class="zmdi zmdi-lock-outline"></i>Баны</button>
													<button class="btn btn-warning btn-block btn-icon-text waves-effect" onclick="window.location.href='?p=search_comm'"><i class="zmdi zmdi-mic-off"></i>Муты</button>
												</div>
											</div>
										</div>
									</div>
									
                                </div>

                            </div>
                        </li>
						
						<li id="chat-trigger" data-trigger="#chat">
                            <a href="#"><i class="tm-icon zmdi zmdi-comment-alt-text"></i></a>
                        </li>
						{if $supports_count == 0}<script type="text/javascript">$('chat-trigger').style.display = "none";</script>{/if}
                    </ul>
                </li>
            </ul>
        </header>
        
        <section id="main" data-layout="layout-1">
			
			<!---->
			<aside id="chat" class="sidebar c-overflow">
				{if $supports_count > 0}
					<div class="chat-seach">
						<div class="fg-line p-10">
							<h4>Администраторы</h4>
						</div>
					</div>
					<div class="listview">
						{foreach from=$supports_list item=supp}
							<a class="lv-item" href="#">
								<div class="media">
									<div class="pull-left p-relative">
										<img class="lv-img-sm" src="{$supp.avatarka}" alt="">
										<i class="chat-status-online"></i>
									</div>
									<div class="media-body">
										<div class="lv-title"><span target="_blank" onclick="window.open('http://steamcommunity.com/profiles/{$supp.authid}', '_blank');" style="color: #2196f3;text-decoration: none;cursor: pointer;">{$supp.user}</span></div>
										<small class="lv-small">{if NOT empty($supp.vk) AND NOT empty($supp.skype)}({/if}{if NOT empty($supp.vk)}<span onclick="window.open('https://vk.com/{$supp.vk|escape}', '_blank');" style="color: #2196f3;text-decoration: none;cursor: pointer;">VK</span>{/if} {if NOT empty($supp.vk) AND NOT empty($supp.skype)}/{/if} {if NOT empty($supp.skype)}<span onclick="window.open('skype:{$supp.skype|escape}?userinfo', '_self');" style="color: #2196f3;text-decoration: none;cursor: pointer;">SKYPE</span>{/if}{if NOT empty($supp.vk) AND NOT empty($supp.skype)}){/if} {if NOT empty($supp.comment)}- {$supp.comment}{/if}</small>
									</div>
								</div>
							</a>
						{/foreach}
					</div>
				{/if}
				<div id="chat_aut">
				</div>
			</aside>
			<!---->
			
			<aside id="sidebar" class="sidebar c-overflow">
                <div class="profile-menu">
                    <a href="#">
                        <div class="profile-pic">
                            <!--АВАТАРКИ СТИМА-->
							<img src="{$avatar}" />
                        </div>

                        <div class="profile-info">
                            {if $logged_in}Аккаунт: <mark>{$username}</mark>{else}Нарушитель{/if}
                            <i class="zmdi zmdi-caret-down"></i>
                        </div>
                    </a>

                    <ul class="main-menu">
                        {if $logged_in}
							<li>
								<a href='index.php?p=account'><i class="zmdi zmdi-settings"></i> Профиль</a>
							</li>
							<li>
								<a href='index.php?p=logout'><i class="zmdi zmdi-time-restore"></i> Выход</a>
							</li>
						{else}
							<li>
								<a href='index.php?p=login'><i class="zmdi zmdi-input-antenna"></i> Авторизация</a>
							</li>
							{if $vay4er_act == "1"}
								<li>
									<a href='index.php?p=pay'><i class="zmdi zmdi-shopping-cart-plus"></i> Активировать Ваучер</a>
								</li>
							{/if}
                        {/if}
                    </ul>
                </div>

                <ul class="main-menu">

         
