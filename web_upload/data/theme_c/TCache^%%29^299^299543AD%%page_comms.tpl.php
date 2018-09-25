<?php /* Smarty version 2.6.29, created on 2018-09-18 17:08:06
         compiled from page_comms.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sb_button', 'page_comms.tpl', 37, false),array('modifier', 'escape', 'page_comms.tpl', 82, false),array('modifier', 'stripslashes', 'page_comms.tpl', 82, false),array('modifier', 'count', 'page_comms.tpl', 85, false),)), $this); ?>
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
						<textarea class="form-control auto-size" placeholder="Ваш комментарий...." id="commenttext" name="commenttext"><?php echo $this->_tpl_vars['commenttext']; ?>
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
					<?php echo smarty_function_sb_button(array('text' => 'Back','onclick' => "history.go(-1)",'class' => "m-t-15 btn btn-sm",'id' => 'aback'), $this);?>

				</li>
			</ul>
		</div>
    </div>
</div>
<!--Код Комментариев-->
<?php else: ?>
<div class="card">
	<div class="card-header">
		<h2>Список блоков коммуникаций
			<small>
				Общее количество: <?php echo $this->_tpl_vars['total_bans']; ?>
 <?php echo $this->_tpl_vars['ban_nav_p']; ?>

			</small>
		</h2>
		
		<div class="actions" id="banlist-nav">
			<?php echo $this->_tpl_vars['ban_nav']; ?>

		</div>
	</div>
	
	<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
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
				<tr class="opener" <?php if ($this->_tpl_vars['ban']['server_id'] != 0): ?>onclick="xajax_ServerHostPlayers(<?php echo $this->_tpl_vars['ban']['server_id']; ?>
, <?php echo $this->_tpl_vars['ban']['ban_id']; ?>
);"<?php endif; ?> style="cursor: pointer;">
					<td class="text-center"><?php echo $this->_tpl_vars['ban']['mod_icon']; ?>
</td>
					<td class="text-center"><?php echo $this->_tpl_vars['ban']['ban_date_info']; ?>
</td>
					<td>
						<div style="float:left;"><?php echo $this->_tpl_vars['ban']['type_icon_p']; ?>

							<?php if (empty ( $this->_tpl_vars['ban']['player'] )): ?>
								<i>имя игрока скрыто</i>
							<?php else: ?>
								<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['ban']['player'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

							<?php endif; ?>
						</div>
						<?php if ($this->_tpl_vars['view_comments'] && $this->_tpl_vars['ban']['commentdata'] != "Нет" && count($this->_tpl_vars['ban']['commentdata']) > 0): ?>
							<div style="float:right;">
								<?php echo count($this->_tpl_vars['ban']['commentdata']); ?>
 <img src="theme/img/comm.png" alt="Comments" title="Comments" style="height:14px;width:14px;" />
							</div>
						<?php endif; ?>
					</td>
					<?php if (! $this->_tpl_vars['hideadminname']): ?>
						<td class="text-center">
							<?php if (! empty ( $this->_tpl_vars['ban']['admin'] )): ?>
								<?php if ($this->_tpl_vars['ban']['admin'] != 'CONSOLE'): ?>
									<?php echo ((is_array($_tmp=$this->_tpl_vars['ban']['admin'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

								<?php else: ?>
									<?php echo ((is_array($_tmp=$this->_tpl_vars['ConsoleName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

								<?php endif; ?>
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
									<h2>Информация о блокировке:</h2>

									<?php if ($this->_tpl_vars['view_bans']): ?>
									<ul class="actions actions-alt">
										<li class="dropdown">
											<a href="#" data-toggle="dropdown" aria-expanded="false">
												<i class="zmdi zmdi-more-vert"></i>
											</a>

											<ul class="dropdown-menu dropdown-menu-right">
												<?php if ($this->_tpl_vars['ban']['unbanned'] && $this->_tpl_vars['ban']['reban_link'] != false): ?>
												  <li><?php echo $this->_tpl_vars['ban']['reban_link']; ?>
</li>
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
											</ul>
										</li>
									</ul>
									<?php endif; ?>
								</div>
								<div class="card-body card-padding">
									<div class="form-group col-sm-7">
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i>  Игрок</label>
											<div class="col-sm-8">
												<?php if (empty ( $this->_tpl_vars['ban']['player'] )): ?>
													<i>имя игрока скрыто.</i>
												<?php else: ?>
													<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['ban']['player'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

												<?php endif; ?>
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Steam ID</label>
											<div class="col-sm-8">
												<?php if (empty ( $this->_tpl_vars['ban']['steamid'] )): ?>
													<i>Steam ID игрока скрыт.</i>
												<?php else: ?>
													<?php echo $this->_tpl_vars['ban']['steamid']; ?>

												<?php endif; ?>
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Steam3 ID</label>
											<div class="col-sm-8">
												<?php if (empty ( $this->_tpl_vars['ban']['steamid'] )): ?>
													<i>Steam3 ID игрока скрыт.</i>
												<?php else: ?>
													<a href="http://steamcommunity.com/profiles/<?php echo $this->_tpl_vars['ban']['steamid3']; ?>
" target="_blank"><?php echo $this->_tpl_vars['ban']['steamid3']; ?>
</a>
												<?php endif; ?>
											</div>
										</div>
										<?php if ($this->_tpl_vars['ban']['type'] == 0): ?>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Steam Community</label>
											<div class="col-sm-8">
												<a href="http://steamcommunity.com/profiles/<?php echo $this->_tpl_vars['ban']['communityid']; ?>
" target="_blank"><?php echo $this->_tpl_vars['ban']['communityid']; ?>
</a>
											</div>
										</div>
										<?php endif; ?>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Был выдан</label>
											<div class="col-sm-8">
												<?php echo $this->_tpl_vars['ban']['ban_date']; ?>

											</div>
										</div>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Длительность</label>
											<div class="col-sm-8">
												<?php if ($this->_tpl_vars['ban']['ub_reason']): ?><del><?php echo $this->_tpl_vars['ban']['banlength']; ?>
</del> <?php echo $this->_tpl_vars['ban']['ub_reason']; ?>
<?php else: ?><?php echo $this->_tpl_vars['ban']['banlength']; ?>
<?php endif; ?>
											</div>
										</div>
										<?php if ($this->_tpl_vars['ban']['unbanned']): ?>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Причина снятия</label>
											<div class="col-sm-8">
												<?php if ($this->_tpl_vars['ban']['ureason'] == ""): ?>
													<i>Причина разбана скрыта.</i>
												<?php else: ?>
													<?php echo $this->_tpl_vars['ban']['ureason']; ?>

												<?php endif; ?>
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Блокировку снял</label>
											<div class="col-sm-8">
												 <?php if (! empty ( $this->_tpl_vars['ban']['removedby'] )): ?>
													<?php echo ((is_array($_tmp=$this->_tpl_vars['ban']['removedby'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

												<?php else: ?>
													<i>Администратор снят</i>
												<?php endif; ?>
											</div>
										</div>
										<?php endif; ?>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Будет снят</label>
											<div class="col-sm-8">
												<?php if ($this->_tpl_vars['ban']['expires'] == 'never'): ?>
													<i>Бан навсегда.</i>
												<?php else: ?>
													<?php echo $this->_tpl_vars['ban']['expires']; ?>

												<?php endif; ?>
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Причина блокировки</label>
											<div class="col-sm-8">
												<?php echo ((is_array($_tmp=$this->_tpl_vars['ban']['reason'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

											</div>
										</div>
										<?php if (! $this->_tpl_vars['hideadminname']): ?>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Выдана админом</label>
											<div class="col-sm-8">
												<?php if (! empty ( $this->_tpl_vars['ban']['admin'] )): ?>
													<?php echo ((is_array($_tmp=$this->_tpl_vars['ban']['admin'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

												<?php else: ?>
													<i>Администратор снят.</i>
												<?php endif; ?>
											</div>
										</div>
										<?php endif; ?>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Сервер</label>
											<div class="col-sm-8" id="ban_server_<?php echo $this->_tpl_vars['ban']['ban_id']; ?>
">
												<?php if ($this->_tpl_vars['ban']['server_id'] == 0): ?>
													Веб-бан
												<?php else: ?>
													Пожалуйста, подождите...
												<?php endif; ?>
											</div>
										</div>
										<div class="form-group col-sm-12 m-b-10">
											<label class="col-sm-4 control-label"><i class="zmdi zmdi-star text-left"></i> Предыдущие блокировки</label>
											<div class="col-sm-8">
												<?php echo $this->_tpl_vars['ban']['prevoff_link']; ?>

											</div>
										</div>
									</div>
																		<div class="form-group col-sm-5">
										<!-- COMMENT CODik-->
										<div class="wall-comment-list">
										<?php if ($this->_tpl_vars['view_comments']): ?>
											<?php if ($this->_tpl_vars['ban']['commentdata'] != "Нет"): ?>
												<!-- Comment Listing -->
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
<?php else: ?><i>Админ удален</i><?php endif; ?></a> <?php if (! empty ( $this->_tpl_vars['commenta']['edittime'] )): ?><small class="c-gray m-l-10">last edit <?php echo $this->_tpl_vars['commenta']['edittime']; ?>
 by <?php if (! empty ( $this->_tpl_vars['commenta']['editname'] )): ?><?php echo $this->_tpl_vars['commenta']['editname']; ?>
<?php else: ?><i>Admin deleted</i><?php endif; ?></small><?php endif; ?>
															<p class="m-t-5 m-b-0" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><?php echo $this->_tpl_vars['commenta']['commenttxt']; ?>
</p>
														</div>
														
														<?php if ($this->_tpl_vars['commenta']['editcomlink'] != ""): ?>
															<ul class="actions">
																<li class="dropdown">
																	<a href="#" data-toggle="dropdown" aria-expanded="false">
																		<i class="zmdi zmdi-more-vert"></i>
																	</a>

																	<ul class="dropdown-menu dropdown-menu-right">
																		<li><?php echo $this->_tpl_vars['commenta']['editcomlink']; ?>
</li>
																		<li><?php echo $this->_tpl_vars['commenta']['delcomlink']; ?>
</li>
																	</ul>
																</li>
															</ul>
														<?php endif; ?>
													</div>
													<?php endforeach; endif; unset($_from); ?>
												</div>
											<?php else: ?>
												<!-- Comment Listing -->
												<div class="wcl-list">
													<div class="media">
														<div class="media-body">
															<p class="m-t-5 m-b-0"><?php if ($this->_tpl_vars['ban']['commentdata'] == 'None'): ?>Комментарии отсутствуют.<?php endif; ?></p>
														</div>
													</div>
												</div>
											<?php endif; ?>
										<?php else: ?>
											<!-- Comment Listing -->
											<div class="wcl-list">
												<div class="media">
													<div class="media-body">
														<p class="m-t-5 m-b-0">Просматривать комментарии и оставлять их - разрешено только авторизированным пользователям!</p>
													</div>
												</div>
											</div>
										<?php endif; ?>
											<!-- Comment form -->
											<div class="wcl-form">
												<div class="wc-comment">
													<?php if ($this->_tpl_vars['view_comments']): ?>
														<a href="<?php echo $this->_tpl_vars['ban']['addcomment_link']; ?>
">
															<div class="wcc-inner">
																Добавить комментарий...
															</div>
														</a>
													<?php else: ?>
														<div class="wcc-inner">
															Доступ запрещен...
														</div>
													<?php endif; ?>
												</div>
											</div>
										</div>
										
										<!-- COMMENT CODik-->
									</div>
								</div>
						</div>
					</td>
				</tr>
				<!-- ###############[ End Sliding Panel ]################## -->
			<?php endforeach; endif; unset($_from); ?>
		</tbody>
	</table>
	</div>
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