<!--<div class="sea_open">
    <blockquote class="m-b-5 text-center bgm-orange c-white f-17" style="border-left: 30px solid #ffffff;border-right: 30px solid #ffffff;">
        <p>Поиск записей в логах</p>
    </blockquote>
</div>
-->
<div class="panel">
    <table width="100%" class="table">
         <thead>
             <tr>
                 <th width="5%">#</th>
                 <th width="25%">Критерий</th>
                 <th width="70%">Ввод</th>
             </tr>
         </thead>
         <tbody>
             <tr>
                 <td class="p-b-5">
                     <div class="p-t-5">
                         <label class="radio radio-inline m-r-20" for="admin_">
                             <input id="admin_" name="search_type" type="radio" value="admin" hidden="hidden" />
                             <i class="input-helper"></i>
                         </label>
                     </div>
                 </td>
                 <td class="p-b-5">
                     <div class="p-t-5"><label for="admin" onclick="$('admin_').checked = true">Администратор</label></div>
                 </td>
                 <td class="p-b-5">
                     <div class="col-sm-6 p-t-5 p-r-0">
                         <select class="form-control" id="admin" onchange="$('admin_').checked = true">
{foreach from="$admin_list" item="admin}
                             <option label="{$admin.user}" value="{$admin.aid}">{$admin.user}</option>
{/foreach}
                         </select>
                     </div>
                 </td>
             </tr>
             <tr>
                 <td class="p-b-5">
                     <div class="p-t-5">
                         <label class="radio radio-inline m-r-20" for="message_">
                             <input id="message_" name="search_type" type="radio" value="message" hidden="hidden" />
                             <i class="input-helper"></i>
                         </label>
                     </div>
                 </td>
                 <td class="p-b-5">
                     <div class="p-t-5"><label for="message" onchange="$('message_').checked = true">Текст сообщения</label></div>
                 </td>
                 <td class="p-b-5">
                     <div class="fg-line">
                         <input type="text" class="form-control" id="message" value="" onmouseup="$('message_')checked = true" placeholder="Сообщение" />
                     </div>
                 </td>
             </tr>
             <tr>
                 <td class="p-b-5">
                     <div class="p-t-5">
                         <label class="radio radio-inline m-r-20" for="type_">
                             <input id="type_" name="search_type" type="radio" value="type" hidden="hidden" />
                             <i class="input-helper"></i>
                         </label>
                     </div>
                 </td>
                 <td class="p-b-5">
                     <div class="p-t-5"><label for="type" onchange="$('type_').checked = true">Тип сообщения</label></div>
                 </td>
                 <td class="p-b-5">
                     <div class="col-sm-6 p-t-5 p-r-0">
                         <select class="form-control" id="type" onchange="$('type_').checked = true">
                             <option label="Сообщение" value="m" selected>Сообщение</option>
                             <option label="Предупреждение" value="w" selected>Предупреждение</option>
                             <option label="Ошибка" value="e" selected>Ошибка</option>
                         </select>
                     </div>
                 </td>
             </tr>
             <tr>
                 <td colspan="4">{sb_button text="Поиск" onclick="search_log();" class="ok" id="searchbtn" submit=false}</td>
             </tr>
         </tbody>
    </table>
</div>


<!--<script>InitAccordion('div.sea_open', 'div.panel', 'content');</script>-->
