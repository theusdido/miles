<?php

	// Projecto atual
	$currentproject 	= isset($_GET["currentproject"])?$_GET["currentproject"]:(isset($_POST["currentproject"])?$_POST["currentproject"]:1);
	
	// Ambiente atual
	$ambiente			= isset($_GET["ambiente"])?$_GET["ambiente"]:(isset($_POST["ambiente"])?$_POST["ambiente"]:'SISTEMA');	

	session_name("miles_" . $ambiente . "_" . $currentproject);
	session_start();

	if (empty($_SESSION)){		
		$_SESSION["CRIARBASE"] = 0;
		$_SESSION["INSTALACAOSISTEMA"] = 0;
		$_SESSION["PACOTECONFIGURACAO"] = 0;
	}
?>
<html>
	<head>
		<?php
			include 'head.php'; 
		?>
	</head>
	<body>
		<div class="container-fluid">
			<?php
				include 'topo.php';
				include 'criarbase.php';
			?>
		</div>	
	</body>
</html>
