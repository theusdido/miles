<?php

	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

	require 'conexao.php';
	include 'funcoes.php';
?>
<!DOCTYPE html>
<html  lang="pt-br">
	<head>
		<title>Listar Coluna</title>
		<?php include 'head.php' ?>
	</head>
	<body>
		<?php 
			include 'menu_topo.php'; 
		?>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="col-md-2">
					<?php
						//include 'menu_entidade.php';
					?>
				</div>
				<div class="col-md-10">
				</div>
			</div>
		</div>
	</body>
</html>