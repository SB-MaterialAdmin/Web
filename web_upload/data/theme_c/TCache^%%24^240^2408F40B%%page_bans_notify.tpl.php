<?php /* Smarty version 2.6.29, created on 2018-09-18 17:19:51
         compiled from page_bans_notify.tpl */ ?>
<div class="alert alert-<?php if ($this->_tpl_vars['IsBanned'] > 0): ?>danger<?php else: ?>success<?php endif; ?>" role="alert">
  <h4>Быстрый поиск бана</h4>
  <span class="p-l-10"><b>Ваш IP-адрес - <?php echo $this->_tpl_vars['UserIP']; ?>
</b>.&nbsp;
  <?php if ($this->_tpl_vars['IsBanned'] === NULL): ?>
    Всё хорошо, сегодня без нарушений.
  <?php else: ?>
    У Вас имеются активные баны, доступ на сервера Вам запрещён. Узнайте больше <a href="index.php?p=banlist&advType=banid&advSearch=<?php echo $this->_tpl_vars['IsBanned']; ?>
">здесь</a>.
  <?php endif; ?>
  </span>

  <br />
  <span class="p-l-10">Проверьте наличие бана по SteamID в пару щелчков <a href="index.php?p=check">здесь</a>.</span>
</div>