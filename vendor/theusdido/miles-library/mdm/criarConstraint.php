<?php
	require 'conexao.php';
	require 'prefixo.php';	
	include 'funcoes.php';
	
	$id = $tipo = $entidade = "";
	
	if (isset($_GET["entidade"])){
		$entidade = $_GET["entidade"];
	}
	$ent = $entidade; #usado para montar o menu
	
	if (isset($_GET["id"])){
		$id = $_GET["id"];
	}
?>
<html>
	<head>
		<title>Criar Relatório</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
			window.onload = function(){
				
			}
		</script>
	</head>
	<body>
		<?php 
			include 'menu_topo.php'; 			
		?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-2">
					<?php include 'menu_relatorio.php'; ?>
				</div>
				<div class="col-md-10">
					<!-- Aqui -->
					
				</div>
			</div>
		</div>
	</body>
</html>
