<?php
	require 'conexao.php';	
	require 'prefixo.php';
	require 'funcoes.php';
?>
<html>
	<head>
		<title>HTML Code</title>
		<?php include 'head.php' ?>
		<style type="text/css">
			#pagina-gerada{
				border:3px solid #EEE;
				float:left;
				width:100%;
				padding:15px;				
			}
		</style>
		<script type="text/javascript" src="../../lib/jquery/jquery.mask.js"></script>
		<script type="text/javascript" src="../../lib/jquery/jquery.maskMoney.js"></script>
	</head>
	<body>
		<?php include 'menu_topo.php'; ?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-2">
					<!-- Menu -->
				</div>
				<div class="col-md-10">
					<form>
						<legend>
							Template
						</legend>						
						<fieldset>
							<input type="hidden" id="id" name="id">
							<div class="form-group">
								<button id="gerar" name="gerar" type="button" class="btn btn-primary" style="float:right;margin-bottom:5px;width:10%;">Baixar</button>
								<div id="gravando-pagina"></div>
								<input type="text" id="urltemplate" name="urltemplate" class="form-control" style="float:right;width:89%;margin-right:1%;"/>
								<div id="pagina-gerada"></div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	$("#gerar").click(function(){
		$.ajax({
			url:"../../index.php",
			data:{
				controller:"website/importar",
				url:$("#urltemplate").val(),
				currentproject:"<?=$_SESSION["currentproject"]?>"
			},
			complete:function(retorno){
				$("#pagina-gerada").html("Terminou");
			},
			beforeSend:function(){
				$("#pagina-gerada").html('<img src="../tema/padrao/loading2.gif" id="loading" style="float:left;margin-left:48%;" />');
			}
		});
	});
</script>