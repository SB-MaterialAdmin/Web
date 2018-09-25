<?php /* Smarty version 2.6.29, created on 2018-09-18 17:04:04
         compiled from page_dashboard.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'page_dashboard.tpl', 147, false),)), $this); ?>
<?php if ($this->_tpl_vars['dashboard_text'] == "<p><br></p>" || $this->_tpl_vars['dashboard_text'] == ""): ?>
<?php if ($this->_tpl_vars['dashboard_info_block'] == '1'): ?>
	<div class="card go-social">
		<div class="card-header">
			<h2>Главная<small>Ниже указана главная информация о данном ресурсе.</small></h2>
		</div>
		<div class="card-body card-padding clearfix">
			<div class="col-sm-8">
				<blockquote class="m-b-25">
					<p>
						<?php if ($this->_tpl_vars['dashboard_info_block_text'] != ""): ?><?php echo $this->_tpl_vars['dashboard_info_block_text']; ?>
<?php else: ?>Информация не указана. :(<?php endif; ?>
					</p>
					<?php if ($this->_tpl_vars['dashboard_info_block_text_p'] != ""): ?><footer><?php echo $this->_tpl_vars['dashboard_info_block_text_p']; ?>
</footer><?php endif; ?>
				</blockquote>
			</div>
			<div class="col-sm-4">
				<blockquote class="m-b-25">
					<p>Мы в социальных сетях:</p>
				</blockquote>
				<div class="col-sm-12">
					<?php if ($this->_tpl_vars['dashboard_info_steam'] != ""): ?>
						<a class="col-xs-3" href="<?php echo $this->_tpl_vars['dashboard_info_steam']; ?>
">
							<img src="theme/img/social/steam-128.png" class="img-responsive" alt="">
						</a>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['dashboard_info_vk'] != ""): ?>
						<a class="col-xs-3" href="<?php echo $this->_tpl_vars['dashboard_info_vk']; ?>
">
						<img src="theme/img/social/vk-128.png" class="img-responsive" alt="">
					</a>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['dashboard_info_yout'] != ""): ?>
						<a class="col-xs-3" href="<?php echo $this->_tpl_vars['dashboard_info_yout']; ?>
">
						<img src="theme/img/social/youtube-128.png" class="img-responsive" alt="">
					</a>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['dashboard_info_face'] != ""): ?>
						<a class="col-xs-3" href="<?php echo $this->_tpl_vars['dashboard_info_face']; ?>
">
						<img src="theme/img/social/facebook-128.png" class="img-responsive" alt="">
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php else: ?>
	<div class="card go-social">
		<div class="card-header">
			<h2>Главная<small>Ниже указана главная информация о данном ресурсе.</small></h2>
		</div>
		<div class="card-body card-padding">
			<?php if ($this->_tpl_vars['dashboard_info_block'] == '1'): ?>
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
					<?php echo $this->_tpl_vars['dashboard_text']; ?>

				</div>
				<div role="tabpanel" class="tab-pane animated fadeIn clearfix" id="tab-rek">
					<div class="col-sm-8">
						<blockquote class="m-b-25">
							<p>
								<?php if ($this->_tpl_vars['dashboard_info_block_text'] != ""): ?><?php echo $this->_tpl_vars['dashboard_info_block_text']; ?>
<?php else: ?>Информация не указана. :(<?php endif; ?>
							</p>
							<?php if ($this->_tpl_vars['dashboard_info_block_text_p'] != ""): ?><footer><?php echo $this->_tpl_vars['dashboard_info_block_text_p']; ?>
</footer><?php endif; ?>
						</blockquote>
					</div>
					<div class="col-sm-4">
						<blockquote class="m-b-25">
							<p>Мы в социальных сетях:</p>
						</blockquote>
						<div class="col-sm-12">
							<?php if ($this->_tpl_vars['dashboard_info_steam'] != ""): ?>
								<a class="col-xs-3" href="<?php echo $this->_tpl_vars['dashboard_info_steam']; ?>
">
									<img src="theme/img/social/steam-128.png" class="img-responsive" alt="">
								</a>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['dashboard_info_vk'] != ""): ?>
								<a class="col-xs-3" href="<?php echo $this->_tpl_vars['dashboard_info_vk']; ?>
">
								<img src="theme/img/social/vk-128.png" class="img-responsive" alt="">
							</a>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['dashboard_info_yout'] != ""): ?>
								<a class="col-xs-3" href="<?php echo $this->_tpl_vars['dashboard_info_yout']; ?>
">
								<img src="theme/img/social/youtube-128.png" class="img-responsive" alt="">
							</a>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['dashboard_info_face'] != ""): ?>
								<a class="col-xs-3" href="<?php echo $this->_tpl_vars['dashboard_info_face']; ?>
">
								<img src="theme/img/social/facebook-128.png" class="img-responsive" alt="">
							</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php else: ?>
				<?php echo $this->_tpl_vars['dashboard_text']; ?>

			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<div id="front-servers" class="login-content">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'page_servers.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>


<div class="row">
	
	<?php if ($this->_tpl_vars['listing_block'] == '2' || $this->_tpl_vars['listing_block'] == '3'): ?>
	<!--КОММС-->
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header ch-alt">
				<h2>Блокировки коммуникаций<small>Общее количество: <?php echo $this->_tpl_vars['total_comms']; ?>
</small></h2>
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
						<?php $_from = $this->_tpl_vars['players_commed']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['player']):
?>
							<tr <?php if ($this->_tpl_vars['player']['unbanned']): ?>class="info f-500"<?php endif; ?> onclick="<?php echo $this->_tpl_vars['player']['link_url']; ?>
" style="cursor:pointer;" height="16">
								<td class="listtable_1" align="center"><img src="<?php echo $this->_tpl_vars['player']['type']; ?>
" width="16" alt="<?php if ($this->_tpl_vars['player']['type'] == "images/type_v.png"): ?>Голосовой чат<?php else: ?>Текстовый чат<?php endif; ?>" title="<?php if ($this->_tpl_vars['player']['type'] == "images/type_v.png"): ?>Голосовой чат<?php else: ?>Текстовый чат<?php endif; ?>" /></td>
								<td class="listtable_1"><span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $this->_tpl_vars['player']['created_info']; ?>
"><?php echo $this->_tpl_vars['player']['created']; ?>
</span></td>
								<td class="listtable_1">
									<?php if (empty ( $this->_tpl_vars['player']['short_name'] )): ?>
										<i>Имя игрока не указано.</i>
									<?php else: ?>
										<?php echo ((is_array($_tmp=$this->_tpl_vars['player']['short_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

									<?php endif; ?>
								</td>
								<td class="<?php if ($this->_tpl_vars['player']['unbanned']): ?>c-cyan<?php else: ?>c-cyan<?php endif; ?>" <?php if ($this->_tpl_vars['player']['unbanned']): ?>data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Срок блокировки коммуникации вышел или игрок был разбанен." title="" data-original-title="Подсказка"<?php endif; ?>><?php if ($this->_tpl_vars['player']['unbanned']): ?><del><?php echo $this->_tpl_vars['player']['length']; ?>
</del><?php else: ?><?php echo $this->_tpl_vars['player']['length']; ?>
<?php endif; ?></td>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>
				<!--<a class="lv-footer m-t-0 p-20 f-13" href="index.php?p=commslist">Посмотреть все</a>-->
			</div>
        </div>
	</div>
	<?php endif; ?>
	<!--КОММС-->
	<?php if ($this->_tpl_vars['listing_block'] == '1' || $this->_tpl_vars['listing_block'] == '3'): ?>
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header ch-alt">
				<h2>Список последних банов <small>Общее количество: <?php echo $this->_tpl_vars['total_bans']; ?>
</small></h2>
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
						<?php $_from = $this->_tpl_vars['players_banned']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['player']):
?>
							<tr <?php if ($this->_tpl_vars['player']['unbanned']): ?>class="info f-500"<?php endif; ?> onclick="<?php echo $this->_tpl_vars['player']['link_url']; ?>
" style="cursor:pointer;">
								<td align="center"><img src="images/games/<?php echo $this->_tpl_vars['player']['icon']; ?>
" width="16" alt="MOD" title="MOD" /></td>
								<td class="<?php if ($this->_tpl_vars['player']['unbanned']): ?>c-cyan<?php else: ?>c-cyan<?php endif; ?>"><span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $this->_tpl_vars['player']['created_info']; ?>
"><?php echo $this->_tpl_vars['player']['created']; ?>
</span></td>
								<td>
									<?php if (empty ( $this->_tpl_vars['player']['short_name'] )): ?>
										<i>Имя игрока не указано.</i>
									<?php else: ?>
										<?php echo ((is_array($_tmp=$this->_tpl_vars['player']['short_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

									<?php endif; ?>
								</td>
								<td class="<?php if ($this->_tpl_vars['player']['unbanned']): ?>c-cyan<?php else: ?>c-cyan<?php endif; ?>" <?php if ($this->_tpl_vars['player']['unbanned']): ?>data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Срок бана вышел или игрок был разбанен." title="" data-original-title="Подсказка"<?php endif; ?>><?php if ($this->_tpl_vars['player']['unbanned']): ?><del><?php echo $this->_tpl_vars['player']['length']; ?>
</del><?php else: ?><?php echo $this->_tpl_vars['player']['length']; ?>
<?php endif; ?></td>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>
				<!--<a class="lv-footer m-t-0 p-20 f-13" href="index.php?p=banlist">Посмотреть все</a>-->
			</div>
        </div>
	</div>
	
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header ch-alt">
				<h2>Список последних блоков <small>Всего остановлено: <?php echo $this->_tpl_vars['total_blocked']; ?>
</small></h2>
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
						<?php if ($this->_tpl_vars['total_blocked'] == '0'): ?>
							<tr>
								<td></td>
								<td></td>
								<td>Нет игроков</td>
							</tr>
						<?php else: ?>
							<?php $_from = $this->_tpl_vars['players_blocked']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['player']):
?>
								<tr <?php if ($this->_tpl_vars['dashboard_lognopopup']): ?>onclick="<?php echo $this->_tpl_vars['player']['link_url']; ?>
" <?php else: ?>onclick="<?php echo $this->_tpl_vars['player']['popup']; ?>
" <?php endif; ?>onmouseout="this.className='tbl_out'" onmouseover="this.className='tbl_hover'" style="cursor: pointer;" id="<?php echo $this->_tpl_vars['player']['server']; ?>
" title="Подключение к серверу...">
									<td><img src="images/forbidden.png" width="16" height="16" alt="Заблокированные игроки" /></td>
									<td><?php echo $this->_tpl_vars['player']['date']; ?>
</td>
									<td><?php echo ((is_array($_tmp=$this->_tpl_vars['player']['short_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
								</tr>
							<?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
        </div>
	</div>
	<?php endif; ?>


</div>
<?php if ($this->_tpl_vars['stats']): ?>
<div class="mini-charts">
                    <div class="row">
                        <a href="index.php?p=adminlist">
                            <div class="col-sm-6 col-md-3">
                                <div class="mini-charts-item bgm-cyan">
                                    <div class="clearfix">
                                        <div class="chart stats-bar"></div>
                                        <div class="count">
                                            <small class="f-14">Всего админов</small>
                                            <h2><?php echo $this->_tpl_vars['total_admins']; ?>
</h2>
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
                                            <h2><?php echo $this->_tpl_vars['total_bans']; ?>
</h2>
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
                                            <h2><?php echo $this->_tpl_vars['total_servers']; ?>
</h2>
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
                                            <h2><?php echo $this->_tpl_vars['total_comms']; ?>
</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</a>
                    </div>
                </div>
<?php endif; ?>