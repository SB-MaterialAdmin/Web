<?php
$GLOBALS['db']->Execute(sprintf(
    "INSERT INTO
        `%s_settings`
    (`option`, `value`)
    VALUES (
        'nulladmin.name',
        'CONSOLE'
    )",
    DB_PREFIX
));

return true;