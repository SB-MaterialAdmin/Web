<div class="card">
  <div class="card-header">
    <h2>Быстрый поиск банов</h2>
  </div>

  <div class="alert alert-info" role="alert">
    <h4>Данные, использованные при поиске банов</h4>
    <ul class="clist clist-star">
      <li><b>IP-адрес</b>: {$user.ip}</li>
      <li><b>SteamID</b>: {if $user.steam == false}Неизвестен{else}{$user.steam}{/if}
    </ul>
    {if $user.steam == false}
    <br />
    Для быстрого поиска банов по SteamID, <a href="steam_auth.php?reason=user_auth">авторизуйтесь</a>.
    {/if}
  </div>

  <ul class="clist clist-star">
    <li><b>Бан по IP</b>: {if $check_result.GameBan.IP}Активен, см. подробнее <a href="index.php?p=banlist&advType=banid&advSearch={$check_result.GameBan.IP}">здесь.{else}Отсутствует{/if}</li>
    <li><b>Бан по SteamID</b>: {if $user.steam}{if $check_result.GameBan.IP}Активен, см. подробнее <a href="index.php?p=banlist&advType=banid&advSearch={$check_result.GameBan.IP}">здесь.{else}Отсутствует{/if}{else}Не удаётся проверить, SteamID неизвестен.{/if}</li>
    <li><b>Текстовый чат</b>: {if $user.steam}{if $check_result.CommBan.Gag}Активен, см. подробнее <a href="index.php?p=comms&advType=banid&advSearch={$check_result.CommBan.Gag}">здесь.{else}Отсутствует{/if}{else}Не удаётся проверить, SteamID неизвестен.{/if}</li>
    <li><b>Голосовой чат</b>: {if $user.steam}{if $check_result.CommBan.Voice}Активен, см. подробнее <a href="index.php?p=comms&advType=banid&advSearch={$check_result.CommBan.Voice}">здесь.{else}Отсутствует{/if}{else}Не удаётся проверить, SteamID неизвестен.{/if}</li>
  </ul>
  <br />
</div>