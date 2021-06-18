<?php
if (!defined('IN_SB')) die("You should not be here. Only follow links!");

function SteamAuthorize($site) {
	$openid = new LightOpenID($site);
	if (!$openid->mode) {
		$openid->identity = 'http://steamcommunity.com/openid';
		return $openid->authUrl();
	} elseif($openid->mode == 'cancel') {
		return false;
	} elseif ($openid->mode == 'id_res' && $openid->validate()) {
		$id = $openid->identity;
		$ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
		preg_match($ptn, $id, $matches);
		
		if (!empty($matches[1])) return $matches[1];
		else return null;
	}
}
?>
