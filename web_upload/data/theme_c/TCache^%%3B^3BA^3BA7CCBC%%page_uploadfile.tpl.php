<?php /* Smarty version 2.6.29, created on 2018-09-18 17:50:36
         compiled from page_uploadfile.tpl */ ?>
<html>
	<head>
		<title>Загрузить файл : SourceBans</title>
		<link rel="Shortcut Icon" href="../images/favicon.ico" />
		<link href="../theme/css/uploadfile.css" rel="Stylesheet" />
	</head>
	<body bgcolor="e9e9e9">
		<h3><?php echo $this->_tpl_vars['title']; ?>
</h3>
		Выберите файл для загрузки. Файл должен быть в формате <?php echo $this->_tpl_vars['formats']; ?>
.<br>
		<?php echo $this->_tpl_vars['message']; ?>

		<form action="" method="POST" id="<?php echo $this->_tpl_vars['form_name']; ?>
" enctype="multipart/form-data">
			<input name="upload" value="1" type="hidden">
			<input name="<?php echo $this->_tpl_vars['input_name']; ?>
" size="25" class="submit-fields" type="file" multiple> <br />
			<button class="button-Submit" type="submit">Сохранить</button>
		</form>
	</body>
</html>