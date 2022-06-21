{display_header title="Репозиторий МОДов" text="Список всех МОДов, доступных в репозитории SourceBans"}
<div class="card-body card-padding">
    <table class="table table-striped">
        <tbody>
            <tr>
                <th width="5%"  class="text-center">Иконка</th>
                <th width="70%" class="text-left">Имя</th>
                <th class="text-right">Статус</th>
            </tr>
            {foreach from="$modlist" item="mod"}
            <tr id="{$mod.folder}">
                <td class="text-center"><img src="{$mirror}{$mirror_iconsdir}{$mod.icon}"></td>
                <td class="text-left">{$mod.name|htmlspecialchars}</td>
                <td class="text-right">{if $mod.installed}<strong>Установлен</strong>{else}<strong><a href="#" onClick="xajax_InstallMOD('{$mod.folder}'); return false;">Установить</a></strong>{/if}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
