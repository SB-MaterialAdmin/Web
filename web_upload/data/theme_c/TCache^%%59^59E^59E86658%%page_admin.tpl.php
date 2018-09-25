<?php /* Smarty version 2.6.29, created on 2018-09-25 13:22:59
         compiled from page_admin.tpl */ ?>
<div class="card" id="cpanel">
		<div class="card-header">
			<h2>Панель управления
				<small>
					Выберите опции администрирования
				</small>
			</h2>
		</div>
		
		<div class="card-body">
			<div class="col-sm-12">
				<?php if ($this->_tpl_vars['access_admins']): ?>
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body bgm-amber card-padding c-white">
								В этом разделе вы можете управлять администраторами вашей системы SourceBans. Просмотреть действующих, добавить нового, а также назначить переопределения
							</div>
							<div class="card-body c-white">
								<button class="btn btn-primary btn-block waves-effect" onclick="window.location.href='index.php?p=admin&amp;c=admins'">Управление Админами</button>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['access_servers']): ?>
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body card-padding bgm-teal c-white">
								Этот раздел предназначен для управления всеми вашими серверами, а так же для добавления новых<br><br><br>
							</div>
							<div class="card-body c-white">
								<button class="btn btn-primary btn-block waves-effect" onclick="window.location.href='index.php?p=admin&amp;c=servers'">Управление серверами</button>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['access_bans']): ?>
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body card-padding bgm-lime c-white">
								Хотите посмотреть, добавить или удалить бан? То этот раздел именно для этого. Так же здесь можно увидеть кто на кого пожаловался, а кто хочет помилования. Еще и импортировать баны
							</div>
							<div class="card-body c-white">
								<button class="btn btn-primary btn-block waves-effect" onclick="window.location.href='index.php?p=admin&amp;c=bans'">Управление банами</button>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-sm-12">
				<?php if ($this->_tpl_vars['access_groups']): ?>
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body card-padding bgm-pink c-white">
								Здесь вы найдете информацию о всех группах вашей системы SourceBans, а так же можете добавить новую или изменить существующую<br>
							</div>
							<div class="card-body c-white">
								<button class="btn btn-primary btn-block waves-effect" onclick="window.location.href='index.php?p=admin&amp;c=groups'">Управление группами</button>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['access_settings']): ?>
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body card-padding bgm-brown c-white">
								Этот раздел содержит основные настройки SourceBans. Здесь вы можете настроить вид SourceBans, изменить описание, просмотреть системный лог и т.д.
							</div>
							<div class="card-body c-white">
								<button class="btn btn-primary btn-block waves-effect" onclick="window.location.href='index.php?p=admin&amp;c=settings'">Управление настройками</button>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['access_mods']): ?>
					<div class="col-sm-4">
						<div class="card">
							<div class="card-body card-padding bgm-gray c-white">
								Данный раздел предназначен для просмотра, изменения и добавления различных модификаций игр<br><br>
							</div>
							<div class="card-body c-white">
								<button class="btn btn-primary btn-block waves-effect" onclick="window.location.href='index.php?p=admin&amp;c=mods'">Управление MOD's</button>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		
		<div class="card-body card-padding p-b-0">
			<div class="form-group m-b-5 col-sm-6">
				<label class="col-sm-5 control-label">Последний релиз:</label>
				<div class="col-sm-5 control-label" style="text-align: left;" <?php if (! $this->_tpl_vars['sb_svn']): ?>id='relver'<?php else: ?>id='svnrev'<?php endif; ?>>
					Подождите...
				</div>
			</div>
			<div class="form-group m-b-5 col-sm-6">
				<label class="col-sm-5 control-label">Всего админов:</label>
				<div class="col-sm-5 control-label" style="text-align: left;">
					<?php echo $this->_tpl_vars['total_admins']; ?>

				</div>
			</div>
			<div class="form-group m-b-5 col-sm-6">
				<label class="col-sm-5 control-label">Всего банов:</label>
				<div class="col-sm-5 control-label" style="text-align: left;">
					<?php echo $this->_tpl_vars['total_bans']; ?>

				</div>
			</div>
			<div class="form-group m-b-5 col-sm-6">
				<label class="col-sm-5 control-label">Блокировки подключений:</label>
				<div class="col-sm-5 control-label" style="text-align: left;">
					<?php echo $this->_tpl_vars['total_blocks']; ?>

				</div>
			</div>
			<div class="form-group m-b-5 col-sm-6">
				<label class="col-sm-5 control-label">Размер всех демок:</label>
				<div class="col-sm-5 control-label" style="text-align: left;">
					<strong><?php echo $this->_tpl_vars['demosize']; ?>
</strong>
				</div>
			</div>
			<div class="form-group m-b-5 col-sm-6">
				<label class="col-sm-5 control-label">Всего серверов:</label>
				<div class="col-sm-5 control-label" style="text-align: left;">
					<?php echo $this->_tpl_vars['total_servers']; ?>

				</div>
			</div>
			<div class="form-group m-b-5 col-sm-6">
				<label class="col-sm-5 control-label">Протесты банов:</label>
				<div class="col-sm-5 control-label" style="text-align: left;">
					<?php echo $this->_tpl_vars['total_protests']; ?>

				</div>
			</div>
			<div class="form-group m-b-5 col-sm-6">
				<label class="col-sm-5 control-label">Заявок на бан:</label>
				<div class="col-sm-5 control-label" style="text-align: left;">
					<?php echo $this->_tpl_vars['total_submissions']; ?>

				</div>
			</div>
			<div class="form-group m-b-5 col-sm-6">
				<label class="col-sm-5 control-label">Протесты в архиве:</label>
				<div class="col-sm-5 control-label" style="text-align: left;">
					<?php echo $this->_tpl_vars['archived_protests']; ?>

				</div>
			</div>
			<div class="form-group m-b-5 col-sm-6">
				<label class="col-sm-5 control-label">Заявок в архиве:</label>
				<div class="col-sm-5 control-label" style="text-align: left;">
					<?php echo $this->_tpl_vars['archived_submissions']; ?>

				</div>
			</div>
			&nbsp;<br />
		</div>
	&nbsp;
</div>
<br />
<script type="text/javascript">xajax_CheckVersion();</script>