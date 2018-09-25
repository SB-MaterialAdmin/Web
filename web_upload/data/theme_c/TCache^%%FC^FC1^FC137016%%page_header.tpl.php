<?php /* Smarty version 2.6.29, created on 2018-09-18 17:04:04
         compiled from page_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'page_header.tpl', 159, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if ($this->_tpl_vars['header_title'] != ""): ?><?php echo $this->_tpl_vars['header_title']; ?>
<?php else: ?>SourceBans :: MATERIAL<?php endif; ?></title>
<link rel="Shortcut Icon" href="./images/favicon.ico" />

<!--SB -->
<script type="text/javascript" src="./theme/js/sourcebans.js?_=<?php echo $this->_tpl_vars['sbjs']; ?>
"></script>
<script type="text/javascript" src="./theme/js/mootools.js"></script>
<script type="text/javascript" src="./theme/js/contextMenoo.js"></script>

<!-- Vendor CSS -->
<link href="theme/vendors/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet" />
<link href="theme/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet" />
<link href="theme/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet" />
<link href="theme/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet" />
<link href="theme/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet" />
<link href='https://fonts.googleapis.com/css?family=Roboto:900italic,900,700italic,700,500italic,500,400italic,400,300italic,300' rel='stylesheet' type='text/css' />
<link href="theme/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" />
<link href="theme/vendors/bower_components/chosen/chosen.min.css" rel="stylesheet" />
<link href="theme/vendors/summernote/dist/summernote.css" rel="stylesheet" />   
<!-- CSS -->
<link href="theme/css/app.min.1.css" rel="stylesheet" />
<link href="theme/css/app.min.2.css" rel="stylesheet" />
<link href="theme/css/css_sup.css" rel="stylesheet" />

<?php echo $this->_tpl_vars['tiny_mce_js']; ?>

<?php echo $this->_tpl_vars['xajax_functions']; ?>


</head>
<body <?php echo $this->_tpl_vars['def_body_chenger']; ?>
 <?php echo $this->_tpl_vars['theme_bg']; ?>
>
        <header id="header" class="clearfix" <?php echo $this->_tpl_vars['theme_color']; ?>
>
            <ul class="header-inner">
                <li id="menu-trigger" data-trigger="#sidebar">
                    <div class="line-wrap">
                        <div class="line top"></div>
                        <div class="line center"></div>
                        <div class="line bottom"></div>
                    </div>
                </li>
				
				<?php if ($this->_tpl_vars['header_logo'] == ""): ?>
					<li class="logo hidden-xs">
						<a href="index.php">
							<?php echo $this->_tpl_vars['header_title']; ?>

						</a>
					</li>
				<?php else: ?>
					<li class="hidden-xs">
						<a href="index.php">
							<img src="<?php echo $this->_tpl_vars['header_logo']; ?>
" alt="" />
						</a>
					</li>
				<?php endif; ?>
					
                <li class="pull-right">
                    <ul class="top-menu">
						<?php echo $this->_tpl_vars['def_ch_chenger']; ?>

						
						<?php if ($this->_tpl_vars['logged_in']): ?>
							<li class="dropdown">
								<a data-toggle="dropdown" href="#"><i class="tm-icon zmdi zmdi zmdi-fire"></i></a>
								<ul class="dropdown-menu dm-icon pull-right" id="nav">
								</ul>
							</li>
						<?php endif; ?>
							
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
						<?php if ($this->_tpl_vars['supports_count'] == 0): ?><script type="text/javascript">$('chat-trigger').style.display = "none";</script><?php endif; ?>
                    </ul>
                </li>
            </ul>
        </header>
        
        <section id="main" data-layout="layout-1">
			
			<!---->
			<aside id="chat" class="sidebar c-overflow">
				<?php if ($this->_tpl_vars['supports_count'] > 0): ?>
					<div class="chat-seach">
						<div class="fg-line p-10">
							<h4>Администраторы</h4>
						</div>
					</div>
					<div class="listview">
						<?php $_from = $this->_tpl_vars['supports_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['supp']):
?>
							<a class="lv-item" href="#">
								<div class="media">
									<div class="pull-left p-relative">
										<img class="lv-img-sm" src="<?php echo $this->_tpl_vars['supp']['avatarka']; ?>
" alt="">
										<i class="chat-status-online"></i>
									</div>
									<div class="media-body">
										<div class="lv-title"><span target="_blank" onclick="window.open('http://steamcommunity.com/profiles/<?php echo $this->_tpl_vars['supp']['authid']; ?>
', '_blank');" style="color: #2196f3;text-decoration: none;cursor: pointer;"><?php echo $this->_tpl_vars['supp']['user']; ?>
</span></div>
										<small class="lv-small"><?php if (! empty ( $this->_tpl_vars['supp']['vk'] ) && ! empty ( $this->_tpl_vars['supp']['skype'] )): ?>(<?php endif; ?><?php if (! empty ( $this->_tpl_vars['supp']['vk'] )): ?><span onclick="window.open('https://vk.com/<?php echo ((is_array($_tmp=$this->_tpl_vars['supp']['vk'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
', '_blank');" style="color: #2196f3;text-decoration: none;cursor: pointer;">VK</span><?php endif; ?> <?php if (! empty ( $this->_tpl_vars['supp']['vk'] ) && ! empty ( $this->_tpl_vars['supp']['skype'] )): ?>/<?php endif; ?> <?php if (! empty ( $this->_tpl_vars['supp']['skype'] )): ?><span onclick="window.open('skype:<?php echo ((is_array($_tmp=$this->_tpl_vars['supp']['skype'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
?userinfo', '_self');" style="color: #2196f3;text-decoration: none;cursor: pointer;">SKYPE</span><?php endif; ?><?php if (! empty ( $this->_tpl_vars['supp']['vk'] ) && ! empty ( $this->_tpl_vars['supp']['skype'] )): ?>)<?php endif; ?> <?php if (! empty ( $this->_tpl_vars['supp']['comment'] )): ?>- <?php echo $this->_tpl_vars['supp']['comment']; ?>
<?php endif; ?></small>
									</div>
								</div>
							</a>
						<?php endforeach; endif; unset($_from); ?>
					</div>
				<?php endif; ?>
				<div id="chat_aut">
				</div>
			</aside>
			<!---->
			
			<aside id="sidebar" class="sidebar c-overflow">
                <div class="profile-menu">
                    <a href="#">
                        <div class="profile-pic">
                            <!--АВАТАРКИ СТИМА-->
							<img src="<?php echo $this->_tpl_vars['avatar']; ?>
" />
                        </div>

                        <div class="profile-info">
                            <?php if ($this->_tpl_vars['logged_in']): ?>Аккаунт: <mark><?php echo $this->_tpl_vars['username']; ?>
</mark><?php else: ?>Нарушитель<?php endif; ?>
                            <i class="zmdi zmdi-caret-down"></i>
                        </div>
                    </a>

                    <ul class="main-menu">
                        <?php if ($this->_tpl_vars['logged_in']): ?>
							<li>
								<a href='index.php?p=account'><i class="zmdi zmdi-settings"></i> Профиль</a>
							</li>
							<li>
								<a href='index.php?p=logout'><i class="zmdi zmdi-time-restore"></i> Выход</a>
							</li>
						<?php else: ?>
							<li>
								<a href='index.php?p=login'><i class="zmdi zmdi-input-antenna"></i> Авторизация</a>
							</li>
							<?php if ($this->_tpl_vars['vay4er_act'] == '1'): ?>
								<li>
									<a href='index.php?p=pay'><i class="zmdi zmdi-shopping-cart-plus"></i> Активировать Ваучер</a>
								</li>
							<?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <ul class="main-menu">

         