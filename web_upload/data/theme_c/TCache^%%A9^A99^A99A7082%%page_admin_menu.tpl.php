<?php /* Smarty version 2.6.29, created on 2018-09-24 18:13:31
         compiled from page_admin_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'page_admin_menu.tpl', 25, false),array('modifier', 'stripslashes', 'page_admin_menu.tpl', 25, false),array('function', 'help_icon', 'page_admin_menu.tpl', 55, false),array('function', 'display_material_checkbox', 'page_admin_menu.tpl', 101, false),array('function', 'sb_button', 'page_admin_menu.tpl', 105, false),)), $this); ?>
<div id="0">
	<div class="card">
		<div class="card-header">
		<h2>Меню <small>Позволяет управлять ссылками в главном меню SourceBans.</small></h2>
		</div>

		<div class="card-body table-responsive">
				<table width="100%" class="table">
					<thead>
						<tr>
							<th width="5%">Активно</th>
							<th width="15%">Заголовок</th>
							<th width="25%">Описание</th>
							<th width="15%">Линк</th>
							<th width="18%">Действие</th>
						</tr>
					</thead>
					<tbody>
						<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
							<tr>
								<td>
									<b><?php if ($this->_tpl_vars['menu']['enabled'] == '1'): ?>Да<?php else: ?>Нет<?php endif; ?></b> / <mark data-toggle="tooltip" data-placement="right" title="" data-original-title="Приоритет ссылки"> <?php echo $this->_tpl_vars['menu']['priority']; ?>
 </mark>
								</td>
								<td>
									<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['menu']['text'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

								</td>
								<td>
									<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['menu']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>

								</td>
								<td>
									<a href="<?php echo $this->_tpl_vars['menu']['url']; ?>
"><?php echo $this->_tpl_vars['menu']['url']; ?>
</a>
								</td>
								<td class="center">
									<?php if ($this->_tpl_vars['menu']['system'] != '1'): ?><a href="index.php?p=admin&c=menu&o=del&id=<?php echo $this->_tpl_vars['menu']['id']; ?>
">Удалить</a> / <?php endif; ?><a href="index.php?p=admin&c=menu&o=edit&id=<?php echo $this->_tpl_vars['menu']['id']; ?>
">Изменить</a> <?php if ($this->_tpl_vars['menu']['enabled'] != '1'): ?>/ <a href="index.php?p=admin&c=menu&o=on&id=<?php echo $this->_tpl_vars['menu']['id']; ?>
">Включить</a> <?php else: ?>/ <a href="index.php?p=admin&c=menu&o=off&id=<?php echo $this->_tpl_vars['menu']['id']; ?>
">Отключить</a><?php endif; ?>
								</td>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>
		</div>
	</div>
</div>

<form action="" method="post">
	<div class="card" id="admin-page-content">
		<div id="1" style="display:none;">
		<input type="hidden" name="Link" value="add" />
		<div class="form-horizontal" role="form" id="add-group">
			<div class="card-header">
				<h2>Меню <small>Позволяет управлять ссылками в главном меню SourceBans.</small></h2>
			</div>
			<div class="alert alert-info" role="alert">Вы можете добавлять или заменять иконки ссылок! Иконки используются из фреймворка <i>Material Design Iconic Font</i>. Доступные иконки вы можете просмотреть <a href="http://zavoloklom.github.io/material-design-iconic-font/examples.html" target="_blank">здесь</a>.</div>
			<div class="card-body card-padding p-b-0" id="group.details">
				<div class="form-group m-b-5">
					<label for="names_link" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Заголовок",'message' => "Введите заголовок названия ссылки. Грубо говоря 'Имя' ссылки."), $this);?>
 Заголовок</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="names_link" name="names_link" placeholder="Введите данные" />
						</div>
						<div id="names_link.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="des_link" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Описание",'message' => "Введите описание ссылки, которое вылазиет при наводе курсором мыши на ссылку."), $this);?>
 Описание</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="des_link" name="des_link" placeholder="Введите данные" />
						</div>
						<div id="des_link.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="url_link" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Линк",'message' => "Линк, на который переадресует пользователя, после нажатия на заголовок ссылки."), $this);?>
 URL</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="url_link" name="url_link" placeholder="Введите данные" />
						</div>
						<div id="url_link.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="priora_link" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Приоритет",'message' => "Приоритет ссылки, позволяет вставить ссылку в определенное место, тем самым сортируя показ ссылки в главном меню SourceBans."), $this);?>
 Приоритет</label>
					<div class="col-sm-9">
						<div class="fg-line">
							<input type="text" TABINDEX=1 class="form-control" id="priora_link" name="priora_link" placeholder="Введите данные" />
						</div>
						<div id="priora_link.msg"></div>
					</div>
				</div>
				<div class="form-group m-b-5">
					<label for="on_link" class="col-sm-3 control-label"> Статус</label>
					<div class="col-sm-9">
						<div class="checkbox m-b-15">
							<label for="on_link">
									<input type="checkbox" name="on_link" id="on_link" hidden="hidden" />
								<i class="input-helper"></i> Включить?
							</label>
						</div>
					</div>
				</div>
				<?php echo materialdesign_checkbox(array('name' => 'onNewTab','help_title' => "Открывать в новой вкладке",'help_text' => "При щелчке по пункту в меню, он будет открываться в новой вкладке браузера, если здесь установлена галочка."), $this);?>

				
			</div>
			<div class="card-body card-padding text-center">
				<?php echo smarty_function_sb_button(array('text' => "Добавить",'icon' => "<i class='zmdi zmdi-check-all'></i>",'class' => "bgm-green btn-icon-text",'submit' => true), $this);?>

			    &nbsp;
			    <?php echo smarty_function_sb_button(array('text' => "Назад",'onclick' => "history.go(-1)",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'back','submit' => false), $this);?>

			</div>
		</div>
		</div>
	</div>
	
</form>