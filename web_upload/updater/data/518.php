<?php
\MaterialAdmin\DataStorage::ADOdb()->Execute(sprintf(
    "INSERT INTO
        `%s_settings`
    (`setting`, `value`)
    VALUES (
        'nulladmin.name',
        'CONSOLE'
    )",
    DB_PREFIX
));

return true;