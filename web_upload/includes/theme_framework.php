<?php
global $theme;

// Регистрация функций
$theme->register_function("display_material_checkbox", "materialdesign_checkbox");
$theme->register_function("display_material_input", "materialdesign_input");
$theme->register_function("display_header", "materialdesign_cardheader");
$theme->register_function("display_alert", "materialdesign_alert");
$theme->register_function('sb_button', 'smarty_function_sb_button');;
$theme->register_function('help_icon', 'smarty_function_help_icon');
$theme->register_block("render_material_body", "materialdesign_body");

// Создание каллбэков функций
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

function materialdesign_cardheader($params, &$smarty) {
    if (!isset($params['title']))
        return "";

    $str  = '<div class="card-header"><h2>'.$params['title'];
    $str .= (isset($params['text']))?"<small>".$params['text']."</small>":"";
    $str .= "</h2></div>";

    return $str;
}

function materialdesign_alert($params, &$smarty) {
    return sprintf('<div class="alert alert-info" role="alert">%s</div>', $params['text']);
}

function materialdesign_body($params, $content, &$smarty) {
    $out = '<div class="card-body';
    if ($params['padding'])
        $out .= " card-padding";
    if ($params['clearfix'])
        $out .= " clearfix";
    $out .= '">'.$content."</div>";
    
    return $out;
}

function smarty_function_sb_button($params, &$smarty) //$text, $click, $class, $id="", $submit=false
{
    $text = isset($params['text'])?$params['text']:"";
    $click = isset($params['onclick'])?$params['onclick']:"";
    $class = isset($params['class'])?$params['class']:"";
    $id = isset($params['id'])?$params['id']:"";
    $icon = isset($params['icon'])?$params['icon']:"";
    $submit = isset($params['submit'])?$params['submit']:"";

    $type = $submit ? "submit" : "button";
    //$button = "<input type='$type' onclick=\"$click\" name='$id' class='btn $class' onmouseover='ButtonOver(\"$id\")' onmouseout='ButtonOver(\"$id\")' id='$id' value='$text' />";
    $button = "<button type='$type' onclick=\"$click\" name='$id' class='btn $class waves-effect' onmouseover='ButtonOver(\"$id\")' onmouseout='ButtonOver(\"$id\")' id='$id' value='$text' />$icon$text</button>";
    return $button;
}

function smarty_function_help_icon($params, &$smarty)
{
    $style = isset($params['style'])?$params['style']:"";
    //return '<img border="0" align="absbottom" src="images/help.png" class="tip" title="' .  $params['title'] . ' :: ' .  $params['message'] . '">&nbsp;&nbsp;';
    return '<img border="0" align="absbottom" src="images/help.png" style="float:left;'.$style.'" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="' .  $params['message'] . '" title="" data-original-title="' .  $params['title'] . '">&nbsp;&nbsp;';
    //oder
    //return '<i class="zmdi zmdi-pin-help zmdi-hc-fw" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="' .  $params['message'] . '" title="" data-original-title="' .  $params['title'] . '"></i>';
}
