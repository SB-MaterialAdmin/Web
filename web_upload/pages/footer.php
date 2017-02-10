<?php
/**************************************************************************
 * Эта программа является частью SourceBans MATERIAL Admin.
 *
 * Все права защищены © 2016-2017 Sergey Gut <webmaster@kruzefag.ru>
 *
 * SourceBans MATERIAL Admin распространяется под лицензией
 * Creative Commons Attribution-NonCommercial-ShareAlike 3.0.
 *
 * Вы должны были получить копию лицензии вместе с этой работой. Если нет,
 * см. <http://creativecommons.org/licenses/by-nc-sa/3.0/>.
 *
 * ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО
 * ГАРАНТИЙ, ЯВНЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ,
 * ГАРАНТИИ ПРИГОДНОСТИ ДЛЯ КОНКРЕТНЫХ ЦЕЛЕЙ И НЕНАРУШЕНИЯ. НИ ПРИ КАКИХ
 * ОБСТОЯТЕЛЬСТВАХ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ЗА
 * ЛЮБЫЕ ПРЕТЕНЗИИ, ИЛИ УБЫТКИ, НЕЗАВИСИМО ОТ ДЕЙСТВИЯ ДОГОВОРА,
 * ГРАЖДАНСКОГО ПРАВОНАРУШЕНИЯ ИЛИ ИНАЧЕ, ВОЗНИКАЮЩИЕ ИЗ, ИЛИ В СВЯЗИ С
 * ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ
 * ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ.
 *
 * Эта программа базируется на работе, охватываемой следующим авторским
 *                                                           правом (ами):
 *
 *  * SourceBans ++
 *    Copyright © 2014-2016 Sarabveer Singh
 *    Выпущено под лицензией CC BY-NC-SA 3.0
 *    Страница: <https://sbpp.github.io/>
 *
 ***************************************************************************/

if (!defined('IN_SB')) {echo("Вы не должны быть здесь. Используйте только ссылки внутри системы!");die();}

global $theme, $userbank;

global $start;
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$finish = $time;
$totaltime = ($finish - $start);

$theme->assign('UPDATE_NUM', $GLOBALS['config']['config.version']);
$theme->assign('THEME_VERSION', theme_version);
$theme->assign('BRANCH', MA_BRANCH);
$theme->assign('THEME_LINK', theme_link);
$theme->assign('SB_VERSION', SB_VERSION);

$theme->assign('show_gendata',      ($GLOBALS['config']['page.footer.allow_show_data'] == "1"));
$theme->assign('gendata_queries',   $GLOBALS['db']->Queries);
$theme->assign('gendata_time',      round($totaltime, 2));

$theme->assign('splash_screen',     ($GLOBALS['config']['theme.splashscreen'] == "1"));

$theme->display('page_footer.tpl');

if(isset($_GET['p']))
	$_SESSION['p'] = $_GET['p'];
if(isset($_GET['c']))
	$_SESSION['c'] = $_GET['c'];
if(isset($_GET['p']) && $_GET['p'] != "login")
	$_SESSION['q'] = $_SERVER['QUERY_STRING'];


	
if(defined('DEVELOPER_MODE')) {
		echo('<div class="container" style="padding-bottom: 100px;"><div class="card">');
		echo('<div class="card-header"><h2>Режим отладки SourceBans</h2></div><div class="card-body card-padding">Активен режим отладки SourceBans. Для отключения, снимите галочку с пункта "Режим отладки" в настройках SourceBans или закомментируйте строчку <pre>define(\'DEVELOPER_MODE\', true);</pre> в <i>config.php</i></div>');
		echo('<div class="card-header"><h2>Менеджер данных пользователя</h2></div><div class="card-body card-padding"><pre>');
		PrintArray($userbank);
		echo('</pre></div><div class="card-header"><h2>Сообщение данных</h2></div><div class="card-body card-padding"><pre>');
		print_r($_POST);
		echo('</pre></div><div class="card-header"><h2>Данные сеанса</h2></div><div class="card-body card-padding"><pre>');
	 	print_r($_SESSION);
	 	echo('</pre></div><div class="card-header"><h2>Данные Cookie</h2></div><div class="card-body card-padding"><pre>'); 
	 	print_r($_COOKIE);
	 	echo('</pre></div></div></div>');
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
                                            '<span class="sr-only">Закрыть</span>' +
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

<?php if(is_object($GLOBALS['log'])) $GLOBALS['log']->WriteLogEntries(); ?>
	
<!--[if lt IE 7]>
<script defer type="text/javascript" src="./scripts/pngfix.js"></script>
<![endif]-->

<?php if (isset($_COOKIE['ScriptFooter'])) { ?>
    <script type="text/javascript">
        <?php echo $_COOKIE['ScriptFooter']; ?>
    </script>
<?php
    setcookie("ScriptFooter", "", time());
    } ?>

</body>
</html>

