<?php
/*
 * Костыль для очистки БД от старой статики. Которая очень костыльная.
 * Скачать можно было здесь: <http://hlmod.ru/posts/268630>
 */
$GLOBALS['db']->Execute("DROP TABLE IF EXISTS `" . DB_PREFIX . "_pages`;");

// Создаём новую таблицу.
$GLOBALS['db']->Execute("CREATE TABLE `" . DB_PREFIX . "_pages` (
  `id` int(11) NOT NULL,
  `url` varchar(32) NOT NULL,
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `use_default_template` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

// Назначаем уникальные индексы и Автоинкремент.
$GLOBALS['db']->Execute("ALTER TABLE `" . DB_PREFIX . "_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`);");

$GLOBALS['db']->Execute("ALTER TABLE `" . DB_PREFIX . "_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

// Создадим Хеллоу Ворлд для Администратора.
$GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_pages` (`id`, `url`, `title`, `content`, `use_default_template`) VALUES (NULL, 'helloworld', 'Привет, мир!', '<p>Это пример Статической страницы, на которой Вы можете писать что угодно. Начиная правилами Вашего проекта, заканчивая командами, доступными на Ваших серверах!</p>[{onlyadmins}]<p>Поскольку Вы Администратор, то можете отредактировать её нажатием на кнопку карандаша в углу страницы, если у Вас есть доступ к настройкам веб-панели SourceBans.</p>[{/onlyadmins}][{onlyusers}]<p>Этот текст виден только обычным пользователям!<p>[{/onlyusers}]', '1');");
?>
