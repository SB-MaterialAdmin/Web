{* Шаблон вывода информации о наличии или отсутствии бана по IP-адресу *}
<div class="alert alert-{if $IsBanned > 0}danger{else}success{/if}" role="alert">
  <h4>Быстрый поиск бана</h4>
  <span class="p-l-10"><b>Ваш IP-адрес - {$UserIP}</b>.&nbsp;
  {if $IsBanned === NULL}
    Всё хорошо, сегодня без нарушений.
  {else}
    У Вас имеются активные баны, доступ на сервера Вам запрещён. Узнайте больше <a href="index.php?p=banlist&advType=banid&advSearch={$IsBanned}">здесь</a>.
  {/if}
  </span>

  <br />
  <span class="p-l-10">Проверьте наличие бана по SteamID в пару щелчков <a href="index.php?p=check">здесь</a>.</span>
</div>