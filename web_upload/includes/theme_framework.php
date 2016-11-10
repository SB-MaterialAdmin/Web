<?php
global $theme;

// Регистрация функций
$theme->register_function("display_material_checkbox", "materialdesign_checkbox");
$theme->register_function("display_material_input", "materialdesign_input");

// Создание каллбэков
function materialdesign_checkbox($params, &$smarty) {
    if (!isset($params["name"]) || !isset($params["help_title"]) || !isset($params["help_text"]))
        return "";

    $str  = '<div class="form-group m-b-5">';
    $str .= '<label for="'.$params['name'].'" class="col-sm-3 control-label">'.smarty_function_help_icon(['title' => $params['help_title'], 'message' => $params['help_text']], $smarty)." ".$params["help_title"]."</label>";
    $str .= '<div class="col-sm-9"><div class="checkbox m-b-15">';
    $str .= '<label for="'.$params['name'].'">';
    $str .= '<input type="checkbox" name="'.$params['name'].'" id="'.$params['name'].'" hidden="hidden" />';
    $str .= '<i class="input-helper"></i> Включить?';
    $str .= '</label></div></div></div>';

    return $str;
}

function materialdesign_input($params, &$smarty) {
    if (!isset($params["name"]) || !isset($params["help_title"]) || !isset($params["help_text"]))
        return "";

    if (!isset($params['placeholder']))
        $params['placeholder'] = "Введите текст";
    if (!isset($params['value']))
        $params['value'] = "";

    $str  = '<div class="form-group m-b-5">';
    $str .= '<label for="'.$params['name'].'" class="col-sm-3 control-label">'.smarty_function_help_icon(['title' => $params['help_title'], 'message' => $params['help_text']], $smarty).' '.$params["help_title"].'</label>';
    $str .= '<div class="col-sm-9"><div class="fg-line">';
    $str .= '<input type="'.(isset($params['pass'])?"password":"text").'" TABINDEX=1 class="form-control" name="'.$params['name'].'" id="'.$params['name'].'" placeholder="'.$params['placeholder'].'" value="'.$params['value'].'" />';
    $str .= '</div></div></div>';

    return $str;
}
