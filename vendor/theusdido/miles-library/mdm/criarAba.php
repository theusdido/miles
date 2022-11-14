<?php

	require 'conexao.php';
	include 'funcoes.php';
	require 'prefixo.php';
	
	$entidade  = isset($_GET["entidade"])?$_GET["entidade"]:$_POST["entidade"];
	
	if (isset($_GET["excluir"])){
		$sql = "DELETE FROM ".PREFIXO."abas WHERE id = {$_GET["excluir"]}";
		$query = $conn->query($sql);
		if ($query){
			header("Location: criarAba.php?entidade={$entidade}&currentproject=".$_SESSION['currentproject']);
		}
		exit;
	}

	if (isset($_POST["bsalvar"])){
		// ID Último atributo
		$query_ultimo = $conn->query("SELECT IFNULL(MAX(id),0)+1 id FROM ".PREFIXO."abas");
		$linha_ultimo = $query_ultimo->fetch();
		$idRetorno = $linha_ultimo["id"];
				
		$descricao = executefunction("tdc::utf8",array($_POST["descricao"]));
		$atributos = implode(",",$_POST["atributos"]);
		if ($_POST["id"] == ""){
			$sql = "INSERT INTO ".PREFIXO."abas (id,entidade,descricao,atributos) values ({$idRetorno},{$entidade},'{$descricao}','{$atributos}');";
			$id = $idRetorno;
		}else{
			$id = $_POST["id"];
			$sql = "UPDATE ".PREFIXO."abas SET descricao = '{$descricao}' , atributos = '{$atributos}' WHERE id = {$id};";
		}
		$query = $conn->query($sql);
		if ($query){
			header('Location: criarAba.php?entidade='.$entidade."&currentproject=".$_SESSION['currentproject']);
		}else{
			if (IS_SHOW_ERROR_MESSAGE){
				echo $sql;
				var_dump($conn->errorInfo());
			}
		}
	}
	if (isset($_GET["id"])){
		$id = $_GET["id"];
		$sql = "SELECT descricao FROM ".PREFIXO."abas WHERE id = {$id}";
		$query = $conn->query($sql);
		$linha = $query->fetch();
		$descricao = executefunction("tdc::utf8",array($linha[0]));
	}else{
		$id = "";
		$descricao = "";
	}
?>
<html>
	<head>
		<title>Criar Aba</title>
		<?php include 'head.php' ?>
	</head>
	<body>
		<?php include 'menu_topo.php'; ?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-2">
					<?php include 'menu_entidade.php'; ?>
					<?php if ($entidade!="") include 'menu_atributo.php'; ?>
				</div>
				<div class="col-md-10">
	
					<form action="?" method="post">
						<input type="hidden" value="<?=$entidade?>" id="entidade" name="entidade" />
						<input type="hidden" value="<?=$id?>" id="id" name="id" />
						<input type="hidden" value="<?=$_SESSION["currentproject"]?>" id="currentproject" name="currentproject" />
						<div class="form-group">
							<label for="descricao">Descri&ccedil;&atilde;o</label>
							<input type="text" id="descricao" name="descricao" value="<?=$descricao?>" class="form-control">
						</div>
						<div class="form-group">						
							<label for="atributos">Atributos</label>
							<select id="atributos" name="atributos[]" multiple size="5" class="form-control">
								<?php
									// Relacionamento por Generalização
									$sql = "SELECT filho FROM ".PREFIXO."relacionamento WHERE pai = {$entidade} and tipo=3";
									$query = $conn->query($sql);
									$superclasse = "";
									if ($query){
										foreach($query->fetchAll() as $linha){
											$superclasse = " OR entidade = " . $linha["filho"];
										}	
									}
									
									$sql = "SELECT id,descricao,nome FROM ".PREFIXO."atributo WHERE entidade = {$entidade} ORDER BY ordem ASC,id ASC";
									$query = $conn->query($sql);
									foreach($query->fetchAll() as $linha){
										$selected = "";
										if (isset($_GET["atributos"])){
											$attr = explode(",",$_GET["atributos"]);
											foreach ($attr as $a){
												if ($a == $linha["id"]){
													$selected = "selected=''";
												}
											}
										}
										$descricao = executefunction("tdc::utf8",array($linha['descricao']));
										$nome = executefunction("tdc::utf8",array($linha['nome']));
										echo "<option value='{$linha['id']}' {$selected}>{$descricao} [ {$nome} ]</option>";
									}
								?>
							</select>
						</div>						
						<input type="submit" value="Salvar" name="bsalvar" class="btn btn-primary">
					</form>
					<div class="list-group">
					<?php
						$sql = "SELECT id,descricao,atributos FROM ".PREFIXO."abas WHERE entidade = {$entidade}";
						$query = $conn->query($sql);
						foreach($query->fetchAll() as $linha){
							$descricao = executefunction("tdc::utf8",array($linha['descricao']));
							echo "<a class='list-group-item btn-link-excluir-aba'>
									<label style='cursor:pointer' onclick='editarAba({$entidade},{$linha['id']},\"{$linha['atributos']}\");'>{$descricao}</label>
									<button type='button' class='btn btn-default' onclick='excluirAba({$linha['id']},{$entidade});' style='float:right;margin-top:-4px'>
										<span class='fas fa-trash-alt' aria-hidden='true'></span>
									</button>																			
								</a>";
						}
						
					?>
					</div>	
				</div>
			</div>
		</div>						
		<script language="JavaScript" type="text/javascript">
			$(".btn-link-excluir-aba").click(function(e){
				e.stopPropagation();
				e.preventDefault();
			});
			function excluirAba(id,entidade){				
				location.href='criarAba.php?excluir='+id+'&entidade=' + entidade + "&currentproject=<?=$_SESSION['currentproject']?>";
			}
			function editarAba(entidade,id,atributos){
				location.href='criarAba.php?entidade=' + entidade + "&id=" + id + "&atributos=" + atributos + "&currentproject=<?=$_SESSION['currentproject']?>";
			}
		</script>		
	</body>
</html>	