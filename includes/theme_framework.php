<?php
global $theme;

// Регистрация функций
$theme->registerPlugin(Smarty\Smarty::PLUGIN_FUNCTION, 'display_material_checkbox', 'materialdesign_checkbox');
$theme->registerPlugin(Smarty\Smarty::PLUGIN_FUNCTION, 'display_material_input', 'materialdesign_input');
$theme->registerPlugin(Smarty\Smarty::PLUGIN_FUNCTION, 'display_header', 'materialdesign_cardheader');
$theme->registerPlugin(Smarty\Smarty::PLUGIN_FUNCTION, 'display_alert', 'materialdesign_alert');
$theme->registerPlugin(Smarty\Smarty::PLUGIN_FUNCTION, 'steamid_format', 'steamid_format');
//$theme->registerPlugin(Smarty\Smarty::PLUGIN_FUNCTION, 'highlight_links_fn', 'highlight_links_fn');
$theme->registerPlugin(Smarty\Smarty::PLUGIN_FUNCTION, 'sb_button', 'smarty_function_sb_button');
$theme->registerPlugin(Smarty\Smarty::PLUGIN_FUNCTION, 'help_icon', 'smarty_function_help_icon');

$theme->registerPlugin(Smarty\Smarty::PLUGIN_BLOCK, 'render_material_body', 'materialdesign_body');
$theme->registerPlugin(Smarty\Smarty::PLUGIN_BLOCK, 'highlight_links', 'highlight_links_block');

$theme->registerPlugin(Smarty\Smarty::PLUGIN_MODIFIER, 'unserialize', function($string) {
    return unserialize($string);
});

// Создание каллбэков функций
function smarty_function_help_icon($params, &$smarty)
{
    $style = $params['style'] ?? "";
    return '<img border="0" align="absbottom" src="images/help.png" style="float:left;'.$style.'" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="' .  $params['message'] . '" title="" data-original-title="' .  $params['title'] . '">&nbsp;&nbsp;';
}

function smarty_function_sb_button($params, &$smarty) {
    $text   = $params['text']   ?? '';
    $click  = $params['onclick'] ?? '';
    $class  = $params['class']  ?? '';
    $id     = $params['id']     ?? '';
    $icon   = $params['icon']   ?? '';
    $submit = $params['submit'] ?? false;

    $type = $submit ? 'submit' : 'button';

    //$button = "<input type='$type' onclick=\"$click\" name='$id' class='btn $class' onmouseover='ButtonOver(\"$id\")' onmouseout='ButtonOver(\"$id\")' id='$id' value='$text'>";
    $button = "<button type='$type' onclick=\"$click\" name='$id' class='btn $class waves-effect' onmouseover='ButtonOver(\"$id\")' onmouseout='ButtonOver(\"$id\")' id='$id' value='$text'>$icon$text</button>";
    return $button;
}

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

function materialdesign_cardheader($params, &$smarty)
{
    if (!isset($params['title'])) {
        return "";
	}

    $str  = '<div class="card-header"><h2>'.$params['title'];
    $str .= (isset($params['text']))?"<small>".$params['text']."</small>":"";
    $str .= "</h2></div>";

    return $str;
}

function materialdesign_alert($params, &$smarty)
{
    return sprintf('<div class="alert alert-info" role="alert">%s</div>', $params['text']);
}

function materialdesign_body($params, $content, &$smarty)
{
    $out = '<div class="card-body';

	if ($params['padding']) {
		$out .= " card-padding";
	}

    if ($params['clearfix']) {
        $out .= " clearfix";
	}

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
    if ($repeat) {
        return;
    }

    return highlight_links_fn(['content' => $content], $smarty);
}

function highlight_links_fn($params, &$smarty)
{
	if (empty($params['content'])) {
		return "";
	}

    return preg_replace('/\w{1,}:\/\/\S{1,}/', '<a href="${0}">${0}</a>', $params['content']);
}
