<?php /* Smarty version 2.6.29, created on 2018-09-18 17:11:29
         compiled from page_admin_overrides.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'display_header', 'page_admin_overrides.tpl', 12, false),array('function', 'sb_button', 'page_admin_overrides.tpl', 49, false),array('modifier', 'htmlspecialchars', 'page_admin_overrides.tpl', 31, false),)), $this); ?>
<?php if (! $this->_tpl_vars['permission_addadmin']): ?>
    Доступ запрещен!
<?php else: ?>
    <?php if ($this->_tpl_vars['overrides_error'] != ""): ?>
        <script type="text/javascript">ShowBox("Ошибка", "<?php echo $this->_tpl_vars['overrides_error']; ?>
", "red");</script>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['overrides_save_success']): ?>
        <script type="text/javascript">ShowBox("Переопределения обновлены", "Изменения успешно сохранены.", "green", "index.php?p=admin&c=admins");</script>
    <?php endif; ?>
    <div id="add-group">
        <form action="" method="post">
        <?php echo materialdesign_cardheader(array('title' => "Переопределения",'text' => "С переопределениями вы можете изменить флаги или разрешения для какой-либо команды, либо глобально, либо для конкретной группы, без редактирования исходного кода плагина."), $this);?>

        <div class="card-body card-padding">
            <p>Прочитать о переопределениях можно <a href="https://wiki.alliedmods.net/Ru:Overriding_Command_Access_(SourceMod)" title="Переопределения (SourceMod)" target="_blank">на AlliedModders Wiki</a>.<br />
            Чтобы удалить переопределение, просто оставьте поле пустым.</p>
            <table align="center" cellspacing="0" cellpadding="4" id="overrides" width="90%" class="table table-striped">
                <tr>
                    <th class="text-left">Тип</td>
                    <th class="text-center">Название</td>
                    <th class="text-right">Флаги</td>
                </tr>
                <?php $_from = $this->_tpl_vars['overrides_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['override']):
?>
                <tr>
                    <td class="text-left">
                        <select name="override_type[]">
                            <option<?php if ($this->_tpl_vars['override']['type'] == 'command'): ?> selected="selected"<?php endif; ?> value="command">Команда</option>
                            <option<?php if ($this->_tpl_vars['override']['type'] == 'group'): ?> selected="selected"<?php endif; ?> value="group">Группа</option>
                        </select>
                        <input type="hidden" name="override_id[]" value="<?php echo $this->_tpl_vars['override']['id']; ?>
" />
                    </td>
                    <td class="text-center"><input name="override_name[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['override']['name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
" /></td>
                    <td class="text-right"><input name="override_flags[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['override']['flags'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
" /></td>
                </tr>
                <?php endforeach; endif; unset($_from); ?>
                <tr>
                    <td class="text-left">
                        <select class="selectpicker" name="new_override_type">
                            <option value="command">Команда</option>
                            <option value="group">Группа</option>
                        </select>
                    </td>
                    <td class="text-center"><input class="form-control" name="new_override_name" placeholder="Введите данные(обязательное поле)" /></td>
                    <td class="text-right"><input class="form-control" name="new_override_flags" placeholder="Введите данные(обязательное поле)" /></td>
                </tr>
            </table>
        </div>
        <br />
        <center>
            <?php echo smarty_function_sb_button(array('text' => "Сохранить",'icon' => "<i class='zmdi zmdi-check-all'></i>",'class' => "bgm-green btn-icon-text",'id' => 'oversave','submit' => true), $this);?>

            &nbsp;
            <?php echo smarty_function_sb_button(array('text' => "Назад",'onclick' => "history.go(-1)",'icon' => "<i class='zmdi zmdi-undo'></i>",'class' => "bgm-red btn-icon-text",'id' => 'oback'), $this);?>

        </center>
        <br />
        </form>
    </div>
<?php endif; ?>