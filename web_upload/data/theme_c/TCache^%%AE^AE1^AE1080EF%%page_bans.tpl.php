<?php /* Smarty version 2.6.29, created on 2018-09-18 17:07:23
         compiled from page_bans.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sb_button', 'page_bans.tpl', 37, false),array('function', 'help_icon', 'page_bans.tpl', 318, false),array('modifier', 'escape', 'page_bans.tpl', 96, false),array('modifier', 'stripslashes', 'page_bans.tpl', 96, false),array('modifier', 'count', 'page_bans.tpl', 104, false),array('modifier', 'htmlspecialchars', 'page_bans.tpl', 501, false),)), $this); ?>
<?php if ($this->_tpl_vars['comment']): ?>
<!--Код Комментариев-->
<div class="row">
	<div class="card">
		<div class="card-header">
			<h2><?php echo $this->_tpl_vars['commenttype']; ?>
 Комментарий</h2>
		</div>
		<div class="tv-comments">
			<ul class="tvc-lists">
				<?php $_from = ($this->_tpl_vars['othercomments']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['com']):
?>
					<li class="media">
						<a href="#" class="tvh-user pull-left">
							<img class="img-responsive" style="width: 46px;height: 46px;border-radius: 50%;" src="theme/img/profile-pics/1.jpg" alt="">
						</a>
						<div class="media-body">
						<strong class="d-block"><?php echo $this->_tpl_vars['com']['comname']; ?>
</strong>
						<small class="c-gray"><?php echo $this->_tpl_vars['com']['added']; ?>
 <?php if ($this->_tpl_vars['com']['editname'] != ''): ?>last edit <?php echo $this->_tpl_vars['com']['edittime']; ?>
 by <?php echo $this->_tpl_vars['com']['editname']; ?>
<?php endif; ?></small>

						<div class="m-t-10"><?php echo $this->_tpl_vars['com']['commenttxt']; ?>
</div>

						</div>
					</li>
				<?php endforeach; endif; unset($_from); ?>
				<li class="p-20">
					<div class="fg-line">
						<textarea class="form-control auto-size" rows="5" placeholder="Ваш комментарий...." id="commenttext" name="commenttext"><?php echo $this->_tpl_vars['commenttext']; ?>
</textarea>
						<div id="commenttext.msg" class="badentry"></div>
					</div>
					<input type="hidden" name="bid" id="bid" value="<?php echo $this->_tpl_vars['comment']; ?>
">
					<input type="hidden" name="ctype" id="ctype" value="<?php echo $this->_tpl_vars['ctype']; ?>
">
					<?php if ($this->_tpl_vars['cid'] != ""): ?>
						<input type="hidden" name="cid" id="cid" value="<?php echo $this->_tpl_vars['cid']; ?>
">
					<?php else: ?>
						<input type="hidden" name="cid" id="cid" value="-1">
					<?php endif; ?>
					<input type="hidden" name="page" id="page" value="<?php echo $this->_tpl_vars['page']; ?>
">
					<?php echo smarty_function_sb_button(array('text' => ($this->_tpl_vars['commenttype'])." Комментарий",'onclick' => "ProcessComment();",'class' => "m-t-15 btn-primary btn-sm",'id' => 'acom','submit' => false), $this);?>
&nbsp;
					<?php echo smarty_function_sb_button(array('text' => "Назад",'onclick' => "history.go(-1)",'class' => "m-t-15 btn btn-sm",'id' => 'aback'), $this);?>

				</li>
			</ul>
		</div>
    </div>
</div>
<!--Код Комментариев-->
<?php else: ?>
<div class="card">
	<div class="card-header">
		<h2>Список банов
			<small>
				Общее количество: <?php echo $this->_tpl_vars['total_bans']; ?>
<?php echo $this->_tpl_vars['ban_nav_p']; ?>

			</small>
		</h2>
		
		<div class="actions" id="banlist-nav">
			<?php echo $this->_tpl_vars['ban_nav']; ?>

		</div>
	</div>
	
	<div class="alert alert-info" role="alert" id="bans_hidden" style="display:none;">Вам был выведен список банов, которые активны на данный момент.</div>
	<div class="alert" role="alert" id="tickswitchlink" style="display:none;"></div>
	
	<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<?php if ($this->_tpl_vars['view_bans']): ?>
					<th width="1%" title="Select All" name="tickswitch" id="tickswitch" onclick="TickSelectAll()" onmouseout="this.className=''" onmouseover="this.className='active'"></th>
				<?php endif; ?>
				<th width="5%" class="text-center">Игра</th>
				<th width="11%" class="text-center">Дата</th>
				<th class="text-center">Игрок</th>
				<?php if (! $this->_tpl_vars['hideadminname']): ?>
					<th width="15%" class="text-center">Админ</th>
				<?php endif; ?>
				<th width="20%" class="text-center">Срок</th>  
			</tr>
		</thead>
		<tbody>
			<?php $_from = $this->_tpl_vars['ban_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['banlist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['banlist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['ban']):
        $this->_foreach['banlist']['iteration']++;
?>
				<tr class="opener" onclick="<?php if ($this->_tpl_vars['ban']['server_id'] != 0): ?>xajax_ServerHostPlayers(<?php echo $this->_tpl_vars['ban']['server_id']; ?>
, <?php echo $this->_tpl_vars['ban']['ban_id']; ?>
);<?php endif; ?><?php if ($this->_tpl_vars['ban']['vacshow']): ?>xajax_GetVACBan(<?php echo $this->_tpl_vars['ban']['ban_id']; ?>
);<?php endif; ?>" style="cursor: pointer;">
					<?php if ($this->_tpl_vars['view_bans']): ?>
						<td>
							<label class="checkbox checkbox-inline m-r-20" for="chkb_<?php echo ($this->_foreach['banlist']['iteration']-1); ?>
" onclick="event.cancelBubble = true;">
                                <input type="checkbox" name="chkb_<?php echo ($this->_foreach['banlist']['iteration']-1); ?>
" id="chkb_<?php echo ($this->_foreach['banlist']['iteration']-1); ?>
" value="<?php echo $this->_tpl_vars['ban']['ban_id']; ?>
" hidden="hidden" />
                                <i class="input-helper"></i>
                            </label>
						</td>
					<?php endif; ?>
					<td class="text-center"><?php echo $this->_tpl_vars['ban']['mod_icon']; ?>
</td>
					<td class="text-center"><?php echo $this->_tpl_vars['ban']['ban_date_info']; ?>
</td>
					<td>
						<div style="float:left;"><?php if (! $this->_tpl_vars['nocountryshow']): ?><?php echo $this->_tpl_vars['ban']['country_icon']; ?>
<?php endif; ?>
							<?php if (empty ( $this->_tpl_vars['ban']['player'] )): ?>
								<i>имя игрока не указано</i>
							<?php else: ?>
								<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['ban']['player'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

							<?php endif; ?>
						</div>
						<?php if ($this->_tpl_vars['ban']['demo_available']): ?>
							<div style="float:right;" class="f-20">
								<i class="zmdi zmdi-videocam"></i>
							</div>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['view_comments'] && $this->_tpl_vars['ban']['commentdata'] != "Нет" && count($this->_tpl_vars['ban']['commentdata']) > 0): ?>
							<div style="float:right;padding-right: 5px;">
								<?php echo count($this->_tpl_vars['ban']['commentdata']); ?>
 <img src="theme/img/comm.png" alt="Comments" title="Комментарии" style="height:14px;width:14px;" />
							</div>
						<?php endif; ?>
					</td>
					<?php if (! $this->_tpl_vars['hideadminname']): ?>
						<td class="text-center">
							<?php if (! empty ( $this->_tpl_vars['ban']['admin'] )): ?>
								<?php echo ((is_array($_tmp=$this->_tpl_vars['ban']['admin'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

							<?php else: ?>
								<i>Администратор снят</i>
							<?php endif; ?>
						</td>
					<?php endif; ?>
					<td class="<?php echo $this->_tpl_vars['ban']['class']; ?>
"><?php if (! $this->_tpl_vars['ban']['ub_reason']): ?>
						<?php echo $this->_tpl_vars['ban']['banlength']; ?>

					<?php else: ?>
						<?php echo $this->_tpl_vars['ban']['ub_reason']; ?>

					<?php endif; ?>
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
												<?php if ($this->_tpl_vars['view_bans']): ?>
													<?php if ($this->_tpl_vars['ban']['unbanned'] && $this->_tpl_vars['ban']['reban_link'] != false): ?>
														<li><?php echo $this->_tpl_vars['ban']['reban_link']; ?>
</li>
													<?php endif; ?>
														<li><?php echo $this->_tpl_vars['ban']['blockcomm_link']; ?>
</li>
													<?php if ($this->_tpl_vars['ban']['demo_available']): ?>
														<li><?php echo $this->_tpl_vars['ban']['demo_link']; ?>
</li>
													<?php endif; ?>
													<li><?php echo $this->_tpl_vars['ban']['addcomment']; ?>
</li>
													<?php if ($this->_tpl_vars['ban']['type'] == 0): ?>
														<?php if ($this->_tpl_vars['groupban']): ?>
															<li><?php echo $this->_tpl_vars['ban']['groups_link']; ?>
</li>
														<?php endif; ?>
														<?php if ($this->_tpl_vars['friendsban']): ?>
															<li><?php echo $this->_tpl_vars['ban']['friend_ban_link']; ?>
</li>
														<?php endif; ?>
													<?php endif; ?>
													<?php if (( $this->_tpl_vars['ban']['view_edit'] && ! $this->_tpl_vars['ban']['unbanned'] )): ?> 
														<li><?php echo $this->_tpl_vars['ban']['edit_link']; ?>
</li>
													<?php endif; ?>
													<?php if (( $this->_tpl_vars['ban']['unbanned'] == false && $this->_tpl_vars['ban']['view_unban'] )): ?>
														<li><?php echo $this->_tpl_vars['ban']['unban_link']; ?>
</li>
													<?php endif; ?>
													<?php if ($this->_tpl_vars['ban']['view_delete']): ?>
														<li><?php echo $this->_tpl_vars['ban']['delete_link']; ?>
</li>
													<?php endif; ?>
												<?php else: ?>
													<li><?php echo $this->_tpl_vars['ban']['demo_link']; ?>
</li>
												<?php endif; ?>
											</ul>
										</li>
									</ul>
								</div>
								<div class="card-body card-padding">
									<div class="form-group col-sm-7" style="font-size: 14px;">
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i>  Игрок</label>
											<div class="col-sm-8">
												<?php if (empty ( $this->_tpl_vars['ban']['player'] )): ?>
													<i>имя игрока не указано.</i>
												<?php else: ?>
													<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['ban']['player'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

												<?php endif; ?>
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Steam ID</label>
											<div class="col-sm-8">
												<?php if (empty ( $this->_tpl_vars['ban']['steamid'] )): ?>
													<i>Steam ID игрока не указан.</i>
												<?php else: ?>
													<?php echo $this->_tpl_vars['ban']['steamid']; ?>

												<?php endif; ?>
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Steam3 ID</label>
											<div class="col-sm-8">
												<?php if (empty ( $this->_tpl_vars['ban']['steamid'] )): ?>
													<i>Steam3 ID игрока не указан.</i>
												<?php else: ?>
													<a href="http://steamcommunity.com/profiles/<?php echo $this->_tpl_vars['ban']['steamid3']; ?>
" target="_blank"><?php echo $this->_tpl_vars['ban']['steamid3']; ?>
</a>
												<?php endif; ?>
											</div>
										</div>
										<?php if ($this->_tpl_vars['ban']['type'] == 0): ?>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Steam Community</label>
											<div class="col-sm-8">
												<a href="http://steamcommunity.com/profiles/<?php echo $this->_tpl_vars['ban']['communityid']; ?>
" target="_blank"><?php echo $this->_tpl_vars['ban']['communityid']; ?>
</a>
											</div>
										</div>
										<?php endif; ?>
                                        <?php if ($this->_tpl_vars['ban']['vacshow']): ?>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> VAC-бан</label>
											<div class="col-sm-8">
												<strong id="vacban_<?php echo $this->_tpl_vars['ban']['ban_id']; ?>
">Загружается...</strong>
											</div>
										</div>
                                        <?php endif; ?>
										<?php if (! $this->_tpl_vars['hideplayerips']): ?>
											<?php if ($this->_tpl_vars['ban']['ip'] != 'none'): ?>
												<div class="form-group col-sm-12 m-b-5">
													<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> IP адрес</label>
													<div class="col-sm-8">
														<?php echo $this->_tpl_vars['ban']['ip']; ?>

													</div>
												</div>
											<?php endif; ?>
										<?php endif; ?>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Был выдан</label>
											<div class="col-sm-8">
												<?php echo $this->_tpl_vars['ban']['ban_date']; ?>

											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Длительность</label>
											<div class="col-sm-8">
												<?php if ($this->_tpl_vars['ban']['ub_reason']): ?><del><?php echo $this->_tpl_vars['ban']['banlength']; ?>
</del> <?php echo $this->_tpl_vars['ban']['ub_reason']; ?>
<?php else: ?><?php echo $this->_tpl_vars['ban']['banlength']; ?>
<?php endif; ?>
											</div>
										</div>
										<?php if ($this->_tpl_vars['ban']['unbanned']): ?>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Причина разбана</label>
											<div class="col-sm-8">
												<?php if ($this->_tpl_vars['ban']['ureason'] == ""): ?>
													<i>Причина разбана не указана.</i>
												<?php else: ?>
													<?php echo $this->_tpl_vars['ban']['ureason']; ?>

												<?php endif; ?>
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Разбанен админом</label>
											<div class="col-sm-8">
												 <?php if (! empty ( $this->_tpl_vars['ban']['removedby'] )): ?>
													<?php echo ((is_array($_tmp=$this->_tpl_vars['ban']['removedby'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

												<?php else: ?>
													<i>Администратор снят.</i>
												<?php endif; ?>
											</div>
										</div>
										<?php endif; ?>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Будет снят</label>
											<div class="col-sm-8">
												<?php if ($this->_tpl_vars['ban']['expires'] == 'never'): ?>
													<i>Никогда.</i>
												<?php else: ?>
													<?php echo $this->_tpl_vars['ban']['expires']; ?>

												<?php endif; ?>
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Причина бана</label>
											<div class="col-sm-8">
												<?php echo ((is_array($_tmp=$this->_tpl_vars['ban']['reason'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

											</div>
										</div>
										<!--
										<?php if (! $this->_tpl_vars['hideadminname']): ?>
											<div class="form-group col-sm-12 m-b-5">
												<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Забанен админом</label>
												<div class="col-sm-8">
													<?php if (! empty ( $this->_tpl_vars['ban']['admin'] )): ?>
														<?php echo ((is_array($_tmp=$this->_tpl_vars['ban']['admin'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

													<?php else: ?>
														<i>Администратор снят.</i>
													<?php endif; ?>
												</div>
											</div>
										<?php endif; ?>
										-->
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Сервер</label>
											<div class="col-sm-8" id="ban_server_<?php echo $this->_tpl_vars['ban']['ban_id']; ?>
">
												<?php if ($this->_tpl_vars['ban']['server_id'] == 0): ?>
													Веб-бан
												<?php else: ?>
													Пожалуйста, подождите...
												<?php endif; ?>
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Предыдущие баны</label>
											<div class="col-sm-8">
												<?php echo $this->_tpl_vars['ban']['prevoff_link']; ?>

											</div>
										</div>
										<div class="form-group col-sm-12 m-b-5">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Блокировок (<?php echo $this->_tpl_vars['ban']['blockcount']; ?>
)</label>
											<div class="col-sm-8">
												<?php if ($this->_tpl_vars['ban']['banlog'] == ""): ?>
													<i>никогда...</i>
												<?php else: ?>
													<?php if ($this->_tpl_vars['ban']['blockcount'] >= '5'): ?>
														<?php echo smarty_function_help_icon(array('title' => "Блокировки",'message' => "У данного игрока было слишком много блокировок при входе на сервер, поэтому список был укомплектован."), $this);?>
 
														<a data-toggle="modal" href="#block_spoiler_<?php echo $this->_tpl_vars['ban']['ban_id']; ?>
_ply">
															Показать все <?php echo $this->_tpl_vars['ban']['blockcount']; ?>
 блокировок.
														</a>
														<div class="modal fade" id="block_spoiler_<?php echo $this->_tpl_vars['ban']['ban_id']; ?>
_ply" tabindex="-1" role="dialog" aria-hidden="true">
															<div class="modal-dialog modal-lg">
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title">
																			Список блокировок игрока: 
																			<?php if (empty ( $this->_tpl_vars['ban']['player'] )): ?>
																				<i><del>Скрыто :(</del></i>
																			<?php else: ?>
																				<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['ban']['player'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

																			<?php endif; ?>
																		</h4>
																	</div>
																	<div class="modal-body">
																		<p><?php echo $this->_tpl_vars['ban']['banlog']; ?>
</p>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-link bgm-blue c-white waves-effect" data-dismiss="modal">Закрыть</button>
																	</div>
																</div>
															</div>
														</div>
													<?php else: ?>
														<?php echo $this->_tpl_vars['ban']['banlog']; ?>

													<?php endif; ?>
												<?php endif; ?>
											</div>
										</div>
									</div>
									<div class="form-group col-sm-5">
									<?php if (! $this->_tpl_vars['hideadminname']): ?>
											<div class="wall-comment-list">
												
												<div class="pmo-block pmo-contact" style="font-size: 14px;">
													<div class="lv-header c-bluegray p-b-5 p-t-0" style="border-bottom: 2px solid #607d8b;">Блокировку Выдал:</div>
													<ul>
														<li class="p-b-5">
															<i class="zmdi zmdi-star c-red f-20"></i> 
																<?php if (! empty ( $this->_tpl_vars['ban']['admin'] )): ?>
																	<?php if ($this->_tpl_vars['ban']['admin'] != 'CONSOLE'): ?>
																		<b><?php echo ((is_array($_tmp=$this->_tpl_vars['ban']['admin'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b>
																	<?php else: ?>
																		<b><?php echo ((is_array($_tmp=$this->_tpl_vars['ConsoleName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b>
																	<?php endif; ?>
																<?php else: ?>
																	<b>Администратор был удален</b>
																<?php endif; ?>
														</li>
														<?php if ($this->_tpl_vars['ban']['admin'] != 'CONSOLE'): ?>
															<?php if (! empty ( $this->_tpl_vars['ban']['admin'] )): ?>
																<?php if ($this->_tpl_vars['admininfos']): ?>
																	<li class="p-b-5"><i class="zmdi zmdi-steam"></i> <?php if (! empty ( $this->_tpl_vars['ban']['admin_authid'] )): ?><?php echo $this->_tpl_vars['ban']['admin_authid']; ?>
 (<a href="http://steamcommunity.com/profiles/<?php echo $this->_tpl_vars['ban']['admin_authid_link']; ?>
" target="_blank">Профиль</a>)<?php else: ?>Нет данных...<?php endif; ?></li>
																	<li class="p-b-5"><i class="zmdi zmdi-vk"></i> <?php if (! empty ( $this->_tpl_vars['ban']['admin_vk'] )): ?><a href="https://vk.com/<?php echo $this->_tpl_vars['ban']['admin_vk']; ?>
" target="_blank">Линк</a><?php else: ?>Нет данных...<?php endif; ?></li>
																	<li class="p-b-5"><i class="zmdi zmdi-account-box-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Skype"></i> <?php if (! empty ( $this->_tpl_vars['ban']['admin_skype'] )): ?><?php echo $this->_tpl_vars['ban']['admin_skype']; ?>
<?php else: ?>Нет данных...<?php endif; ?></li>
																	<li class="p-b-5">
																		<i class="zmdi zmdi-info-outline" data-toggle="tooltip" data-placement="top" title="" data-original-title="Характеристика"></i>
																		<address class="m-b-0 ng-binding">
																			<?php if (! empty ( $this->_tpl_vars['ban']['admin_comm'] )): ?>
																				<?php echo $this->_tpl_vars['ban']['admin_comm']; ?>

																			<?php else: ?>
																				Нет данных. Обычный рядовой, контролирует порядок на серверах.
																			<?php endif; ?>
																		</address>
																	</li>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
													</ul>
												</div>
											</div>
										<?php endif; ?>
										<!-- COMMENT CODik-->
										<?php if ($this->_tpl_vars['view_comments']): ?>
											<hr class="m-t-10 m-b-10" />
											<div class="wall-comment-list">
												<?php if ($this->_tpl_vars['ban']['commentdata'] != "Нет"): ?>
													<div class="wcl-list">
														<?php $_from = $this->_tpl_vars['ban']['commentdata']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['commenta']):
?>
															<div class="media">
																<a href="#" class="pull-left">
																	<img src="theme/img/profile-pics/4.jpg" alt="" class="lv-img-sm">
																</a>
										 
																<div class="media-body">
																	<a href="#" class="a-title"><?php if (! empty ( $this->_tpl_vars['commenta']['comname'] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['commenta']['comname'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
<?php else: ?><i>Админ удален</i><?php endif; ?></a> <?php if ($this->_tpl_vars['commenta']['edittime'] != 'none'): ?><small class="c-gray m-l-10">редактировал <?php if ($this->_tpl_vars['commenta']['editname'] != 'none'): ?><?php echo $this->_tpl_vars['commenta']['editname']; ?>
<?php else: ?><i>Админ удален</i><?php endif; ?> в <?php echo $this->_tpl_vars['commenta']['edittime']; ?>
</small><?php endif; ?>
																	<p class="m-t-5 m-b-0" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><?php echo $this->_tpl_vars['commenta']['commenttxt']; ?>
</p>
																</div>
																
																	<ul class="actions" style="right: -1px;">
																		<li class="dropdown">
																			<a href="#" data-toggle="dropdown" aria-expanded="false">
																				<i class="zmdi zmdi-more-vert"></i>
																			</a>

																			<ul class="dropdown-menu dropdown-menu-right">
																				<?php if ($this->_tpl_vars['commenta']['editcomlink'] != 'none'): ?><li><?php echo $this->_tpl_vars['commenta']['editcomlink']; ?>
</li><?php endif; ?>
																				<?php if ($this->_tpl_vars['commenta']['delcomlink'] != 'none'): ?><li><?php echo $this->_tpl_vars['commenta']['delcomlink']; ?>
</li><?php endif; ?>
																			</ul>
																		</li>
																	</ul>
															</div>
														<?php endforeach; endif; unset($_from); ?>
													</div>
												<?php else: ?>
													<div class="wcl-list">
														<div class="media">
															<div class="media-body">
																<p class="m-t-5 m-b-0">Комментарии отсутствуют.</p>
															</div>
														</div>
													</div>
												<?php endif; ?>
												<!-- Comment form -->
													<div class="wcl-form m-t-15">
														<div class="wc-comment">
															<a href="<?php echo $this->_tpl_vars['ban']['addcomment_link']; ?>
">
																<div class="wcc-inner">
																	Добавить комментарий...
																</div>
															</a>
														</div>
													</div>
											</div>
										<?php endif; ?>
									<!-- COMMENT CODik-->
										</div>
								</div>
						</div>
					</td>
				</tr>
				<!-- ###############[ End Sliding Panel ]################## -->
				<!-- 
				<div class="modal fade" id="mod_<?php echo $this->_tpl_vars['ban']['admin_gid']; ?>
_mod" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"><b><?php echo $this->_tpl_vars['ban']['admin']; ?>
</b></h4>
                                        </div>
                                        <div class="modal-body f-15">
                                            <p>Доступный список данных администратора:</p>
											<p>
												<ul class="clist clist-angle">
													<?php if (! empty ( $this->_tpl_vars['ban']['admin_skype'] )): ?><li>Skype: <?php echo $this->_tpl_vars['ban']['admin_skype']; ?>
</li><?php endif; ?>
													<?php if (! empty ( $this->_tpl_vars['ban']['admin_vk'] )): ?><li>VK: <a href="https://vk.com/<?php echo $this->_tpl_vars['ban']['admin_vk']; ?>
">Линк</a></li><?php endif; ?>
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
			<?php endforeach; endif; unset($_from); ?>
		</tbody>
	</table>
	</div>
	<?php if ($this->_tpl_vars['general_unban'] || $this->_tpl_vars['can_delete']): ?>
		<div class="card-body card-padding">
			<div class="col-sm-12 p-l-0">
				<div class="col-sm-2 p-0">
					<select class="selectpicker " name="bulk_action" id="bulk_action" onchange="BulkEdit(this,'<?php echo $this->_tpl_vars['admin_postkey']; ?>
');">
						<option value="-1">Выберите</option>
						<?php if ($this->_tpl_vars['general_unban']): ?>
						<option value="U">Разбан</option>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['can_delete']): ?>
						<option value="D">Удалить</option>
						<?php endif; ?>
					</select>
				</div>
				<?php if ($this->_tpl_vars['can_export']): ?>
					<div class="col-sm-7 p-t-10 text-center">
						Скачать перманентные&nbsp;(&nbsp;<a href="./exportbans.php?type=steam" title="Экспорт перманентных SteamID банов">SteamID</a>&nbsp;/&nbsp;
						<a href="./exportbans.php?type=ip" title="Экспорт перманентных IP банов">IP</a>&nbsp;)&nbsp; баны.
					</div>
				<?php endif; ?>
				<div class="col-sm-3 p-r-0 text-right" style="float:right;">
					<button class="btn bgm-bluegray waves-effect" onclick="window.location.href='index.php?p=banlist&hideinactive=<?php if ($this->_tpl_vars['hidetext_darf'] == '1'): ?>true<?php else: ?>false<?php endif; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['searchlink'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
'"><?php echo $this->_tpl_vars['hidetext']; ?>
&nbsp;баны</button>
				</div>
			</div>
		</div>&nbsp;
	<?php endif; ?>
</div>

<?php echo '
<script type="text/javascript">window.addEvent(\'domready\', function(){	
InitAccordion(\'tr.opener\', \'div.opener\', \'content\');
'; ?>

<?php if ($this->_tpl_vars['view_bans']): ?>
$('tickswitch').value=0;
<?php endif; ?>
<?php echo '
}); 
</script>
'; ?>

<?php endif; ?>