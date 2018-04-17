<?php
$GLOBALS['db']->Execute('CREATE TABLE `' . DB_PREFIX . '_vac` (
  `account_id` INT NOT NULL ,
  `status` BOOLEAN NOT NULL ,
  `updated_on` INT NOT NULL ,
  PRIMARY KEY (`account_id`)
) ENGINE = InnoDB;');