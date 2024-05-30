<?php
	require 'conexao.php';
	require 'prefixo.php';
	include 'log.php';
	
	$id = $aftersavejs = $beforesavejs = $onloadjs = "";	
	
	if (isset($_GET["entidade"])){
		$id = $_GET["entidade"];
		$entidade = $id;
	}else{
		if (isset($_POST["id"])){
			$id = $_POST["id"];
		}else{
			$id = "";
		}
	}
	if (!empty($_POST)){
		$aftersavejs			 			= isset($_POST["aftersavejs"])?$_POST["aftersavejs"]:'';
		$beforesavejs			 			= isset($_POST["beforesavejs"])?$_POST["beforesavejs"]:'';
		$onloadjs			 				= isset($_POST["onloadjs"])?$_POST["onloadjs"]:'';
		$sql = 'UPDATE ' . PREFIXO . 'entidade SET aftersavejs = "'.tdc::utf8(trim(mysql_real_escape_string($aftersavejs))).'",beforesavejs = "'.tdc::utf8(trim(mysql_real_escape_string($beforesavejs))).'",onloadjs = "'.tdc::utf8(trim(mysql_real_escape_string($onloadjs))).'"  WHERE id = '.$id;		
		$query = $conn->query($sql);
		header("Location: criarAcoes.php?entidade=" . $id);
	}
	
	if ($id != ""){		
		$sql = "SELECT beforesavejs,aftersavejs,onloadjs FROM ".PREFIXO."entidade WHERE id = {$id};";		
		$query = $conn->query($sql);
		foreach ($query->fetchAll() as $linha){
			$aftersavejs = utf8_encode(trim($linha["aftersavejs"]));
			$beforesavejs = utf8_encode(trim($linha["beforesavejs"]));			
			$onloadjs = utf8_encode(trim($linha["onloadjs"]));
		}		
	}
?>
<html>
	<head>
		<title>Criar Ações</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
			window.onload = function(){
				var id = "<?=$id?>";
				document.getElementById("id").value = "<?=$id?>";
				//document.getElementById("aftersavejs").value = "<?=$aftersavejs?>";
				//document.getElementById("beforesavejs").value = "<?=$beforesavejs?>";
				//document.getElementById("onloadjs").value = escape("<?=$onloadjs?>");
			}
		</script>		
	</head>
	<body>
		<?php include 'menu_topo.php'; ?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-2">
					<?php include 'menu_entidade.php'; ?>
					<?php if ($id!="") include 'menu_atributo.php'; ?>
				</div>
				<div class="col-md-10">
					<form action="criarAcoes.php" method="post">
						<legend>
							Ações
						</legend>						
						<fieldset>
							<input type="hidden" id="id" name="id">
							<div class="form-group">
								<label for="aftersavejs">After Save</label> ( <small>Após salvar o registro.</small> ) - <b>JAVASCRIPT</b>
								<textarea name="aftersavejs" id="aftersavejs" class="form-control" rows="5"><?=$aftersavejs?></textarea>
							</div>
							<div class="form-group">
								<label for="beforesavejs">Before Save</label> ( <small>Antes de salvar o registro.</small> ) - <b>JAVASCRIPT</b>
								<textarea name="beforesavejs" id="beforesavejs" class="form-control" rows="5"><?=$beforesavejs?></textarea>
							</div>
							<div class="form-group">
								<label for="onloadjs">OnLoad</label> ( <small>Ao carregar página.</small> ) - <b>JAVASCRIPT</b>
								<textarea name="onloadjs" id="onloadjs" class="form-control" rows="5"><?=$onloadjs?></textarea>
							</div>							
							<button type="submit" class="btn btn-primary" >Salvar</button>							  
						</fieldset>	  
					</form>					
				</div>
			</div>
		</div>
	</body>
</html>