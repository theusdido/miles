<?php

	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);	

	require 'conexao.php';
	require 'prefixo.php';	
	require 'funcoes.php';
	
	$id = $tipo = $entidade = $atributo = $descricao = $ent = $entidadefilho = "";
	$exibirbotaoeditar = $exibirbotaoexcluir = $exibirbotaoemmassa = "";

	
	if (isset($_GET["entidade"])){
		$entidade = $_GET["entidade"];
	}
	$ent = $entidade; #usado para montar o menu
	
	if (isset($_GET["id"])){
		$id = $_GET["id"];
	}
	if (!empty($_POST)){
		if ($_POST["op"] == "salvar"){
			$id			 			= isset($_POST["id"])?$_POST["id"]:'';
			$descricao				= executefunction("utf8charset",array($_POST["descricao"]));
			$entidade	 			= $_POST["entidade"];
			$movimentacao	 		= isset($_POST["movimentacao"])?$_POST["movimentacao"]:0;			
			$exibirbotaoeditar		= isset($_POST["exibirbotaoeditar"])?1:0;
			$exibirbotaoexcluir		= isset($_POST["exibirbotaoexcluir"])?1:0;
			$exibirbotaoemmassa		= isset($_POST["exibirbotaoemmassa"])?1:0;

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."consulta");
				$prox = $query_prox->fetch();
				$id = $prox[0];

				$sql = "INSERT INTO ".PREFIXO."consulta (id,descricao,entidade,movimentacao,exibirbotaoeditar,exibirbotaoexcluir,exibirbotaoemmassa) VALUES ({$id},'{$descricao}',{$entidade},{$movimentacao},{$exibirbotaoeditar},{$exibirbotaoexcluir},{$exibirbotaoemmassa});";
			}else{
				$sql = "UPDATE ".PREFIXO."consulta SET entidade = {$entidade} , descricao = '{$descricao}' , movimentacao = {$movimentacao} , exibirbotaoeditar = {$exibirbotaoeditar} , exibirbotaoexcluir = {$exibirbotaoexcluir} , exibirbotaoemmassa = {$exibirbotaoemmassa} WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
				//addLog($sql);
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}

			header("Location: criarConsulta.php?id=" . $id . "&currentproject=" .$_SESSION['currentproject']);
		}
		if ($_POST["op"] == "salvarfiltro"){
			$id			 			= isset($_POST["id"])?$_POST["id"]:'';
			$operador				= $_POST["operador"];
			$atributo	 			= $_POST["atributo"];
			$consulta	 			= $_POST["consulta"];
			$legenda 				= $_POST["legenda"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."consultafiltro");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."consultafiltro (id,operador,atributo,consulta,legenda) VALUES ({$id},'{$operador}',{$atributo},{$consulta},'{$legenda}');";
			}else{
				$sql = "UPDATE ".PREFIXO."consultafiltro SET atributo = {$atributo} , consulta = {$consulta} , operador = '{$operador}' , legenda = '{$legenda}' WHERE id = {$id};";
			}			
			$query = $conn->query($sql);
			if($query){
				//addLog($sql);
				// Alterações no atributo
				$conn->query("UPDATE td_atributo SET legenda = '{$legenda}' WHERE id=" . $atributo);
				echo 1;
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}
			exit;
		}
		if ($_POST["op"] == "salvarfiltroinicial"){
			$id			 			= isset($_POST["id"])?$_POST["id"]:'';
			$operador				= $_POST["operador"];
			$atributo	 			= $_POST["atributo"];
			$consulta	 			= $_POST["consulta"];
			$legenda 				= $_POST["legenda"];
			$valor 					= $_POST["valor"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."consultafiltroinicial");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."consultafiltroinicial (id,operador,atributo,consulta,legenda,valor) VALUES ({$id},'{$operador}',{$atributo},{$consulta},'{$legenda}','{$valor}');";
			}else{
				$sql = "UPDATE ".PREFIXO."consultafiltroinicial SET atributo = {$atributo} , consulta = {$consulta} , operador = '{$operador}' , legenda = '{$legenda}' , valor = '{$valor}' WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
				//addLog($sql);
				// Alterações no atributo
				$conn->query("UPDATE td_atributo SET legenda = '{$legenda}' WHERE id=" . $atributo);
				echo 1;
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}
			exit;
		}
		if ($_POST["op"] == "salvarstatus"){
			$id			 			= isset($_POST["id"])?$_POST["id"]:'';
			$operador				= $_POST["operador"];
			$valor					= $_POST["valor"];
			$atributo	 			= $_POST["atributo"];
			$consulta	 			= $_POST["consulta"];
			$status 				= $_POST["status"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."consultastatus");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."consultastatus (id,operador,valor,atributo,consulta,status) VALUES ({$id},'{$operador}','{$valor}',{$atributo},{$consulta},{$status});";
			}else{
				$sql = "UPDATE ".PREFIXO."consultastatus SET atributo = {$atributo} , consulta = {$consulta} , valor = '{$valor}' , operador = '{$operador}' , status = {$status} WHERE id = {$id};";
			}
			echo $sql;
			$query = $conn->query($sql);
			if($query){
				//addLog($sql);				
				echo 1;
			}else{
				var_dump($conn->errorInfo());
			}
			exit;
		}
	}

	if (isset($_GET["op"])){
		
		if ($_GET["op"] == "excluirfiltro"){
			
			$sql = "DELETE FROM td_consultafiltro WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);

			$sql = "DELETE FROM td_atributo WHERE id = " . $_GET["atributo"];
			$query = $conn->query($sql);

			//addLog($sql);
			exit;
		}
		if ($_GET["op"] == "excluirfiltroinicial"){
			
			$sql = "DELETE FROM td_consultafiltroinicial WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);

			$sql = "DELETE FROM td_atributo WHERE id = " . $_GET["atributo"];
			$query = $conn->query($sql);

			//addLog($sql);
			exit;
		}
		if ($_GET["op"] == "excluirstatus"){
			
			$sql = "DELETE FROM td_consultastatus WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);

			//addLog($sql);
			exit;
		}
		
		if ($_GET["op"] == "listarconsulta"){
			$sql = "SELECT id,atributo atributo,operador,legenda FROM ".PREFIXO."consultafiltro a WHERE consulta = {$_GET["consulta"]} ORDER BY id DESC";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum campo de <strong>filtro</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo = $linha["atributo"];
				$operador = $linha["operador"];
				$legenda = executefunction("utf8charset",array($linha["legenda"]));
				$sqlAtributo = "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo = $conn->query($sqlAtributo);
				$linhaAtributo = $queryAtributo->fetch();
				$atributoDescricao = executefunction("utf8charset",array($linhaAtributo["descricao"]));
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>
						<button type='button' class='btn btn-default' onclick='excluirFiltro({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-idfiltro='{$linha["id"]}' data-legenda='{$linha["legenda"]}' onclick='editarFiltro({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
			exit;
		}

		if ($_GET["op"] == "listarfiltroinicial"){
			$sql = "SELECT id,atributo atributo,operador,legenda,valor FROM ".PREFIXO."consultafiltroinicial a WHERE consulta = {$_GET["consulta"]} ORDER BY id DESC";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum campo de <strong>filtro</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo = $linha["atributo"];
				$operador = $linha["operador"];
				$legenda = executefunction("utf8charset",array($linha["legenda"]));
				$sqlAtributo = "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo = $conn->query($sqlAtributo);
				$linhaAtributo = $queryAtributo->fetch();
				$atributoDescricao = executefunction("utf8charset",array($linhaAtributo["descricao"]));
				$valor = $linha["valor"];
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>
						<button type='button' class='btn btn-default' onclick='excluirFiltroInicial({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-idfiltro='{$linha["id"]}' data-legenda='{$linha["legenda"]}' data-valor='{$valor}' onclick='editarFiltroInicial({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
			exit;
		}
		if ($_GET["op"] == "listarstatus"){
			$sql = "SELECT id,atributo atributo,operador,valor,status FROM ".PREFIXO."consultastatus a WHERE consulta = {$_GET["consulta"]}";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum filtro de <strong>status</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo = $linha["atributo"];
				$operador = $linha["operador"];
				$valor = $linha["valor"];
				$sqlAtributo = "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo = $conn->query($sqlAtributo);
				$linhaAtributo = $queryAtributo->fetch();
				$atributoDescricao = executefunction("utf8charset",array($linhaAtributo["descricao"]));
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>. Valor: <small class='text-info'>{$valor}</small>.
						<button type='button' class='btn btn-default' onclick='excluirStatus({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-valor='{$valor}' data-idstatus='{$linha["id"]}' data-status='{$linha["status"]}' onclick='editarStatus({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
			exit;
		}		
	}
	if ($id != ""){
		$sql = "SELECT descricao,entidade,movimentacao,exibirbotaoeditar,exibirbotaoexcluir,exibirbotaoemmassa FROM ".PREFIXO."consulta WHERE id = {$id}";
		$query = $conn->query($sql);
		foreach ($query->fetchAll() as $linha){
			$entidade			= $linha["entidade"];
			$descricao			= executefunction("utf8charset",array($linha["descricao"]));
			$movimentacao		= $linha["movimentacao"];
			$exibirbotaoeditar 	= $linha["exibirbotaoeditar"];
			$exibirbotaoexcluir = $linha["exibirbotaoexcluir"];
			$exibirbotaoemmassa = $linha["exibirbotaoemmassa"];
		}
	}
?>
<html>
	<head>
		<title>Criar Consulta</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
			window.onload = function(){
				document.getElementById("id").value = "<?=$id?>";
				if ("<?=$id?>" != ""){
					$("#accordion_filtros").show();
					$("#descricao").val("<?=$descricao?>");
					$("#entidade").val("<?=$entidade?>");
					document.getElementById("exibirbotaoeditar").checked 	= (<?=(int)$exibirbotaoeditar?>==0)?false:true;
					document.getElementById("exibirbotaoexcluir").checked 	= (<?=(int)$exibirbotaoexcluir?>==0)?false:true;
					document.getElementById("exibirbotaoemmassa").checked 	= (<?=(int)$exibirbotaoemmassa?>==0)?false:true;
				}else{
					$("#accordion_filtros").hide();
					document.getElementById("exibirbotaoeditar").checked 	= false;
					document.getElementById("exibirbotaoexcluir").checked 	= false;
					document.getElementById("exibirbotaoemmassa").checked 	= false;
				}
				$("#valor").val("");
				atualizarListaFiltro("<?=$id?>");
				atualizarListaStatus("<?=$id?>");
				atualizarListaFiltroInicial("<?=$id?>");
			}
			function validar(){
				if ($("#entidade").val() == "" || $("#entidade").val() == null){
					alert('Entidade não pode ser vazio');
					return false;
				}
				return true;
			}
			function editarFiltro(id){
				$("#form-filtro #consulta").val(id);
				$("#form-filtro #atributo").val($("#lista-filtro #atributo-editar-" + id).data("atributo"));
				$("#form-filtro #operador").val($("#lista-filtro #atributo-editar-" + id).data("operador"));
				$("#form-filtro #legenda").val($("#lista-filtro #atributo-editar-" + id).data("legenda"));
				$("#form-filtro #idfiltro").val($("#lista-filtro #atributo-editar-" + id).data("idfiltro"));
				$("#modalCadastroFiltro").modal('show');
			}
			function editarFiltroInicial(id){
				$("#form-filtro-inicial #consulta").val(id);
				$("#form-filtro-inicial #atributo").val($("#lista-filtroinicial #atributo-editar-" + id).data("atributo"));
				$("#form-filtro-inicial #operador").val($("#lista-filtroinicial #atributo-editar-" + id).data("operador"));
				$("#form-filtro-inicial #valor").val($("#lista-filtroinicial #atributo-editar-" + id).data("valor"));
				$("#form-filtro-inicial #legenda").val($("#lista-filtroinicial #atributo-editar-" + id).data("legenda"));
				$("#form-filtro-inicial #idfiltro").val($("#lista-filtroinicial #atributo-editar-" + id).data("idfiltro"));
				$("#modalCadastroFiltroInicial").modal('show');
			}			
			function editarStatus(id){
				$("#form-status #consulta").val(id);
				$("#form-status #atributo").val($("#lista-status #atributo-editar-" + id).data("atributo"));
				$("#form-status #operador").val($("#lista-status #atributo-editar-" + id).data("operador"));
				$("#form-status #valor").val($("#lista-status #atributo-editar-" + id).data("valor"));
				$("#form-status #status").val($("#lista-status #atributo-editar-" + id).data("status"));
				$("#form-status #idstatus").val($("#lista-status #atributo-editar-" + id).data("idstatus"));
				$("#modalCadastroStatus").modal('show');
			}
			$(document).ready(function(){
				$("#movimentacao").val("<?=isset($movimentacao)?$movimentacao:0?>");
				$("#salvarFiltro").click(function(){
					$.ajax({
						type:"POST",
						url:"criarConsulta.php",
						data:{
							op:"salvarfiltro",
							operador: $("#form-filtro #operador").val(),
							atributo: $("#form-filtro #atributo").val(),
							consulta:"<?=$id?>",
							id:$("#form-filtro #idfiltro").val(),
							legenda:$("#form-filtro #legenda").val(),
							currentproject:<?=$_SESSION["currentproject"]?>
						},
						complete:function(r){
							atualizarListaFiltro("<?=$id?>");
							$("#modalCadastroFiltro").modal('hide');
						}
					});
				});
				$("#salvarFiltroInicial").click(function(){
					$.ajax({
						type:"POST",
						url:"criarConsulta.php",
						data:{
							op:"salvarfiltroinicial",
							operador: $("#form-filtro-inicial #operador").val(),
							atributo: $("#form-filtro-inicial #atributo").val(),
							consulta:"<?=$id?>",
							id:$("#form-filtro-inicial #idfiltro").val(),
							valor:$("#form-filtro-inicial #valor").val(),
							legenda:$("#form-filtro-inicial #legenda").val(),
							currentproject:<?=$_SESSION["currentproject"]?>
						},
						complete:function(r){
							atualizarListaFiltroInicial("<?=$id?>");
							$("#modalCadastroFiltroInicial").modal('hide');
						}
					});
				});				
				$("#salvarStatus").click(function(){
					$.ajax({
						type:"POST",
						url:"criarConsulta.php",
						data:{
							op:"salvarstatus",
							operador: $("#form-status #operador").val(),
							valor: $("#form-status #valor").val(),
							atributo: $("#form-status #atributo").val(),
							consulta:"<?=$id?>",
							id:$("#form-status #idstatus").val(),
							status:$("#form-status #status").val(),
							currentproject:<?=$_SESSION["currentproject"]?>
						},
						complete:function(r){
							atualizarListaStatus("<?=$id?>");
							$("#modalCadastroStatus").modal('hide');
						}
					});
				});
				$("#form-status #atributo").change(function(){
					carregarValoresAtributo($(this).find("option:selected").data("chaveestrangeira"));
				});
				carregarValoresAtributo($("#form-status #atributo").find("option:selected").data("chaveestrangeira"));
			});
			function novoFiltro(){
				$("#modalCadastroFiltro").modal({
					backdrop:false
				});
				$("#modalCadastroFiltro").modal('show');
				$("#form-filtro #consulta,#form-filtro #idfiltro,#form-filtro #legenda").val("");
				$("#form-filtro #operador").val("=");
				$("#form-filtro #atributo").val($("#form-filtro #atributo option:first").val());
			}
			function novoFiltroInicial(){
				$("#modalCadastroFiltroInicial").modal({
					backdrop:false
				});
				$("#modalCadastroFiltro").modal('show');
				$("#form-filtro #consulta,#form-filtro #idfiltro,#form-filtro #legenda").val("");
				$("#form-filtro #operador").val("=");
				$("#form-filtro #atributo").val($("#form-filtro #atributo option:first").val());
			}			
			function novoStatus(){
				$("#modalCadastroStatus").modal({
					backdrop:false
				});
				$("#modalCadastroStatus").modal('show');				
				$("#form-status #consulta,#form-status #valor,#form-status #idstatus").val("");
				$("#form-status #operador").val("=");
				$("#form-status #atributo").val($("#form-status #atributo option:first").val());
				$("#form-status #status").val($("#form-status #status option:first").val());
			}
			function atualizarListaFiltro(consulta){
				$("#lista-filtro").load("criarConsulta.php?op=listarconsulta&consulta=" + consulta + "&currentproject=<?=$_SESSION['currentproject']?>");
			}
			function atualizarListaFiltroInicial(consulta){
				$("#lista-filtroinicial").load("criarConsulta.php?op=listarfiltroinicial&consulta=" + consulta+ "&currentproject=<?=$_SESSION['currentproject']?>");
			}
			function atualizarListaStatus(consulta){
				$("#lista-status").load("criarConsulta.php?op=listarstatus&consulta=" + consulta + "&currentproject=<?=$_SESSION['currentproject']?>");
			}
			function excluirFiltro(id){
				$.ajax({
					url:"criarConsulta.php",
					data:{
						op:"excluirfiltro",
						id:id,
						currentproject:<?=$_SESSION['currentproject']?>
					},
					complete:function(){
						atualizarListaFiltro("<?=$id?>");
					}
				});
			}
			function excluirFiltroInicial(id){
				$.ajax({
					url:"criarConsulta.php",
					data:{
						op:"excluirfiltroinicial",
						id:id,
						currentproject:<?=$_SESSION['currentproject']?>
					},
					complete:function(){
						atualizarListaFiltroInicial("<?=$id?>");
					}
				});
			}			
			function excluirStatus(id){
				$.ajax({
					url:"criarConsulta.php",
					data:{
						op:"excluirstatus",
						id:id,
						currentproject:<?=$_SESSION['currentproject']?>
					},
					complete:function(){
						atualizarListaStatus("<?=$id?>");
					}
				});
			}
			function carregarValoresAtributo(fk){
				if (fk > 0){
					$(".form-control[data-tipoatributo=lista]").attr("id","valor");
					$(".form-control[data-tipoatributo=lista]").attr("name","valor");
					$(".form-control[data-tipoatributo=lista]").show();
					$(".form-control[data-tipoatributo=input]").attr("id","");
					$(".form-control[data-tipoatributo=input]").attr("name","");
					$(".form-control[data-tipoatributo=input]").hide();
					
					$.ajax({
						url:"../../index.php?controller=requisicoes",
						type:"GET",
						data:{
							op:"carregar_options",
							entidade:fk,
							atributo:"",
							filtro:"",
							currentproject:<?=$_SESSION['currentproject']?>,
							key:"k"
						},
						complete:function(ret){
							$("#form-status #valor").html(ret.responseText);
						}
					});
					
				}else{
					$(".form-control[data-tipoatributo=input]").attr("id","valor");
					$(".form-control[data-tipoatributo=input]").attr("name","valor");
					$(".form-control[data-tipoatributo=input]").show();
					$(".form-control[data-tipoatributo=lista]").attr("id","");
					$(".form-control[data-tipoatributo=lista]").attr("name","");
					$(".form-control[data-tipoatributo=lista]").hide();
				}
				
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
					<?php include 'menu_consulta.php'; ?>
				</div>
				<div class="col-md-10">
					<form action="criarConsulta.php" method="post" onsubmit="return validar();">
						<legend>
							Consulta
						</legend>						
						<fieldset>
							<input type="hidden" id="id" name="id">
							<input type="hidden" id="op" name="op" value="salvar">
							<input type="hidden" id="currentproject" name="currentproject" value="<?=$_SESSION['currentproject']?>" />
							<div class="form-group">
								<label for="nome">Descri&ccedil;&atilde;o</label>
								<input type="text" name="descricao" id="descricao" class="form-control">
							</div>
							<div class="form-group">
								<label for="entidade">Entidade</label>
								<select id="entidade" name="entidade" class="form-control">
									<?php 
										$sql = "SELECT id,nome,descricao FROM ".PREFIXO."entidade";
										$query = $conn->query($sql);
										foreach($query->fetchAll() as $linha){
											echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'">'.executefunction("utf8charset",array($linha["descricao"])).' [ '.$linha["nome"].' ]</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="movimentacao">Movimentação</label>
								<select id="movimentacao" name="movimentacao" class="form-control">
									<option value="0" >-- Selecione --</option>
									<?php
										$sql = "SELECT id,descricao FROM ".PREFIXO."movimentacao";
										$query = $conn->query($sql);
										foreach($query->fetchAll() as $linha){
											echo '<option value="'.$linha["id"].'" >'.$linha["descricao"].'</option>';
										}
									?>
								</select>
							</div>
							<div class="checkbox">
								<label for="exibirbotaoeditar">
									<input type="checkbox" name="exibirbotaoeditar" id="exibirbotaoeditar">Exibir botão <b>Editar</b>
								</label>
							</div>
							<div class="checkbox">
								<label for="exibirbotaoexcluir">
									<input type="checkbox" name="exibirbotaoexcluir" id="exibirbotaoexcluir">Exibir botão <b>Excluir</b>
								</label>
							</div>
							<div class="checkbox">
								<label for="exibirbotaoemmassa">
									<input type="checkbox" name="exibirbotaoemmassa" id="exibirbotaoemmassa">Exibir botão <b>Em Massa</b>
								</label>
							</div>
							<div id="error"></div>
							<button type="submit" class="btn btn-primary" >Salvar</button>
						</fieldset>
					</form>
					
					<?php
						if ($id != ""){
					?>		
					<!-- FILTROS -->
					<div class="panel-group" id="accordion_filtros" role="tablist" aria-multiselectable="true">
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
						  <h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion_filtros" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
							  Filtros							  
							</a>
						  </h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						  <div class="panel-body">
								<button type="button" class="btn btn-default" aria-label="Novo Filtro" style="float:right;margin-top:-3px;" onclick="novoFiltro();">
								  <span class="fas fa-plus-circle" aria-hidden="true"></span>
								</button>

								<!-- CADASTRO DE FILTRO -->
								<div class="modal fade" id="modalCadastroFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								  <div class="modal-dialog" role="document">
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Cadastro de Filtro</h4>
									  </div>
									  <div class="modal-body">

											<!-- FORMULARIO DE FILTRO -->
											<form id="form-filtro" action="criarConsulta.php" method="post">
												<fieldset>
													<input type="hidden" id="idfiltro" name="idfiltro" />
													<input type="hidden" id="op" name="op" value="salvarfiltro" />
													<input type="hidden" id="consulta" name="consulta" value="<?=$id?>" />
													<input type="hidden" id="currentproject" name="currentproject" value="<?=$_SESSION['currentproject']?>" />
													<div class="form-group">
														<label for="atributo">Atributo</label>
														<select id="atributo" name="atributo" class="form-control">
														<?php 
															$sql = "SELECT id,nome,descricao FROM ".PREFIXO."atributo WHERE entidade = " . $entidade;
															$query = $conn->query($sql);
															foreach($query->fetchAll() as $linha){
																echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'">'.executefunction("utf8charset",array($linha["descricao"])).' [ '.$linha["nome"].' ]</option>';
															}
														?>
														</select>
													</div>
													<div class="form-group">
														<label for="operador">Operador</label>
														<select name="operador" id="operador" class="form-control">
															<option value="=">Igual</option>
															<option value="!">Diferente</option>
															<option value="..">Intervalo</option>
															<option value="%">Parcial</option>
															<option value=">">Maior</option>
															<option value="<">Menor</option>
															<option value=">=">Maior ou Igual </option>
															<option value="<=">Menor ou Igual</option>
															<option value="-">Nulo</option>
														</select>
													</div>
													<div class="form-group">
														<label for="legenda">Legenda</label>
														<input type="text" name="legenda" id="legenda" class="form-control">
													</div>
												</fieldset>
											</form>
									  
									  </div>
									  <div class="modal-footer">
											<div id="error"></div>
											<button type="button" class="btn btn-primary" id="salvarFiltro">Salvar</button>
									  </div>
									</div>
								  </div>
								</div>
								<!-- CADASTRO DE FILTRO -->
								<br/><br/>
								<div id="lista-filtro" class="list-group">
								</div>
						  </div>
						</div>
					  </div>
					</div>

					<!-- STATUS -->
					<div class="panel-group" id="accordion_status" role="tablist" aria-multiselectable="true">
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
						  <h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion_status" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							  Status							  
							</a>
						  </h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
						  <div class="panel-body">
								<button type="button" class="btn btn-default" aria-label="Novo Filtro" style="float:right;margin-top:-3px;" onclick="novoStatus();">
								  <span class="fas fa-plus-circle" aria-hidden="true"></span>
								</button>
								
								<!-- CADASTRO DE STATUS -->
								<div class="modal fade" id="modalCadastroStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								  <div class="modal-dialog" role="document">
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Cadastro Status</h4>
									  </div>
									  <div class="modal-body">
									  
											<form id="form-status" action="criarConsulta.php" method="post">
												<fieldset>
													<input type="hidden" id="idstatus" name="idstatus" />
													<input type="hidden" id="op" name="op" value="salvarfiltro" />
													<input type="hidden" id="consulta" name="consulta" value="<?=$id?>" />
													<input type="hidden" id="currentproject" name="currentproject" value="<?=$_SESSION['currentproject']?>" />													
													<div class="form-group">
														<label for="atributo">Atributo</label>
														<select id="atributo" name="atributo" class="form-control">
														<?php 
															$sql = "SELECT id,nome,descricao,chaveestrangeira FROM ".PREFIXO."atributo WHERE entidade = " . $entidade ;
															$query = $conn->query($sql);
															foreach($query->fetchAll() as $linha){
																echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'" data-chaveestrangeira="'.$linha["chaveestrangeira"].'">'.executefunction("utf8charset",array($linha["descricao"])).' [ '.$linha["nome"].' ]</option>';
															}
														?>
														</select>
													</div>
													<div class="form-group">
														<label for="operador">Operador</label>
														<select name="operador" id="operador" class="form-control">
															<option value="=">Igual</option>
															<option value="!">Diferente</option>
															<option value="..">Intervalo</option>
															<option value="%">Parcial</option>
															<option value=">">Maior</option>
															<option value="<">Menor</option>
															<option value=">=">Maior ou Igual </option>
															<option value="<=">Menor ou Igual</option>
														</select>
													</div>
													<div class="form-group">
														<label for="valor">Valor</label>
														<input type="text" name="" id="" class="form-control" data-tipoatributo="input">
														<select name="" id="" class="form-control" data-tipoatributo="lista">
														</select>														
													</div>
													<div class="form-group">
														<label for="status">Status</label>
														<select id="status" name="status" class="form-control">
														<?php 
															$sql = "SELECT id,descricao FROM ".PREFIXO."status";
															$query = $conn->query($sql);
															foreach($query->fetchAll() as $linha){
																echo '<option value="'.$linha["id"].'">'.executefunction("utf8charset",array($linha["descricao"])).'</option>';
															}
														?>
														</select>
													</div>
												</fieldset>
											</form>
									  </div>
									  <div class="modal-footer">
											<div id="error"></div>
											<button type="button" class="btn btn-primary" id="salvarStatus">Salvar</button>
									  </div>
									</div>
								  </div>
								</div>
								<br/><br/>
								<div id="lista-status" class="list-group">
								</div>								
						  </div>
						</div>
					  </div>
					</div>


					<!-- FILTROS INICIAIS -->
					<div class="panel-group" id="accordion_filtrosinicias" role="tablist" aria-multiselectable="true">
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingThree">
						  <h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion_filtrosiniciais" href="#collapseTrhee" aria-expanded="false" aria-controls="collapseTrhee">
							  Filtros Iniciais						  
							</a>
						  </h4>
						</div>
						<div id="collapseTrhee" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
						  <div class="panel-body">
								<button type="button" class="btn btn-default" aria-label="Novo Filtro" style="float:right;margin-top:-3px;" onclick="novoFiltroInicial();">
								  <span class="fas fa-plus-circle" aria-hidden="true"></span>
								</button>

								<!-- CADASTRO DE FILTRO -->
								<div class="modal fade" id="modalCadastroFiltroInicial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								  <div class="modal-dialog" role="document">
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Cadastro de Filtro Inicial</h4>
									  </div>
									  <div class="modal-body">

											<!-- FORMULARIO DE FILTRO -->
											<form id="form-filtro-inicial" action="criarConsulta.php" method="post">
												<fieldset>
													<input type="hidden" id="idfiltro" name="idfiltro" />
													<input type="hidden" id="op" name="op" value="salvarfiltroinicial" />
													<input type="hidden" id="consulta" name="consulta" value="<?=$id?>" />
													<input type="hidden" id="currentproject" name="currentproject" value="<?=$_SESSION['currentproject']?>" />													
													<div class="form-group">
														<label for="atributo">Atributo</label>
														<select id="atributo" name="atributo" class="form-control">
														<?php 
															$sql = "SELECT id,nome,descricao FROM ".PREFIXO."atributo WHERE entidade = " . $entidade;
															$query = $conn->query($sql);
															foreach($query->fetchAll() as $linha){
																echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'">'.executefunction("utf8charset",array($linha["descricao"])).' [ '.$linha["nome"].' ]</option>';
															}
														?>
														</select>
													</div>
													<div class="form-group">
														<label for="operador">Operador</label>
														<select name="operador" id="operador" class="form-control">
															<option value="=">Igual</option>
															<option value="!">Diferente</option>
															<option value="..">Intervalo</option>
															<option value="%">Parcial</option>
															<option value=",">Contém</option>
															<option value=">">Maior</option>
															<option value="<">Menor</option>
															<option value=">=">Maior ou Igual </option>
															<option value="<=">Menor ou Igual</option>
														</select>
													</div>
													<div class="form-group">
														<label for="valor">Valor</label>
														<input type="text" name="valor" id="valor" class="form-control">
													</div>													
													<div class="form-group">
														<label for="legenda">Legenda</label>
														<input type="text" name="legenda" id="legenda" class="form-control">
													</div>
												</fieldset>
											</form>
									  
									  </div>
									  <div class="modal-footer">
											<div id="error"></div>
											<button type="button" class="btn btn-primary" id="salvarFiltroInicial">Salvar</button>
									  </div>
									</div>
								  </div>
								</div>
								<!-- CADASTRO DE FILTRO -->
								<br/><br/>
								<div id="lista-filtroinicial" class="list-group">
								</div>
						  </div>
						</div>
					  </div>
					</div>
					
					<?php
						}
					?>	
				</div>
			</div>
		</div>
	</body>
</html>
