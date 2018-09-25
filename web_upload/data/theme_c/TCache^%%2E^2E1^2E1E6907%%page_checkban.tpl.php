<?php /* Smarty version 2.6.29, created on 2018-09-19 18:10:03
         compiled from page_checkban.tpl */ ?>
<div class="card">
  <div class="card-header">
    <h2>Быстрый поиск банов</h2>
  </div>

  <div class="alert alert-info" role="alert">
    <h4>Данные, использованные при поиске банов</h4>
    <ul class="clist clist-star">
      <li><b>IP-адрес</b>: <?php echo $this->_tpl_vars['user']['ip']; ?>
</li>
      <li><b>SteamID</b>: <?php if ($this->_tpl_vars['user']['steam'] == false): ?>Неизвестен<?php else: ?><?php echo $this->_tpl_vars['user']['steam']; ?>
<?php endif; ?>
    </ul>
    <?php if ($this->_tpl_vars['user']['steam'] == false): ?>
    <br />
    Для быстрого поиска банов по SteamID, <a href="steam_auth.php?reason=user_auth">авторизуйтесь</a>.
    <?php endif; ?>
  </div>

  <ul class="clist clist-star">
    <li><b>Бан по IP</b>: <?php if ($this->_tpl_vars['check_result']['GameBan']['IP']): ?>Активен, см. подробнее <a href="index.php?p=banlist&advType=banid&advSearch=<?php echo $this->_tpl_vars['check_result']['GameBan']['IP']; ?>
">здесь</a><?php else: ?>Отсутствует<?php endif; ?>.</li>
    <li><b>Бан по SteamID</b>: <?php if ($this->_tpl_vars['user']['steam']): ?><?php if ($this->_tpl_vars['check_result']['GameBan']['Steam'] > 0): ?>Активен, см. подробнее <a href="index.php?p=banlist&advType=banid&advSearch=<?php echo $this->_tpl_vars['check_result']['GameBan']['Steam']; ?>
">здесь</a><?php else: ?>Отсутствует<?php endif; ?><?php else: ?>Не удаётся проверить, SteamID неизвестен<?php endif; ?>.</li>
    <li><b>Текстовый чат</b>: <?php if ($this->_tpl_vars['user']['steam']): ?><?php if ($this->_tpl_vars['check_result']['CommBan']['Gag'] > 0): ?>Активен, см. подробнее <a href="index.php?p=comms&advType=banid&advSearch=<?php echo $this->_tpl_vars['check_result']['CommBan']['Gag']; ?>
">здесь</a><?php else: ?>Отсутствует<?php endif; ?><?php else: ?>Не удаётся проверить, SteamID неизвестен<?php endif; ?>.</li>
    <li><b>Голосовой чат</b>: <?php if ($this->_tpl_vars['user']['steam']): ?><?php if ($this->_tpl_vars['check_result']['CommBan']['Voice'] > 0): ?>Активен, см. подробнее <a href="index.php?p=comms&advType=banid&advSearch=<?php echo $this->_tpl_vars['check_result']['CommBan']['Voice']; ?>
">здесь</a><?php else: ?>Отсутствует<?php endif; ?><?php else: ?>Не удаётся проверить, SteamID неизвестен<?php endif; ?>.</li>
  </ul>
  <br />
</div>