<html>
	<head>
		<title>Загрузить файл : SourceBans</title>
		<link rel="Shortcut Icon" href="../images/favicon.ico" />
		<link href="../themes/new_box/css/uploadfile.css" rel="Stylesheet" />
	</head>
	<body bgcolor="e9e9e9">
		<h3>{$title}</h3>
		Выберите файл для загрузки. Файл должен быть в формате {$formats}.<br>
		{$message}
		<form action="" method="POST" id="{$form_name}" enctype="multipart/form-data">
			<input name="upload" value="1" type="hidden">
			<input name="{$input_name}" size="25" class="submit-fields" type="file" multiple> <br />
			<button class="button-Submit" type="submit">Сохранить</button>
		</form>
	</body>
</html>
