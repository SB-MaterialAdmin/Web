<?php
global $theme;

// Подгрузка самого нужного плагина
require_once(INCLUDES_PATH . "/smarty/plugins/function.help_icon.php");

// Регистрация функций
$theme->register_function("display_material_checkbox", "materialdesign_checkbox");
$theme->register_function("display_material_input", "materialdesign_input");
$theme->register_function("display_header", "materialdesign_cardheader");
$theme->register_function("display_alert", "materialdesign_alert");
$theme->register_function('steamid_format', 'steamid_format');
$theme->register_block("render_material_body", "materialdesign_body");
$theme->register_block('highlight_links', 'highlight_links_block');
$theme->register_function('highlight_links', 'highlight_links_fn');

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

function steamid_format($params, &$smarty)
{
	$format = (!empty($params['format'])) ? $params['format'] : 'v2';
	$steamId = (!empty($params['steamid'])) ? $params['steamid'] : 'STEAM_ID_CONSOLE';
	$gameId = (!empty($params['gameid'])) ? $params['gameid'] : 0;
	$fallback = (!empty($params['fallback'])) ? $params['fallback'] : '';

    if (!in_array($format, ['v2', 'v3', 'CommunityID', 'AccountID'])) {
        trigger_error('Unknown SteamID format: ' . $format);
        return $fallback;
    }

    try {
        $steamId = CSteamId::factory($steamId, $gameId);
        return $steamId->{$format};
    } catch (\Exception $e) {
        return $fallback;
    }
}

function highlight_links_block($params, $content, &$smarty, &$repeat)
{
    return highlight_links_fn(['content' => $content], $smarty);
}

function highlight_links_fn($params, &$smarty)
{
	if (empty($params['content'])) {
		return "";
	}

    return preg_replace('/\w{1,}:\/\/\S{1,}/', '<a href="${0}">${0}</a>', $params['content']);
}
