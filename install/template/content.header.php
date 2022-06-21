<?php if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();} ?>

<section id="content">
	<div class="container">
		<div class="block-header">
			<h2 id="content_title">
				<?php echo isset($GLOBALS['pagetitle'])?$GLOBALS['pagetitle']:null;?>
			</h2>
		</div>
		
		<div id="msg-red-debug" style="display:none;" >
			<i><img src="./images/warning.png" alt="Warning" /></i>
			<b>Debug</b>
			<br />
			<div id="debug-text">
			</div></i>
		</div>
