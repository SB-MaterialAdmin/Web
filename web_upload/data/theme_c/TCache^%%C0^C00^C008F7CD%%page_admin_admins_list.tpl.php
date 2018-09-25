<?php /* Smarty version 2.6.29, created on 2018-09-18 17:11:29
         compiled from page_admin_admins_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'page_admin_admins_list.tpl', 138, false),array('function', 'help_icon', 'page_admin_admins_list.tpl', 181, false),)), $this); ?>
<?php if (! $this->_tpl_vars['permission_listadmin']): ?>
	Доступ запрещен
<?php else: ?>
	<button onclick="window.location.href='<?php echo $this->_tpl_vars['btn_href']; ?>
'" class="btn btn-float btn-danger m-btn" <?php echo $this->_tpl_vars['btn_helpa']; ?>
><i class="zmdi <?php echo $this->_tpl_vars['btn_icon']; ?>
"></i></button>
	<div class="card-header">
		<h2>Список администраторов (<?php if (! $this->_tpl_vars['btn_rem']): ?>Активных: <?php else: ?>Истекших: <?php endif; ?><span id="admincount"><?php echo $this->_tpl_vars['admin_count']; ?>
</span>) 
			<small>
				Нажмите на нужного вам администратора в таблице, чтобы узнать больше информации о нем. <?php echo $this->_tpl_vars['admin_nav_p']; ?>

			</small>
		</h2>

		<ul class="actions" id="banlist-nav">
			<?php echo $this->_tpl_vars['admin_nav']; ?>

		</ul>
		<?php if ($this->_tpl_vars['btn_rem']): ?><br><?php echo $this->_tpl_vars['btn_rem']; ?>
<?php endif; ?>
	</div>
	<?php  require (TEMPLATES_PATH . "/admin.admins.search.php"); ?>
	
	<div class="table-responsive" id="banlist">
		<table cellspacing="0" cellpadding="0" class="table table-striped">
			<tr>
				<th>Имя</th>
				<th>Группа доступа к серверу</th>
				<th>Группа доступа к ВЕб-панели</th>
				<th class="text-right">Истекает</th>
			</tr>
			<?php $_from = ($this->_tpl_vars['admins']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['admin']):
?>
				<tr onmouseout="this.className='opener'" onmouseover="this.className='info opener'" class="opener" style="cursor: pointer;">
					<td>
						<?php echo $this->_tpl_vars['admin']['user']; ?>
 / <mark data-toggle="tooltip" data-placement="right" title="" data-original-title="Имммунитет администратора"><?php echo $this->_tpl_vars['admin']['immunity']; ?>
</mark>
					</td>
					<td><?php echo $this->_tpl_vars['admin']['server_group']; ?>
</td>
					<td><?php echo $this->_tpl_vars['admin']['web_group']; ?>
</td>
					<td class="text-right"><?php echo $this->_tpl_vars['admin']['expired_text']; ?>
</td>
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
												<img src="<?php echo $this->_tpl_vars['admin']['avatar']; ?>
">
											</a>
											<a href="http://steamcommunity.com/profiles/<?php echo $this->_tpl_vars['admin']['communityid_profile']; ?>
" class="pmop-edit" target="_blank">
												<i class="zmdi zmdi-steam"></i> <span class="hidden-xs">Профиль стима</span>
											</a>
										</div>
									</div>
									<div class="pmo-block pmo-contact hidden-xs p-t-0">
										<div style="text-align: center;padding-bottom: 20px;"></div>
										<h2>Связь</h2>
										<ul>
											<li><i class="zmdi zmdi-steam" data-toggle="tooltip" data-placement="top" title="" data-original-title="Steam"></i> <?php echo $this->_tpl_vars['admin']['steam_id_amd']; ?>
</li>
											<li><i class="zmdi zmdi-account-box-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Skype"></i> <?php echo $this->_tpl_vars['admin']['sk_profile']; ?>
</li>
											<li><i class="zmdi zmdi-email"></i> <?php echo $this->_tpl_vars['admin']['email_profile']; ?>
 (<a href="mailto:<?php echo $this->_tpl_vars['admin']['email_profile']; ?>
">написать</a>)</li>
											<li><i class="zmdi zmdi-vk"></i> <?php echo $this->_tpl_vars['admin']['vk_profile']; ?>
</li>
										</ul>
									</div>
									
									<div class="pmo-block hidden-xs p-t-0">
										<h2>Права</h2>
										
												<a class="btn btn-primary btn-block waves-effect" data-toggle="modal" href="#modalWider_srv<?php echo $this->_tpl_vars['admin']['aid']; ?>
">
													Серверные
												</a>
												<br>
												<br>
												<a class="btn btn-primary btn-block waves-effect" data-toggle="modal" href="#modalWider_web<?php echo $this->_tpl_vars['admin']['aid']; ?>
">
													Веб
												</a>
									</div>
									
									<!-- Modal -->	
									<div class="modal fade" id="modalWider_srv<?php echo $this->_tpl_vars['admin']['aid']; ?>
" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-sm">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title">Информация</h4>
												</div>
												<div class="modal-body">
													<?php echo $this->_tpl_vars['admin']['server_flag_string']; ?>

												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-link" data-dismiss="modal">Закрыть</button>
												</div>
											</div>
										</div>
									</div>
									<!-- Modal -->
									
									<!-- Modal -->	
									<div class="modal fade" id="modalWider_web<?php echo $this->_tpl_vars['admin']['aid']; ?>
" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-sm">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title">Информация</h4>
												</div>
												<div class="modal-body">
													<?php echo $this->_tpl_vars['admin']['web_flag_string']; ?>

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
									<?php if ($this->_tpl_vars['permission_editadmin']): ?>
										<ul class="tab-nav tn-justified">
											<li class="bgm-lightblue waves-effect"><a class="c-white" href="index.php?p=admin&c=admins&o=editdetails&id=<?php echo $this->_tpl_vars['admin']['aid']; ?>
">Детали</a></li>
											<li class="bgm-lightblue waves-effect"><a class="c-white" href="index.php?p=admin&c=admins&o=editpermissions&id=<?php echo $this->_tpl_vars['admin']['aid']; ?>
">Привилегии</a></li>
											<li class="bgm-lightblue waves-effect"><a class="c-white" href="index.php?p=admin&c=admins&o=editservers&id=<?php echo $this->_tpl_vars['admin']['aid']; ?>
">Сервер</a></li>
											<li class="bgm-lightblue waves-effect"><a class="c-white" href="index.php?p=admin&c=admins&o=editgroup&id=<?php echo $this->_tpl_vars['admin']['aid']; ?>
">Группа</a></li>
											<?php if ($this->_tpl_vars['allow_warnings']): ?>
												<li class="btn-warning waves-effect"><a class="c-white" href="index.php?p=admin&c=admins&o=warnings&id=<?php echo $this->_tpl_vars['admin']['aid']; ?>
">Предупреждения (<?php echo $this->_tpl_vars['admin']['warnings']; ?>
 из <?php echo $this->_tpl_vars['maxWarnings']; ?>
)</a></li>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['permission_deleteadmin']): ?>
												<li class="btn-danger waves-effect"><a class="c-white" href="#" onclick="<?php echo $this->_tpl_vars['admin']['del_link_d']; ?>
">Удалить</a></li>
											<?php endif; ?>
										</ul>
									<?php endif; ?>
									
									
									<div class="pmb-block  p-t-30">
										<div class="pmbb-header">
											<h2><i class="zmdi zmdi-comment m-r-5"></i> Комментарий</h2>
										</div>
										<div class="pmbb-body p-l-30">
											<div class="pmbb-view">
												<?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['comment_profile'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

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
													<dd><?php echo $this->_tpl_vars['admin']['expired_cv']; ?>
</dd>
												</dl>
												<dl class="dl-horizontal">
													<dt>Последний визит</dt>
													<dd><?php echo $this->_tpl_vars['admin']['lastvisit']; ?>
</dd>
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
													<dd><?php echo $this->_tpl_vars['admin']['bancount']; ?>
 <a href="./index.php?p=banlist&advSearch=<?php echo $this->_tpl_vars['admin']['aid']; ?>
&advType=admin">(найти)</a></dd>
												</dl>
												<dl class="dl-horizontal">
													<dt>Баны с демо</dt>
													<dd><?php echo $this->_tpl_vars['admin']['nodemocount']; ?>
 <a href="./index.php?p=banlist&advSearch=<?php echo $this->_tpl_vars['admin']['aid']; ?>
&advType=nodemo">(найти)</a></dd>
												</dl>
											</div>
										</div>
									</div>
									<div class="pmb-block p-t-10 m-b-0">
										<div class="pmbb-header">
											<h2><?php echo smarty_function_help_icon(array('title' => "Поддержка",'message' => "Можете добавить данного администратора в список(который находится возле поиска) авторов данного РеФорка, под категорией 'Администраторы'.",'style' => "padding-top: 3px;"), $this);?>
 Support-List</h2>
										</div>
										<div class="pmbb-body p-l-30">
											<div class="pmbb-view">
												<dl class="dl-horizontal">
													<dt>Добавить в список?</dt>
													<dd>
														<div class="toggle-switch p-b-5" data-ts-color="red">
															<input type="checkbox" id="add_support_<?php echo $this->_tpl_vars['admin']['aid']; ?>
" name="add_support_<?php echo $this->_tpl_vars['admin']['aid']; ?>
" TABINDEX=9 onclick="xajax_AddSupport(<?php echo $this->_tpl_vars['admin']['aid']; ?>
);" hidden="hidden" /> 
															<label for="add_support_<?php echo $this->_tpl_vars['admin']['aid']; ?>
" class="ts-helper checkbox-inline m-r-20" style="z-index:2;"></label>
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
			<?php endforeach; endif; unset($_from); ?>
		</table>
	</div>
	
	<script type="text/javascript">
		<?php $_from = $this->_tpl_vars['checked_if']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kek']):
?>
			$("add_support_<?php echo $this->_tpl_vars['kek']['kid']; ?>
").checked = 1;
		<?php endforeach; endif; unset($_from); ?>
	</script>
	<script type="text/javascript">InitAccordion('tr.opener', 'div.opener', 'content');</script>
<?php endif; ?>