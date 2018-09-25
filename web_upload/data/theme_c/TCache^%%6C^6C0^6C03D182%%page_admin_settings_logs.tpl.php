<?php /* Smarty version 2.6.29, created on 2018-09-24 17:56:35
         compiled from page_admin_settings_logs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'page_admin_settings_logs.tpl', 36, false),array('block', 'textformat', 'page_admin_settings_logs.tpl', 44, false),)), $this); ?>
<div class="card-header">
    <h2 align="left">Системный лог <?php echo $this->_tpl_vars['clear_logs']; ?>
 <small>Щёлкните курсором мыши по нужному событию, дабы раскрыть больше подробностей о нём.</small></h2>
</div>
<div class="card-body">
<?php  require (TEMPLATES_PATH . "/admin.log.search.php"); ?>
</div>
<div class="card-body card-padding">
<div id="banlist-nav"><?php echo $this->_tpl_vars['page_numbers']; ?>
</div>
</div>
<div class="card-body">

    <table width="100%" cellspacing="0" cellpadding="0" align="center" class="table table-striped table-vmiddle">
        <tr>
            <td width="5%" height="16" class="listtable_top" align="center"><b>Тип</b></td>
            <td width="28%" height="16" class="listtable_top" align="center"><b>Событие</b></td>
            <td width="28%" height="16" class="listtable_top" align="center"><b>Пользователь</b></td>
            <td width="" height="16" class="listtable_top"><b>Дата/Время</b></td>
        </tr>

<?php $_from = ($this->_tpl_vars['log_items']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['log']):
?>
        <tr class="opener" onmouseout="this.className='tbl_out'" onmouseover="this.className='tbl_hover'" style="cursor: pointer;">
            <td height="16" align="center" class="listtable_1"><?php echo $this->_tpl_vars['log']['type_img']; ?>
</td>
            <td height="16" class="listtable_1"><?php echo $this->_tpl_vars['log']['title']; ?>
</td>
            <td height="16" class="listtable_1"><?php echo $this->_tpl_vars['log']['user']; ?>
</td>
            <td height="16" class="listtable_1"><?php echo $this->_tpl_vars['log']['date_str']; ?>
</td>
        </tr>
        <tr>
            <td colspan="4" align="center" style="background-color: #f4f4f4;padding: 0px;border-top: 0px solid #FFFFFF;">
                <div class="opener" style="visibility: hidden; zoom: 1; opacity: 0;">
                    <table width="100%" cellspacing="0" cellpadding="0" class="table table-striped table-vmiddle">
                        <tr>
                            <td height="16" align="center" class="listtable_top" colspan="3"><strong>Детали события</strong></td>
                        </tr>
                        <tr align="left">
                            <td width="20%" height="16" class="listtable_1">Детали</td>
                            <td height="16" class="listtable_1"><?php echo ((is_array($_tmp=$this->_tpl_vars['log']['message'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
                        </tr>
                        <tr align="left">
                            <td width="20%" height="16" class="listtable_1">Родительская функция</td>
                            <td height="16" class="listtable_1"><?php echo $this->_tpl_vars['log']['function']; ?>
</td>
                        </tr>
                        <tr align="left">
                            <td width="20%" height="16" class="listtable_1">Строка запроса</td>
                            <td height="16" class="listtable_1"><?php $this->_tag_stack[] = array('textformat', array('wrap' => 62,'wrap_cut' => true)); $_block_repeat=true;smarty_block_textformat($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['log']['query']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_textformat($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
                        </tr>
                        <tr align="left">
                            <td width="20%" height="16" class="listtable_1">IP</td>
                            <td height="16" class="listtable_1"><?php echo $this->_tpl_vars['log']['host']; ?>
</td>
                       </tr>
                    </table>
                </div>
            </td>
        </tr>
<?php endforeach; endif; unset($_from); ?>
    </table>
</div>
<script type="text/javascript">
	InitAccordion('tr.opener', 'div.opener', 'content');
</script>