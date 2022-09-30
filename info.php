<?php
	$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
	if (md5($senha) == 'bf4d9a9fd8ca63472939edad14a91a8d'){
		phpinfo();
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>PHP Info</title>
	</head>
	<body>
		<form action="info.php" method="POST">
			<fieldset>
				<legend>PHP Info</legend>
				<div>
					<label>Senha</label>
					<br/>
					<input type="password" id="senha" name="senha" />
				</div>
				<div>
					<input type="submit" value="Visualizar" />
				</div>
			</fieldset>
		</form>
	</body>
</html>