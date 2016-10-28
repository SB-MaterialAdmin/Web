<?php  
// *************************************************************************
//  This file is part of SourceBans++.
//
//  Copyright (C) 2014-2016 Sarabveer Singh <me@sarabveer.me>
//
//  SourceBans++ is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, per version 3 of the License.
//
//  SourceBans++ is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with SourceBans++. If not, see <http://www.gnu.org/licenses/>.
//
//  This file is based off work covered by the following copyright(s):  
//
//   SourceBans 1.4.11
//   Copyright (C) 2007-2015 SourceBans Team - Part of GameConnect
//   Licensed under GNU GPL version 3, or later.
//   Page: <http://www.sourcebans.net/> - <https://github.com/GameConnect/sourcebansv1>
//
// *************************************************************************

if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();} 

global $theme, $userbank;


$theme->assign('UPDATE_NUM', $GLOBALS['config']['config.version']);
$theme->assign('THEME_VERSION', theme_version);
$theme->assign('THEME_LINK', theme_link);
$theme->assign('SB_VERSION', 	SB_VERSION);
$theme->display('page_footer.tpl');

if(isset($_GET['p']))
	$_SESSION['p'] = $_GET['p'];
if(isset($_GET['c']))
	$_SESSION['c'] = $_GET['c'];
if(isset($_GET['p']) && $_GET['p'] != "login")
	$_SESSION['q'] = $_SERVER['QUERY_STRING'];


	
if(defined('DEVELOPER_MODE'))
	{
		global $start;
		$time = microtime();
		$time = explode(" ", $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$totaltime = ($finish - $start);
		printf ("<h3>Page took %f seconds to load.</h3>", $totaltime);
		
		echo '<h3>User Manager Data</h3><pre>';
		PrintArray($userbank); 
		echo '</pre><h3>Post Data</h3><pre>';
		print_r($_POST); 
		echo '</pre><h3>Session Data</h3><pre>'; 
	 	print_r($_SESSION); echo'</pre> ';
	 	echo '</pre><h3>Cookie Data</h3><pre>'; 
	 	print_r($_COOKIE); echo'</pre> ';
}
?>
</div>
<script type="text/javascript">
	function notify(message, type, fro, alig, animIn, adnimOut, delay){
        jQuery.growl({
            message: message,
			url: "",
			icon: "zmdi zmdi-fire zmdi-hc-fw"
        },{
            type: type,
            allow_dismiss: true,
            label: 'Cancel',
            className: 'btn-xs btn-inverse',
            placement: {
                from: fro,
                align: alig
            },
            delay: delay,
            animate: {
                    enter: animIn,
                    exit: adnimOut
            },
            offset: {
                x: 20,
                y: 85
            },
			template: '<div data-growl="container" class="alert" role="alert">' +
                                        '<button type="button" class="close" data-growl="dismiss">' +
                                            '<span aria-hidden="true">&times;</span>' +
                                            '<span class="sr-only">Close</span>' +
                                        '</button>' +
                                        '<span data-growl="icon"></span>' +
                                        '<span data-growl="title"></span>' +
                                        '<span data-growl="message"></span>' +
                                        '<a href="#" data-growl="url"></a>' +
                                    '</div>'
        });
    };
	<?php
		if(isset($GLOBALS['config']['config.text_home']) && $GLOBALS['config']['config.text_home'] != ""){
			echo "
				if (jQuery('.login-content')[0]) {
					notify(' ".$GLOBALS['config']['config.text_home']."', 'inverse', 'top', 'left', 'animated bounceInLeft', 'animated bounceOutLeft', 3800);
			}";
		}
	
	
		if(isset($GLOBALS['config']['config.text_mon']) && $GLOBALS['config']['config.text_mon'] != ""){
			echo "
				if (jQuery('.servers_pg')[0]) {
					setTimeout(\"notify(' ".$GLOBALS['config']['config.text_mon']."', 'info', 'bottom', 'right', 'animated fadeInRight', 'animated fadeOutRight', 4900);\", 200);
			}";
		}
	
	
	
		if(isset($GLOBALS['config']['config.text_acc']) && $GLOBALS['config']['config.text_mon'] != ""){
			echo "
				if (jQuery('.admin-content')[0]) {
					setTimeout(\"notify(' ".$GLOBALS['config']['config.text_acc']."', 'success', 'top', 'right', 'animated fadeInRight', 'animated fadeOutRight', 4500);\", 200);
				}";
		} 
	
		if(isset($GLOBALS['config']['config.text_acc2']) && $GLOBALS['config']['config.text_mon'] != ""){
			echo "
				if (jQuery('.admin-content')[0]) {
					setTimeout(\"notify(' ".$GLOBALS['config']['config.text_acc2']."', 'info', 'top', 'right', 'animated fadeInRight', 'animated bounceOut', 5800);\", 200);
			}";
		}
	?>
</script>
<script type="text/javascript">
var settab = ProcessAdminTabs();
window.addEvent('domready', function(){	
				<?php if(isset($GLOBALS['server_qry']))
					echo $GLOBALS['server_qry'];
					?>	
				
			var Tips2 = new Tips($$('.tip'), {
				initialize:function(){
					this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 300, wait: false}).set(0);
				},
				onShow: function(toolTip) {
					//this.fx.start(1);
				},
				onHide: function(toolTip) {
					//this.fx.start(0);
				}
			});
			var Tips4 = new Tips($$('.perm'), {
				className: 'perm'
			});
		}); 
		<?php 
	if(isset($GLOBALS['NavRewrite']) && $userbank->is_logged_in())
		echo "$('nav').setHTML('" .  $GLOBALS['NavRewrite'] . "');"//$('content_title').setHTML('<?php  echo $GLOBALS['TitleRewrite']');
	?>
	<?php if(isset($GLOBALS['enable']))
	{?>
	if($('<?php echo $GLOBALS['enable']?>'))
	{
		if(settab != -1)
			$(settab).setStyle('display', 'block');
		else
			$('<?php echo $GLOBALS['enable']?>').setStyle('display', 'block');
		
	}
	<?php } ?>
	<?php 
	if(isset($_GET['o']) && $_GET['o'] == "rcon"){
		echo "
			var scroll = new Fx.Scroll($('rcon'),{duration: 500, transition: Fx.Transitions.Cubic.easeInOut});	
			if(scroll)scroll.toBottom();";
	}?>
	</script>

<?php if(is_object($log))$log->WriteLogEntries(); ?>
	
<!--[if lt IE 7]>
<script defer type="text/javascript" src="./scripts/pngfix.js"></script>
<![endif]-->

</body>
</html>

