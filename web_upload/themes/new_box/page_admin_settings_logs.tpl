<div class="card-header">
    <h2 align="left">Системный лог {$clear_logs} <small>Щёлкните курсором мыши по нужному событию, дабы раскрыть больше подробностей о нём.</small></h2>
</div>
<div class="card-body">
{php} require (TEMPLATES_PATH . "/admin.log.search.php");{/php}
</div>
<div class="card-body card-padding">
<div id="banlist-nav">{$page_numbers}</div>
</div>
<div class="card-body">

    <table width="100%" cellspacing="0" cellpadding="0" align="center" class="table table-striped table-vmiddle">
        <tr>
            <td width="5%" height="16" class="listtable_top" align="center"><b>Тип</b></td>
            <td width="28%" height="16" class="listtable_top" align="center"><b>Событие</b></td>
            <td width="28%" height="16" class="listtable_top" align="center"><b>Пользователь</b></td>
            <td width="" height="16" class="listtable_top"><b>Дата/Время</b></td>
        </tr>

{foreach from="$log_items" item="log"}
        <tr class="opener" onmouseout="this.className='tbl_out'" onmouseover="this.className='tbl_hover'" style="cursor: pointer;">
            <td height="16" align="center" class="listtable_1">{$log.type_img}</td>
            <td height="16" class="listtable_1">{$log.title}</td>
            <td height="16" class="listtable_1">{$log.user}</td>
            <td height="16" class="listtable_1">{$log.date_str}</td>
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
                            <td height="16" class="listtable_1">{$log.message|escape}</td>
                        </tr>
                        <tr align="left">
                            <td width="20%" height="16" class="listtable_1">Родительская функция</td>
                            <td height="16" class="listtable_1">{$log.function}</td>
                        </tr>
                        <tr align="left">
                            <td width="20%" height="16" class="listtable_1">Строка запроса</td>
                            <td height="16" class="listtable_1">{textformat wrap=62 wrap_cut=true}{$log.query}{/textformat}</td>
                        </tr>
                        <tr align="left">
                            <td width="20%" height="16" class="listtable_1">IP</td>
                            <td height="16" class="listtable_1">{$log.host}</td>
                       </tr>
                    </table>
                </div>
            </td>
        </tr>
{/foreach}
    </table>
</div>
<script type="text/javascript">
	InitAccordion('tr.opener', 'div.opener', 'content');
</script>
