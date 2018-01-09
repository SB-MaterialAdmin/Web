<?php
function smarty_function_translate($params, &$smarty) {
    try {
        $args = array();
        $id = 1;
        while (true) {
            if (isset($params['transarg_' . $id])) {
                $args = $params['transarg_' . $id];
                $id++;
                continue;
            }

            break;
        }

        return \MaterialAdmin\DataStorage::Translator()->retrieve($params['phrase'], $args);
    } catch (Exception $e) {
        return "Exception reported: " . $e->getMessage();
    }
}