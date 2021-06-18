{if NOT $permission_addadmin}
    Доступ запрещен!
{else}
    {if $overrides_error != ""}
        <script type="text/javascript">ShowBox("Ошибка", "{$overrides_error}", "red");</script>
    {/if}
    {if $overrides_save_success}
        <script type="text/javascript">ShowBox("Переопределения обновлены", "Изменения успешно сохранены.", "green", "index.php?p=admin&c=admins");</script>
    {/if}
    <div id="add-group">
        <form action="" method="post">
        {display_header title="Переопределения" text="С переопределениями вы можете изменить флаги или разрешения для какой-либо команды, либо глобально, либо для конкретной группы, без редактирования исходного кода плагина."}
        <div class="card-body card-padding">
            <p>Прочитать о переопределениях можно <a href="https://wiki.alliedmods.net/Ru:Overriding_Command_Access_(SourceMod)" title="Переопределения (SourceMod)" target="_blank">на AlliedModders Wiki</a>.<br />
            Чтобы удалить переопределение, просто оставьте поле пустым.</p>
            <table align="center" cellspacing="0" cellpadding="4" id="overrides" width="90%" class="table table-striped">
                <tr>
                    <th class="text-left">Тип</td>
                    <th class="text-center">Название</td>
                    <th class="text-right">Флаги</td>
                </tr>
                {foreach from=$overrides_list item=override}
                <tr>
                    <td class="text-left">
                        <select name="override_type[]">
                            <option{if $override.type == "command"} selected="selected"{/if} value="command">Команда</option>
                            <option{if $override.type == "group"} selected="selected"{/if} value="group">Группа</option>
                        </select>
                        <input type="hidden" name="override_id[]" value="{$override.id}" />
                    </td>
                    <td class="text-center"><input name="override_name[]" value="{$override.name|htmlspecialchars}" /></td>
                    <td class="text-right"><input name="override_flags[]" value="{$override.flags|htmlspecialchars}" /></td>
                </tr>
                {/foreach}
                <tr>
                    <td class="text-left">
                        <select class="select" name="new_override_type">
                            <option value="command">Команда</option>
                            <option value="group">Группа</option>
                        </select>
                    </td>
                    <td class="text-center"><input class="textbox" name="new_override_name" /></td>
                    <td class="text-right"><input class="textbox" name="new_override_flags" /></td>
                </tr>
            </table>
        </div>
        <br />
        <center>
            {sb_button text="Сохранить" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-green btn-icon-text" id="oversave" submit=true}
            &nbsp;
            {sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="oback"}
        </center>
        <br />
        </form>
    </div>
{/if}
