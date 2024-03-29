<?php
var_dump($_FILES);
?>
<html>
	<head>
		<title>Teste Uploader</title>
		<link href="http://hayageek.github.io/jQuery-Upload-File/4.0.2/uploadfile.css" rel="stylesheet">
	</head>
	<body>
		<div id="fileuploader">Upload</div>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="http://hayageek.github.io/jQuery-Upload-File/4.0.2/jquery.uploadfile.min.js"></script>	
		<script>
			$(document).ready(function()
			{
				$("#fileuploader").uploadFile({
				url:"index.php?controller=testeuploader",
				fileName:"myfile"
				});
			});
		</script>
	</body>	
</html>		