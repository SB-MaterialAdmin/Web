<?php /* Smarty version 2.6.29, created on 2018-09-19 13:38:01
         compiled from page_kickit.tpl */ ?>
<html>
<head>
<meta charset="UTF-8">
<?php echo $this->_tpl_vars['xajax_functions']; ?>

<script type="text/javascript">
//<![CDATA[
window.onload = function() {xajax_LoadServers('<?php echo $this->_tpl_vars['check']; ?>
', '<?php echo $this->_tpl_vars['type']; ?>
');}
var srvcount = 0;
function set_counter(count)
{
	srvcount += count;
	if(srvcount==<?php echo $this->_tpl_vars['total']; ?>
 || count=='-1') {
		//parent.document.getElementById('dialog-control').innerHTML = "<font color=\"green\" class=""><b>Поиск закончен. Переадресация...</b></font>"+parent.document.getElementById('dialog-control').innerHTML;
		parent.document.getElementById('dialog-control').innerHTML = "<font color=\"green\" class=\"f-15\"><b>Поиск закончен. Переадресация...</b></font>"+parent.document.getElementById('dialog-control').innerHTML;
		parent.document.getElementById('dialog-control').setStyle('display', 'block');
		//setTimeout("parent.document.getElementById('dialog-placement').setStyle('display', 'none');",5000);
		//setTimeout("window.location='../index.php?p=admin&c=bans'",5000);
		setTimeout("parent.document.location='../index.php?p=admin&c=bans';",3500);
	}
}
parent.document.getElementById('dialog-control').setStyle('display', 'none');
//]]>
</script>
</head>
<body style="background-repeat: repeat-x;color: #444;font-family: Verdana, Arial, Tahoma, Trebuchet MS, Sans-Serif, Georgia, Courier, Times New Roman, Serif;font-size: 11px;line-height: 135%;margin: 5px;padding: 0px;">
<div id="container" name="container">
<h3 style="font-size: 12px;">Идет поиск нарушителя на серверах....</h3>
<table border="0">
<?php $_from = $this->_tpl_vars['servers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['serv']):
?>
<tr>
	<td><div id="srvip_<?php echo $this->_tpl_vars['serv']['num']; ?>
"><font size="1"><?php echo $this->_tpl_vars['serv']['ip']; ?>
:<?php echo $this->_tpl_vars['serv']['port']; ?>
</font></div></td>
	<td>
		<div id="srv_<?php echo $this->_tpl_vars['serv']['num']; ?>
"><font size="1">Ждите...</font></div>
	</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
</div>
<script type="text/javascript">
if(document.all) {
	parent.document.all["srvkicker"].height = document.all["container"].offsetHeight + 10;
}
else {
	parent.document.getElementById("srvkicker").height = document.documentElement.clientHeight;
}
</script>
</body>
</html>