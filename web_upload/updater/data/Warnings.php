<?php 
\MaterialAdmin\DataStorage::ADOdb()->Execute("CREATE TABLE `" . DB_PREFIX . "_warns` (
  `id` int(11) NOT NULL,
  `arecipient` int(11) NOT NULL,
  `afrom` int(11) NOT NULL,
  `expires` int(11) NOT NULL,
  `reason` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

\MaterialAdmin\DataStorage::ADOdb()->Execute("INSERT INTO `" . DB_PREFIX . "_settings` (`setting`, `value`) VALUES ('admin.warns', '1'), ('admin.warns.max', '3');");
