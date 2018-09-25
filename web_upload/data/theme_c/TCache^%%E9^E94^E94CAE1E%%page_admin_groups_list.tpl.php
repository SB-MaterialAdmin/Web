<?php /* Smarty version 2.6.29, created on 2018-09-21 18:07:03
         compiled from page_admin_groups_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'display_header', 'page_admin_groups_list.tpl', 5, false),array('modifier', 'count', 'page_admin_groups_list.tpl', 146, false),array('modifier', 'htmlspecialchars', 'page_admin_groups_list.tpl', 148, false),)), $this); ?>
<?php if (! $this->_tpl_vars['permission_listgroups']): ?>
    Доступ запрещен!
<?php else: ?>
    <!-- Web Admin Groups -->
    <?php echo materialdesign_cardheader(array('title' => "Группы",'text' => "Кликните на группе, чтобы просмотреть разрешения"), $this);?>

    <div class="card-body card-padding">
        <?php echo materialdesign_cardheader(array('title' => "Группы ВЕБ админов",'text' => "Всего: ".($this->_tpl_vars['web_group_count'])), $this);?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="35%" class="text-left">Имя группы</th>
                    <th width="20%" class="text-center">Администраторов в группе</th>
                    <th width="20%" class="text-right">Действия</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $_from = $this->_tpl_vars['web_group_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['web_group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['web_group']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['group']):
        $this->_foreach['web_group']['iteration']++;
?>
                <tr id="gid_<?php echo $this->_tpl_vars['group']['gid']; ?>
" onmouseout="this.className='opener'" onmouseover="this.className='info opener'" class="opener" style="cursor:">
                    <td class="text-left"><?php echo $this->_tpl_vars['group']['name']; ?>
</td>
                    <td class="text-center"><?php echo $this->_tpl_vars['web_admins'][($this->_foreach['web_group']['iteration']-1)]; ?>
</td>
                    <td class="text-right">
                        <?php if ($this->_tpl_vars['permission_editgroup']): ?><a href="index.php?p=admin&c=groups&o=edit&type=web&id=<?php echo $this->_tpl_vars['group']['gid']; ?>
">Редактировать</a><?php if ($this->_tpl_vars['permission_deletegroup']): ?> / <?php endif; ?><?php endif; ?>
                        <?php if ($this->_tpl_vars['permission_deletegroup']): ?><a href="#" onclick="RemoveGroup(<?php echo $this->_tpl_vars['group']['gid']; ?>
, '<?php echo $this->_tpl_vars['group']['name']; ?>
', 'web');">Удалить</a><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" align="center" style="padding-bottom: 0px; padding-left: 0px; padding-right: 0px; padding-top: 0px;">
                        <div class="opener">
                            <table class="table">
                                <tr>
                                    <td height="16" align="left" class="listtable_top" colspan="2">
                                        <b>Детали группы</b>            
                                    </td>
                                </tr>
                                <tr>
                                    <td height="16" class="listtable_1"><?php echo $this->_tpl_vars['group']['permissions']; ?>
</td>
                                    <td height="16" class="listtable_1">
                                        <p class="c-blue">Кто в группе</p>
                                        <ul class="clist clist-star">
                                            <?php $_from = $this->_tpl_vars['web_admins_list'][($this->_foreach['web_group']['iteration']-1)]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['web_admin']):
?>
                                            <li>
                                                <?php if ($this->_tpl_vars['permission_editadmin']): ?><a href="#admin_w<?php echo $this->_tpl_vars['web_admin']['aid']; ?>
" data-toggle="modal"><?php endif; ?>
                                                    <?php echo $this->_tpl_vars['web_admin']['user']; ?>

                                                <?php if ($this->_tpl_vars['permission_editadmin']): ?></a><?php endif; ?>
                                            </li>
                                            
                                            <?php if ($this->_tpl_vars['permission_editadmin']): ?>
                                            <!-- Модальное окошко с действиями над админом <?php echo $this->_tpl_vars['web_admin']['user']; ?>
 -->
                                            <div class="modal fade" id="admin_w<?php echo $this->_tpl_vars['web_admin']['aid']; ?>
" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class='modal-dialog modal-sm'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h4 class='modal-title'><?php echo $this->_tpl_vars['web_admin']['user']; ?>
</h4>
                                                        </div>
                                                        <div class='modal-body'>
                                                            <p class="m-b-10"><button class="btn btn-link btn-block" data-dismiss="modal" onClick='location.href="index.php?p=admin&c=admins&o=editgroup&id=<?php echo $this->_tpl_vars['web_admin']['aid']; ?>
";'>Редактировать группы</button></p>
                                                            <p class="m-b-10"><button class="btn btn-link btn-block" href="#" data-dismiss='modal' onClick='location.href="index.php?p=admin&c=admins&o=editgroup&id=<?php echo $this->_tpl_vars['web_admin']['aid']; ?>
&wg=";'>Исключить админа из группы</button></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php endforeach; endif; unset($_from); ?>
                                        </ul>
                                    </td>
                            </tr>
                        </table>        
                    </div>
                </td>     
            </tr>        
            <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <br/>
    
    <!-- Server Admin Groups -->
    <?php echo materialdesign_cardheader(array('title' => "Серверные группы админов",'text' => "Всего: ".($this->_tpl_vars['server_admin_group_count'])), $this);?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="35%" class="text-left">Имя группы</th>
                <th width="20%" class="text-center">Администраторов в группе</th>
                <th width="20%" class="text-right">Действия</th>
            </tr>
        </thead>
        <tbody>
        <?php $_from = ($this->_tpl_vars['server_group_list']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['server_admin_group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['server_admin_group']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['group']):
        $this->_foreach['server_admin_group']['iteration']++;
?>
            <tr id="gid_<?php echo $this->_tpl_vars['group']['id']; ?>
" onmouseout="this.className='opener'" onmouseover="this.className='info opener'" class="opener" style="cursor: pointer;">
                <td class="text-left" height='16'><?php echo $this->_tpl_vars['group']['name']; ?>
</td>
                <td class="text-center" height='16'><?php echo $this->_tpl_vars['server_admins'][($this->_foreach['server_admin_group']['iteration']-1)]; ?>
</td>
                <td class="text-right" height='16'> 
                    <?php if ($this->_tpl_vars['permission_editgroup']): ?>
                        <a href="index.php?p=admin&c=groups&o=edit&type=srv&id=<?php echo $this->_tpl_vars['group']['id']; ?>
">Редактировать</a>
                        <?php if ($this->_tpl_vars['permission_deletegroup']): ?>
                            &nbsp;/&nbsp;
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['permission_deletegroup']): ?>
                        <a href="#" onclick="RemoveGroup(<?php echo $this->_tpl_vars['group']['id']; ?>
, '<?php echo $this->_tpl_vars['group']['name']; ?>
', 'srv');">Удалить</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td colspan="7" align="center" style="padding-bottom: 0px; padding-left: 0px; padding-right: 0px; padding-top: 0px;">
                    <div class="opener">
                        <table class="table">
                            <tr>
                                <td height="16" align="left" class="listtable_top" colspan="2">
                                    <b>Детали группы</b>            
                                </td>
                            </tr>
                            <tr>
                                <td height="16" class="listtable_1"><?php echo $this->_tpl_vars['group']['permissions']; ?>
</td>
                                <td height="16" class="listtable_1">
                                    <p class="c-blue">Кто в группе</p>
                                    <ul class="clist clist-star">
                                        <?php $_from = $this->_tpl_vars['server_admins_list'][($this->_foreach['server_admin_group']['iteration']-1)]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['server_admin']):
?>
                                        <li>
                                            <?php if ($this->_tpl_vars['permission_editadmin']): ?><a href="#admin_s<?php echo $this->_tpl_vars['server_admin']['aid']; ?>
" data-toggle="modal"><?php endif; ?>
                                                <?php echo $this->_tpl_vars['server_admin']['user']; ?>

                                            <?php if ($this->_tpl_vars['permission_editadmin']): ?></a><?php endif; ?>
                                        </li>
                                        
                                        <?php if ($this->_tpl_vars['permission_editadmin']): ?>
                                        <!-- Модальное окошко с действиями над админом <?php echo $this->_tpl_vars['server_admin']['user']; ?>
 -->
                                        <div class="modal fade" id="admin_s<?php echo $this->_tpl_vars['server_admin']['aid']; ?>
" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class='modal-dialog modal-sm'>
                                                <div class='modal-content'>
                                                    <div class='modal-header'>
                                                        <h4 class='modal-title'><?php echo $this->_tpl_vars['server_admin']['user']; ?>
</h4>
                                                    </div>
                                                    <div class='modal-body'>
                                                        <p class="m-b-10"><button class="btn btn-link btn-block" data-dismiss="modal" onClick='location.href="index.php?p=admin&c=admins&o=editgroup&id=<?php echo $this->_tpl_vars['server_admin']['aid']; ?>
";'>Редактировать группы</button></p>
                                                        <p class="m-b-10"><button class="btn btn-link btn-block" href="#" data-dismiss='modal' onClick='location.href="index.php?p=admin&c=admins&o=editgroup&id=<?php echo $this->_tpl_vars['server_admin']['aid']; ?>
&sg=";'>Исключить админа из группы</button></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </ul>
                                </td>
                                <td>
                                    <p class="c-blue">Переназначения</p>
                                    <ul class="clist clist-star">
                                        <?php if (count($this->_tpl_vars['server_overrides_list'][($this->_foreach['server_admin_group']['iteration']-1)]) > 0): ?>
                                            <?php $_from = $this->_tpl_vars['server_overrides_list'][($this->_foreach['server_admin_group']['iteration']-1)]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['override']):
?>
                                        <li><b><?php if ($this->_tpl_vars['override']['access'] == 'allow'): ?>Разрешён<?php else: ?>Запрещён<?php endif; ?></b> доступ к <?php if ($this->_tpl_vars['override']['type'] == 'command'): ?>команде<?php else: ?>группе команд<?php endif; ?> <b><?php echo ((is_array($_tmp=$this->_tpl_vars['override']['name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</b></li>
                                            <?php endforeach; endif; unset($_from); ?>
                                        <?php else: ?>
                                        <li>Переназначений <b>нет</b>.</li>
                                        <?php endif; ?>
                                    </ul>
                                </td>
                           </tr>
                    </table>        
                 </div>
            </td>     
        </tr>        
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <br/>


    <!-- Server Groups -->
    <?php echo materialdesign_cardheader(array('title' => "Группы серверов",'text' => "Всего: ".($this->_tpl_vars['server_group_count'])), $this);?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="35%" class="text-left">Имя группы</th>
                <th width="20%" class="text-center">Серверов в группе</th>
                <th width="20%" class="text-right">Действия</th>
            </tr>
        </thead>
        <tbody>
        <?php $_from = ($this->_tpl_vars['server_list']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['servers_group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['servers_group']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['group']):
        $this->_foreach['servers_group']['iteration']++;
?>
            <tr id="gid_<?php echo $this->_tpl_vars['group']['gid']; ?>
" onmouseout="this.className='opener'" onmouseover="this.className='info opener'" class="opener" style="cursor: pointer;">
                <td class="text-left" height='16'><?php echo $this->_tpl_vars['group']['name']; ?>
</td>
                <td class="text-center" height='16'><?php echo count($this->_tpl_vars['server_list'][($this->_foreach['servers_group']['iteration']-1)]['servers']); ?>
</td>
                <td class="text-right" height='16'>   
                    <?php if ($this->_tpl_vars['permission_editgroup']): ?>
                    <a href="index.php?p=admin&c=groups&o=edit&type=server&id=<?php echo $this->_tpl_vars['group']['gid']; ?>
">Редактировать</a>
                    <?php if ($this->_tpl_vars['permission_deletegroup']): ?>
                    &nbsp;/&nbsp;
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($this->_tpl_vars['permission_deletegroup']): ?>
                    <a href="#" onclick="RemoveGroup(<?php echo $this->_tpl_vars['group']['gid']; ?>
, '<?php echo $this->_tpl_vars['group']['name']; ?>
', 'server');">Удалить</a>
                <?php endif; ?>        
                </td>
            </tr>
            <tr>
                <td colspan="7" align="left" style="padding-bottom: 0px; padding-left: 0px; padding-right: 0px; padding-top: 0px;">         
                    <div class="opener">
                        <div style="padding-left: 30px; padding-top: 15px; padding-bottom: 15px;">
                            <p class="c-blue">Сервера в группе</p>
                            <ul class="clist clist-star">
                                <?php if (count($this->_tpl_vars['server_list'][($this->_foreach['servers_group']['iteration']-1)]['servers']) > 0): ?>
                                <?php $_from = $this->_tpl_vars['server_list'][($this->_foreach['servers_group']['iteration']-1)]['servers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['server']):
?>
                                    <li id="servername_<?php echo $this->_tpl_vars['server'][0]; ?>
">Пожалуйста, подождите, идёт загрузка имени сервера...</li>
                                    <script type="text/javascript">
                                        xajax_ServerHostProperty(<?php echo $this->_tpl_vars['server'][0]; ?>
, "servername_<?php echo $this->_tpl_vars['server'][0]; ?>
", "innerHTML", 100);
                                    </script>
                                <?php endforeach; endif; unset($_from); ?>
                                <?php else: ?>
                                    <li>Серверов в группе <b>нет.</b></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                     </div>
                 </td>     
              </tr> 
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
        </table>
    </div>

    <script type="text/javascript">InitAccordion('tr.opener', 'div.opener', 'content');</script>