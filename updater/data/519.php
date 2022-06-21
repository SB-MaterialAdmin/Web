<?php
$GLOBALS['db']->Execute(sprintf(
    "INSERT INTO
        `%s_settings`
    (`setting`, `value`)
    VALUES ('gamecache.entry_lifetime', '30'),
           ('gamecache.enabled',        '1')",
    DB_PREFIX
));

return true;