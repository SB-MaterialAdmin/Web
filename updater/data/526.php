<?php

$DB = \DatabaseManager::GetConnection();
$DB->Query('
    INSERT INTO
        `{{prefix}}settings`
        (`setting`, `value`)
    VALUES
    ("demoEnabled", 1)
');