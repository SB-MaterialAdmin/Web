


<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {help_icon title="gaben" message="hello"} function plugin
 *
 * Type:     function<br>
 * Name:     help tip<br>
 * Purpose:  show help tip
 * @link http://www.sourcebans.net
 * @author  SourceBans Development Team
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_help_icon($params, &$smarty)
{
	$style = isset($params['style'])?$params['style']:"";
	 //return '<img border="0" align="absbottom" src="images/help.png" class="tip" title="' .  $params['title'] . ' :: ' .  $params['message'] . '">&nbsp;&nbsp;';
	 return '<img border="0" align="absbottom" src="images/help.png" style="float:left;'.$style.'" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="' .  $params['message'] . '" title="" data-original-title="' .  $params['title'] . '">&nbsp;&nbsp;';
	 //oder
	 //return '<i class="zmdi zmdi-pin-help zmdi-hc-fw" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="' .  $params['message'] . '" title="" data-original-title="' .  $params['title'] . '"></i>';
}
//<button class="btn btn-primary" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." title="" data-original-title="Popover Title"></button>



?>
