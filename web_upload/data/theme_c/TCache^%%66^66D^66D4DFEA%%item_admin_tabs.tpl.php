<?php /* Smarty version 2.6.29, created on 2018-09-18 17:04:10
         compiled from item_admin_tabs.tpl */ ?>
<div class="card">
	<div class="card-body">
		<div id="admin-page-menu" class="fw-container">
			<ul class="tab-nav text-center fw-nav">
				<?php $_from = $this->_tpl_vars['tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tab']):
?>
					<?php echo $this->_tpl_vars['tab']['tab']; ?>

				<?php endforeach; endif; unset($_from); ?>
			</ul>