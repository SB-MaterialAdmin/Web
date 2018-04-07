{* Шаблон вывода информации о наличии или отсутствии бана по IP-адресу *}
<div class="alert alert-{if $IsBanned > 0}danger{else}success{/if}" role="alert">
  <h4>Быстрый поиск бана</h4>
  <span class="p-l-10"><b>Ваш IP-адрес - {$UserIP}</b>.&nbsp;
  {if $IsBanned > 0}
    У Вас имеются активные баны, доступ на сервера Вам запрещён.
  {else}
    Всё хорошо, сегодня без нарушений.
  {/if}
  </span>
</div>