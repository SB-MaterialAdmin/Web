<?php /* Smarty version 2.6.29, created on 2018-09-18 17:04:10
         compiled from page_admin_bans_import.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'display_header', 'page_admin_bans_import.tpl', 4, false),array('function', 'help_icon', 'page_admin_bans_import.tpl', 9, false),array('function', 'display_material_checkbox', 'page_admin_bans_import.tpl', 26, false),array('function', 'sb_button', 'page_admin_bans_import.tpl', 29, false),)), $this); ?>
<?php if (! $this->_tpl_vars['permission_import']): ?>
	Доступ запрещен!
<?php else: ?>
    <?php echo materialdesign_cardheader(array('title' => "Импорт банов",'text' => "За дополнительной информацией или помощью наведите курсор мыши на знак вопроса."), $this);?>

    <div class="card-body card-padding p-b-0 clearfix">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="importBans" />
            <div class="form-group m-b-5">
                <label for="file" class="col-sm-3 control-label"><?php echo smarty_function_help_icon(array('title' => "Файл",'message' => "Выберите файл banned_users.cfg или banned_ip.cfg для загрузки и импорта банов."), $this);?>
 Файл </label>
                <div class="col-sm-9">
                    <!-- <div class="fg-line">
                        <input type="file" TABINDEX=1 class="file" id="importFile" name="importFile" />
                    </div> -->
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <span class="btn btn-primary btn-file m-r-10 waves-effect">
                            <span class="fileinput-new">Выбрать файл</span>
                            <span class="fileinput-exists">Изменить</span>
                            <input name="importFile" type="file" />
                        </span>
                        <span class="fileinput-filename"></span>
                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput">×</a>
                    </div>
                    <div id="file.msg" class="badentry"></div>
                </div>
            </div>
            <?php echo materialdesign_checkbox(array('name' => 'friendsname','help_title' => "Получить имена",'help_text' => "Поставьте флажок, если Вы хотите получить имена игроков из их профиля Steam. (работает с  banned_users.cfg)"), $this);?>

            <br /><br />
            <center>
                <?php echo smarty_function_sb_button(array('text' => "Начать процесс импортирования банов",'icon' => "<i class='zmdi zmdi-account-add'></i>",'class' => "bgm-green btn-icon-text",'id' => 'iban','submit' => true), $this);?>

            </center>
            <br />
        </form>
    </div>
    <?php if (! $this->_tpl_vars['extreq']): ?>
    <script type="text/javascript">
        $('friendsname').disabled = true;
    </script>
    <?php endif; ?>
<?php endif; ?>