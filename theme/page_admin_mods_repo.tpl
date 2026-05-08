{display_header title="Репозиторий МОДов" text="Список всех МОДов, доступных в репозитории SourceBans"}
<div class="card-body card-padding">
    <table class="table table-striped">
        <tbody>
            <tr>
                <th width="5%"  class="text-center">Иконка</th>
                <th width="70%" class="text-left">Имя</th>
                <th class="text-right">Статус</th>
            </tr>

            {foreach $modlist as $mod}
                <tr id="{$mod.folder|escape:'htmlall'}">
                    <td class="text-center">
                        <img src="{$mirror|escape:'htmlall'}{$mirror_iconsdir|escape:'htmlall'}{$mod.icon|escape:'htmlall'}" alt="{$mod.name|escape:'htmlall'}">
                    </td>

                    <td class="text-left">
                        {$mod.name|escape:'htmlall'}
                    </td>

                    <td class="text-right">
                        {if $mod.installed}
                            <strong>Установлен</strong>
                        {else}
                            <strong>
                                <a href="#" onClick="xajax_InstallMOD('{$mod.folder|escape:'javascript'}'); return false;">Установить</a>
                            </strong>
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>
