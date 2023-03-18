<?php

$serializedEmptyArray = serialize([]);

$options = \App::options();
$optionNames = ['unban.customreasons', 'comms.customreasons', 'remove_comms.customreasons'];

foreach ($optionNames as $name)
{
    $options->create($name, $serializedEmptyArray);
}
