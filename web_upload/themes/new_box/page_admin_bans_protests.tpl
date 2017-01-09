{if $comment}
<!--Код Комментариев-->
<div class="row">
  <div class="card">
    <div class="card-header">
      <h2>{$commenttype} Комментарий</h2>
    </div>
    <div class="tv-comments">
      <ul class="tvc-lists">
        {foreach from="$othercomments" item="com"} 
            <a href="#" class="tvh-user pull-left">
              <img class="img-responsive" style="width: 46px;height: 46px;border-radius: 50%;" src="themes/new_box/img/profile-pics/1.jpg" alt="">
            </a>
            <div class="media-body">
            <strong class="d-block">{$com.comname}</strong>
            <small class="c-gray">{$com.added} {if $com.editname != ''}last edit {$com.edittime} by {$com.editname}{/if}</small>

            <div class="m-t-10">{$com.commenttxt}</div>

            </div>
          </li>
        {/foreach}
        <li class="p-20">
          <div class="fg-line">
            <textarea class="form-control auto-size" rows="5" placeholder="Ваш комментарий...." id="commenttext" name="commenttext">{$commenttext}</textarea>
            <div id="commenttext.msg" class="badentry"></div>
          </div>
          <input type="hidden" name="bid" id="bid" value="{$comment}">
          <input type="hidden" name="ctype" id="ctype" value="{$ctype}">
          {if $cid != ""}
            <input type="hidden" name="cid" id="cid" value="{$cid}">
          {else}
            <input type="hidden" name="cid" id="cid" value="-1">
          {/if}
          <input type="hidden" name="page" id="page" value="{$page}">
          {sb_button text="$commenttype Комментарий" onclick="ProcessComment();" class="m-t-15 btn-primary btn-sm" id="acom" submit=false}&nbsp;
          {sb_button text="Назад" onclick="history.go(-1)" class="m-t-15 btn btn-sm" id="aback"}
        </li>
      </ul>
    </div>
    </div>
</div>
<!--Код Комментариев-->
{else}
<div class="card">
  <div class="card-header">
    <h2>Протесты банов
      <small>
        Общее количество: {$protest_count}
      </small>
    </h2>
    
    <div class="actions" id="banlist-nav">
      {$protest_nav}
    </div>
  </div>
  
  <div class="alert alert-info" role="alert" id="bans_hidden" style="display:none;">Вам был выведен список банов, которые активны на данный момент.</div>
  <div class="alert" role="alert" id="tickswitchlink" style="display:none;"></div>
  
  <div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        {if $permission_protests}
          <th width="1%" title="Select All" name="tickswitch" id="tickswitch" onclick="TickSelectAll()" onmouseout="this.className=''" onmouseover="this.className='active'"></th>
        {/if}
        <th width="11%" class="text-center">Дата</th>
        <th class="text-center">Игрок</th>
        {if !$hideadminname}
          <th width="15%" class="text-center">Админ</th>
        {/if}
        <th width="20%" class="text-center">Срок</th>  
      </tr>
    </thead>
    <tbody>
      {foreach from="$protest_list" item="protest"}
        <tr class="opener" {if $ban.server_id != 0}onclick="xajax_ServerHostPlayers({$protest.server}, {$protest.pid});"{/if} style="cursor: pointer;">
          {if $permission_protests}
            <td>
              <label class="checkbox checkbox-inline m-r-20" for="chkb_{$smarty.foreach.banlist.index}" onclick="event.cancelBubble = true;">
                                <input type="checkbox" name="chkb_{$smarty.foreach.banlist.index}" id="chkb_{$smarty.foreach.banlist.index}" value="{$ban.ban_id}" hidden="hidden" />
                                <i class="input-helper"></i>
                            </label>
            </td>
          {/if}
          <td class="text-center">{$protest.date}</td>
          <td>
            <div style="float:left;">
              {if empty($protest.name)}
                <i>имя игрока не указано</i>
              {else}
                {$protest.name|escape:'html'|stripslashes}
              {/if}
            </div>
            {if $view_comments && $protest.commentdata != "Нет" && $protest.commentdata|@count > 0}
              <div style="float:right;padding-right: 5px;">
                {$protest.commentdata|@count} <img src="themes/new_box/img/comm.png" alt="Comments" title="Комментарии" style="height:14px;width:14px;" />
              </div>
            {/if}
          </td>
          {if !$hideadminname}
            <td class="text-center">
              {if !empty($protest.admin)}
                {$protest.admin|escape:'html'}
              {else}
                <i>Администратор снят</i>
              {/if}
            </td>
          {/if}
          <td class="{$ban.class}">{if $protest.ends == 'never'}
                        <i><font color="#677882">Никогда.</font></i>
                      {else}
                        {$protest.ends}
                      {/if}
            </td>
        </tr>
        <!-- ###############[ Start Sliding Panel ]################## -->
        <tr>
          <td colspan="7" style="padding: 0px;border-top: 0px solid #FFFFFF;">
            <div class="opener"> 
                <div class="card-header bgm-bluegray">
                  <h2>Информация о протесте:</h2>

                  <ul class="actions actions-alt">
                    <li class="dropdown">
                      <a href="#" data-toggle="dropdown" aria-expanded="false">
                        <i class="zmdi zmdi-more-vert"></i>
                      </a>

                      <ul class="dropdown-menu dropdown-menu-right">
                        {if $view_bans}
                          {if $ban.unbanned && $ban.reban_link != false}
                            <li>{$ban.reban_link}</li>
                          {/if}
                            <li>{$ban.blockcomm_link}</li>
                          {if $ban.demo_available}
                            <li>{$ban.demo_link}</li>
                          {/if}
                          <li>{$ban.addcomment}</li>
                          {if $ban.type == 0}
                            {if $groupban}
                              <li>{$ban.groups_link}</li>
                            {/if}
                            {if $friendsban}
                              <li>{$ban.friend_ban_link}</li>
                            {/if}
                          {/if}
                          {if ($ban.view_edit && !$ban.unbanned)} 
                            <li>{$ban.edit_link}</li>
                          {/if}
                          {if ($ban.unbanned == false && $ban.view_unban)}
                            <li>{$ban.unban_link}</li>
                          {/if}
                          {if $ban.view_delete}
                            <li>{$ban.delete_link}</li>
                          {/if}
                        {else}
                          <li>{$ban.demo_link}</li>
                        {/if}
                      </ul>
                    </li>
                  </ul>
                </div>
                <div class="card-body card-padding">
                  <div class="form-group col-sm-7" style="font-size: 14px;">
                    <div class="form-group col-sm-12 m-b-5">
                      <label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i>  Игрок</label>
                      <div class="col-sm-8">
                        {if empty($protest.name)}
                          <i>имя игрока не указано.</i>
                        {else}
                          {$protest.name|escape:'html'|stripslashes}
                        {/if}
                      </div>
                    </div>
                    <div class="form-group col-sm-12 m-b-5">
                      <label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Steam ID</label>
                      <div class="col-sm-8">
                        {if empty($protest.authid)}
                          <i>Steam ID игрока не указан.</i>
                        {else}
                          {$protest.authid}
                        {/if}
                      </div>
                    </div>
                    <div class="form-group col-sm-12 m-b-5">
                      <label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Steam3 ID</label>
                      <div class="col-sm-8">
                        {if empty($protest.steamid)}
                          <i>Steam3 ID игрока не указан.</i>
                        {else}
                          <a href="http://steamcommunity.com/profiles/{$ban.steamid3}" target="_blank">{$protest.steamid3}</a>
                        {/if}
                      </div>
                    </div>
                    <div class="form-group col-sm-12 m-b-5">
                      <label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Steam Community</label>
                      <div class="col-sm-8">
                        {if empty($protest.communityid)}
                          <i>Steam Community игрока не указан.</i>
                        {else}
                        <a href="http://steamcommunity.com/profiles/{$ban.communityid}" target="_blank">{$protest.communityid}</a>
                        {/if}
                      </div>
                    </div>  
                    <div class="form-group col-sm-12 m-b-5">
                      <label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> IP адрес</label>
                      {if $protest.ip != 'none'}
                        <div class="col-sm-8">
                          {$protest.ip}
                        </div>
                      {else}
                         <div class="col-sm-8">не предоставлен</div>
                      {/if}
                    </div>
                    <div class="form-group col-sm-12 m-b-5">
                      <label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Был выдан</label>
                      <div class="col-sm-8">
                        {$protest.date}
                      </div>
                    </div>
                    <div class="form-group col-sm-12 m-b-5">
                      <label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Причина бана</label>
                      <div class="col-sm-8">
                        {$protest.ban_reason}
                      </div>
                    </div>
                    <div class="form-group col-sm-12 m-b-5">
                      <label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Сервер</label>
                      <div class="col-sm-8">
                        {$protest.server}
                      </div>
                    </div>
                    <div class="form-group col-sm-12 m-b-5">
                      <label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Будет снят</label>
                      <div class="col-sm-8">
                        {$protest.ends}
                      </div>
                    </div>
                    <div class="form-group col-sm-12 m-b-5">
                      <label class="col-sm-4 control-label"><i class="zmdi zmdi-circle-o text-left"></i> Сообщение</label>
                      <div class="col-sm-8">
                        {$protest.reason}
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-sm-5">  
                    <div class="wall-comment-list">
                        <div class="pmo-block pmo-contact" style="font-size: 14px;">
                          <div class="lv-header c-bluegray p-b-5 p-t-0" style="border-bottom: 2px solid #607d8b;">Блокировку Выдал:</div>
                          <ul>
                            <li class="p-b-5">
                              <i class="zmdi zmdi-star c-red f-20"></i> 
                                {if !($protest.admin)}
                                  <b>{$protest.admin|escape:'html'}</b>
                                {else}
                                  <b>Администратор был удален</b>
                                {/if}
                            </li>
                            {if $protest.admin != "CONSOLE"}
                              {if !empty($protest.admin)}
                                {if $admininfos}
                                  <li class="p-b-5"><i class="zmdi zmdi-steam"></i> {if !empty($protest.admin_authid)}{$protest.admin_authid} (<a href="http://steamcommunity.com/profiles/{$ban.admin_authid_link}" target="_blank">Профиль</a>){else}Нет данных...{/if}</li>
                                  <li class="p-b-5"><i class="zmdi zmdi-vk"></i> {if !empty($protest.admin_vk)}<a href="https://vk.com/{$ban.admin_vk}" target="_blank">Линк</a>{else}Нет данных...{/if}</li>
                                  <li class="p-b-5"><i class="zmdi zmdi-account-box-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="Skype"></i> {if !empty($ban.admin_skype)}{$protest.admin_skype}{else}Нет данных...{/if}</li>
                                  <li class="p-b-5">
                                    <i class="zmdi zmdi-info-outline" data-toggle="tooltip" data-placement="top" title="" data-original-title="Характеристика"></i>
                                    <address class="m-b-0 ng-binding">
                                      {if !empty($protest.admin_comm)}
                                        {$protest.admin_comm}
                                      {else}
                                        Нет данных. Обычный рядовой, контролирует порядок на серверах.
                                      {/if}
                                    </address>
                                  </li>
                                {/if}
                              {/if}
                            {/if}
                          </ul>
                        </div>
                      </div>
                    <!-- COMMENT CODik-->
                      <hr class="m-t-10 m-b-10" />
                      <div class="wall-comment-list">
                        {if $protest.commentdata != "None"}
                          <div class="wcl-list">
                            {foreach from=$protest.commentdata item=commenta}
                              <div class="media">
                                <a href="#" class="pull-left">
                                  <img src="themes/new_box/img/profile-pics/4.jpg" alt="" class="lv-img-sm">
                                </a>
                     
                                <div class="media-body">
                                  <a href="#" class="a-title">{if !empty($commenta.comname)}{$commenta.comname|escape:'html'}{else}<i>Админ удален</i>{/if}</a> {if $commenta.edittime != "none"}<small class="c-gray m-l-10">редактировал {if $commenta.editname != "none"}{$commenta.editname}{else}<i>Админ удален</i>{/if} в {$commenta.edittime}</small>{/if}
                                  <p class="m-t-5 m-b-0" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">{$commenta.commenttxt}</p>
                                </div>
                                
                                  <ul class="actions" style="right: -1px;">
                                    <li class="dropdown">
                                      <a href="#" data-toggle="dropdown" aria-expanded="false">
                                        <i class="zmdi zmdi-more-vert"></i>
                                      </a>

                                      <ul class="dropdown-menu dropdown-menu-right">
                                        {if $commenta.editcomlink != "none"}<li>{$commenta.editcomlink}</li>{/if}
                                        {if $commenta.delcomlink != "none"}<li>{$commenta.delcomlink}</li>{/if}
                                      </ul>
                                    </li>
                                  </ul>
                              </div>
                            {/foreach}
                          </div>
                        <!-- Comment form -->
                          <div class="wcl-form m-t-15">
                            <div class="wc-comment">
                              <a href="{$protest.addcomment_link}">
                                <div class="wcc-inner">
                                  Добавить комментарий...
                                </div>
                              </a>
                            </div>
                          </div>
                      </div>
                    {/if}
                  <!-- COMMENT CODik-->
                    </div>
                </div>
            </div>
          </td>
        </tr>
      {/foreach}
    </tbody>
  </table>
  </div>
    <div class="card-body card-padding">
      <div class="col-sm-12 p-l-0">
        <div class="col-sm-2 p-0">
          <select class="selectpicker " name="bulk_action" id="bulk_action" onchange="BulkEdit(this,'{$admin_postkey}');">
            <option value="-1">Выберите</option>
            {if $general_unban}
            <option value="U">Контакты</option>
            {/if}
            {if $can_delete}
            <option value="D">Удалить</option>
            {/if}
          </select>
        </div>
        <div class="col-sm-3 p-r-0 text-right" style="float:right;">
          <button class="btn bgm-bluegray waves-effect" onclick="window.location.href='index.php?p=admin&c=bans#^0~p'">Архив протестов</button>
        </div>
      </div>
    </div>&nbsp;
</div>

{literal}
<script type="text/javascript">window.addEvent('domready', function(){  
InitAccordion('tr.opener', 'div.opener', 'content');
{/literal}
{if $view_bans}
$('tickswitch').value=0;
{/if}
{literal}
}); 
</script>
{/literal}
{/if}
