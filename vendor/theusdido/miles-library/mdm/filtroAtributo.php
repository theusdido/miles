<?php
require 'conexao.php';
require 'prefixo.php';
require 'funcoes.php';

	$entidade = isset($_GET["entidade"])?$_GET["entidade"]:(isset($_POST["entidade"])?isset($_POST["entidade"]):"");
	
	if (isset($_GET["op"])){
		if ($_GET["op"] == "carrega_fk_atributo"){
			$sql = "SELECT chaveestrangeira FROM td_atributo WHERE id = " . $_GET["atributo"];
			$query = $conn->query($sql);
			$linha = $query->fetch();
			
			$sqlOptions = "SELECT id,descricao FROM td_atributo WHERE entidade = " . $linha["chaveestrangeira"];
			$queryOptions = $conn->query($sqlOptions);
			while ($linhaOptions = $queryOptions->fetch()){
				echo '<option value="'.$linhaOptions["id"].'">'.utf8_encode($linhaOptions["descricao"]).'</option>';
			}
		}
		exit;
	}
	if (isset($_GET["excluir"])){
		$excluir = $_GET["excluir"];
		if ($excluir != ""){
			$sql = "DELETE FROM td_atributofiltro WHERE id = " . $excluir;
			$query = $conn->query($sql);
			if ($query){
				echo "<meta http-equiv='refresh' content='0;url=filtroAtributo.php?entidade=".$entidade."'>";
			}	
			exit;
		}		
	}
	
	if ($entidade == ""){
		echo 'Entidade não encontrada';
		exit;
	}else{
		$id = isset($_GET["id"])?$_GET["id"]:"";
		$atributos = isset($_GET["atributos"])?$_GET["atributos"]:"";
	}

	if (isset($_POST["bsalvar"])){

		$atributo = $_POST["atributo"];
		$campo = $_POST["campo"];
		$operador = $_POST["operador"];
		$valor = $_POST["valor"];
		$entidade = $_POST["entidade"];
		
		if($id == ""){
			$idRetorno = getProxId("atributofiltro",$conn);
			$sql = "INSERT INTO td_atributofiltro (id,entidade,atributo,campo,operador,valor) VALUES (".$idRetorno.",".$entidade.",".$atributo.",".$campo.",'".$operador."','".$valor."');";
		}else{
			$idRetorno = $id;
			$sql = "UPDATE td_atributofiltro SET entidade = ".$entidade.", atributo = ".$atributo.", campo = ".$campo.", operador = '".$operador."', valor = '".$valor."' WHERE id = " . $idRetorno;
		}
			
		$query = $conn->query($sql);
		if ($query){
			echo "<meta http-equiv='refresh' content='0;url=filtroAtributo.php?entidade=".$entidade."&id=".$idRetorno."'>";
			exit;
		}
	}
	
	$atributo = $campo = $operador = $valor = "";
	if ($id != ""){
		$sql = "SELECT * FROM td_atributofiltro WHERE id = " . $id;
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){
			$atributo = $linha["atributo"];
			$campo = $linha["campo"];
			$operador = $linha["operador"];
			$valor = $linha["valor"];
		}
	}	
?>
<html>
	<head>
		<title>Criar Filtro</title>
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
						<legend>
							Filtro
						</legend>					
						<input type="hidden" value="<?=$entidade?>" id="entidade" name="entidade" />
						<input type="hidden" value="<?=$id?>" id="id" name="id" />
						<div class="form-group">
							<label for="atributo">Atributo</label>
							<select id="atributo" name="atributo" class="form-control">
								<option value="">-- Selecione --</option>
								<?php
									$sql = "SELECT id,descricao FROM td_atributo WHERE entidade = ".$entidade." AND (tipohtml = 4 or tipohtml = 5 or tipohtml = 22) ORDER BY ordem ASC";
									$query = $conn->query($sql);
									While ($linha = $query->fetch()){
										echo "<option value='".$linha["id"]."'>".utf8_encode($linha["descricao"])."</option>";
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="campo">Campo</label>
							<select id="campo" name="campo" class="form-control">
								<?php
									$sql = "SELECT id,descricao FROM td_atributo WHERE entidade = ".$entidade." ORDER BY ordem ASC";
									$query = $conn->query($sql);
									While ($linha = $query->fetch()){
										echo "<option value='".$linha["id"]."'>".utf8_encode($linha["descricao"])."</option>";
									}
								?>							
							</select>
						</div>						
						<div class="form-group">
							<label for="operador">Operador</label>
							<select id="operador" name="operador" class="form-control">
								<option value="=">Igual</option>
								<option value="!">Diferente</option>
								<option value=">=">Maior Que</option>
								<option value="<=">Menor Que</option>
							</select>
						</div>
						<div class="form-group">
							<label for="valor">Valor</label>
							<input type="text" name="valor" id="valor" class="form-control">
						</div>						
						<input type="submit" value="Salvar" name="bsalvar" class="btn btn-primary">
					</form>
					<div class="list-group">
					<?php
						$sql = "SELECT * FROM td_atributofiltro WHERE entidade = " . $entidade;
						$query = $conn->query($sql);
						while ($linha = $query->fetch()){
							
							$sqlAtributo = "SELECT descricao FROM td_atributo WHERE id = " . $linha["atributo"];
							$queryAtributo = $conn->query($sqlAtributo);
							$linhaAtributo = $queryAtributo->fetch();
							
							$sqlCampo = "SELECT descricao FROM td_atributo WHERE id = " . $linha["campo"];
							$queryCampo = $conn->query($sqlCampo);
							$linhaCampo = $queryCampo->fetch();
							
							echo "<a class='list-group-item'>";
							echo "Atributo: <b>".utf8_encode($linhaAtributo["descricao"])."</b> - Campo: <b>".utf8_encode($linhaCampo["descricao"])."</b> Operador: <b>".$linha["operador"]."</b> Valor: <b>".$linha["valor"]."</b>";
							echo "	<button type='button' class='btn btn-default' onclick='excluirAtributoFiltro(".$linha["id"].",".$entidade.");' style='float:right;margin-top:-4px'>";
							echo "		<span class='fas fa-trash-alt' aria-hidden='true'></span>";
							echo "	</button>";
							echo "</a>";
						}
					?>
					</div>	
				</div>
			</div>
		</div>						
		<script language="JavaScript" type="text/javascript">
			function excluirAtributoFiltro(id,entidade){
				location.href='filtroAtributo.php?excluir='+id+'&entidade=' + entidade;
			}
			function carregaFKAtributo(atributo){
				$("#campo").load("filtroAtributo.php?op=carrega_fk_atributo&atributo=" + atributo);
			}
			$(document).ready(function(){
				carregaFKAtributo($("#atributo").val());
			});
			$("#atributo").change(function(){
				carregaFKAtributo(this.value);
			});
		</script>
	</body>
</html>