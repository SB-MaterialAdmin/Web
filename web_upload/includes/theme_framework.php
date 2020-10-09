<?php
global $theme;

// Подгрузка самого нужного плагина
require_once(INCLUDES_PATH . "/smarty/plugins/function.help_icon.php");

// Регистрация функций
$theme->registerPlugin('function', "display_material_checkbox", "materialdesign_checkbox", true);
$theme->registerPlugin('function', "display_material_input", "materialdesign_input", true);
$theme->registerPlugin('function', "display_header", "materialdesign_cardheader", true);
$theme->registerPlugin('function', "display_alert", "materialdesign_alert", true);
$theme->registerPlugin('block', "render_material_body", "materialdesign_body");

// Создание каллбэков функций
function materialdesign_checkbox($params, Smarty_Internal_Template $template) {
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

function materialdesign_input($params, Smarty_Internal_Template $template) {
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

function materialdesign_cardheader($params, Smarty_Internal_Template $template) {
    if (!isset($params['title']))
        return "";

    $str  = '<div class="card-header"><h2>'.$params['title'];
    $str .= (isset($params['text']))?"<small>".$params['text']."</small>":"";
    $str .= "</h2></div>";

    return $str;
}

function materialdesign_alert($params, Smarty_Internal_Template $template) {
    return sprintf('<div class="alert alert-info" role="alert">%s</div>', $params['text']);
}

function materialdesign_body($params, $content, Smarty_Internal_Template $template) {
    $out = '<div class="card-body';
    if ($params['padding'])
        $out .= " card-padding";
    if ($params['clearfix'])
        $out .= " clearfix";
    $out .= '">'.$content."</div>";
    
    return $out;
}
