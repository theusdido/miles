<?php
	require 'conexao.php';
	require 'prefixo.php';
	require 'funcoes.php';
	
	$id = $tipo = $entidade = $atributo = $descricao = $ent = $entidadefilho = $motivo = $exigirobrigatorio = "";
	$exibirvaloresantigos = '';
	
	if (isset($_GET["op"])){
		if ($_GET["op"] == "lista_atributos"){
			$sql = "SELECT id,descricao,nome FROM ".PREFIXO."atributo WHERE entidade = " . $_GET["entidade"];
			$query = $conn->query($sql);
			foreach($query->fetchAll() as $linha){
				$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
				echo '<option value="'.$linha["id"].'">'.$descricao.' [ '.$linha["nome"].' ]</option>';
			}
			exit;
		}
	}	
	
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
			$descricao				= $_POST["descricao"];
			$entidade	 			= $_POST["entidade"];
			$motivo					= $_POST["motivo"];
			$exigirobrigatorio		= isset($_POST["exigirobrigatorio"])?1:0;;
			$exibirtitulo			= isset($_POST["exibirtitulo"])?1:0;;
			$exibirvaloresantigos	= isset($_POST["exibirvaloresantigos"])?1:0;

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."movimentacao");
				$prox 		= $query_prox->fetch();
			 	$id 		= $prox[0];
			 	$sql 		= "INSERT INTO ".PREFIXO."movimentacao (id,descricao,entidade,motivo,exigirobrigatorio,exibirtitulo,exibirvaloresantigos) VALUES ({$id},'{$descricao}',{$entidade},{$motivo},{$exigirobrigatorio},{$exibirtitulo},{$exibirvaloresantigos});";
			}else{
			 	$sql 		= "UPDATE ".PREFIXO."movimentacao SET entidade = {$entidade} , descricao = '{$descricao}' , motivo = {$motivo} , exigirobrigatorio = {$exigirobrigatorio} , exibirtitulo = {$exibirtitulo} , exibirvaloresantigos = {$exibirvaloresantigos} WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
				addLog($sql);
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
			 		var_dump($conn->errorInfo());
				}
			}

			header("Location: criarMovimentacao.php?id=" . $id);
		}
		if ($_POST["op"] == "salvaralterar"){
			$id			 			= isset($_POST["id"])?$_POST["id"]:'';
			$atributo	 			= $_POST["atributo"];
			$movimentacao 			= $_POST["movimentacao"];
			$legenda 				= $_POST["legenda"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."movimentacaoalterar");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."movimentacaoalterar (id,atributo,movimentacao,legenda) VALUES ({$id},{$atributo},{$movimentacao},'{$legenda}');";
			}else{
				$sql = "UPDATE ".PREFIXO."movimentacaoalterar SET atributo = {$atributo} , movimentacao = {$movimentacao} , legenda = '{$legenda}' WHERE id = {$id};";
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
			$movimentacao	 		= $_POST["movimentacao"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."movimentacaostatus");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."movimentacaostatus (id,operador,valor,atributo,movimentacao) VALUES ({$id},'{$operador}','{$valor}',{$atributo},{$movimentacao});";
			}else{
				$sql = "UPDATE ".PREFIXO."movimentacaostatus SET atributo = {$atributo} , movimentacao = {$movimentacao} , valor = '{$valor}' , operador = '{$operador}' WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
				//addLog($sql);				
				echo 1;
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}
			exit;
		}
		if ($_POST["op"] == "salvarhistorico"){
			$id			 			= isset($_POST["id"])?$_POST["id"]:'';
			$legenda				= $_POST["legenda"];
			$atributo	 			= $_POST["atributo"];
			$movimentacao	 		= $_POST["movimentacao"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."movimentacaohistorico");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."movimentacaohistorico (id,atributo,movimentacao,legenda) VALUES ({$id},{$atributo},{$movimentacao},'{$legenda}');";
			}else{
				$sql = "UPDATE ".PREFIXO."movimentacaohistorico SET atributo = {$atributo} , movimentacao = {$movimentacao} , legenda = '{$legenda}' WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
				//addLog($sql);		
				echo 1;
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}
			exit;
		}		
	}

	if (isset($_GET["op"])){
		
		if ($_GET["op"] == "excluiralterar"){
			
			$sql = "DELETE FROM td_movimentacaoalterar WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);

			$sql = "DELETE FROM td_atributo WHERE id = " . $_GET["atributo"];
			$query = $conn->query($sql);

			//addLog($sql);
			exit;
		}

		if ($_GET["op"] == "excluirstatus"){
			
			$sql = "DELETE FROM td_movimentacaostatus WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);

			//addLog($sql);
			exit;
		}

		if ($_GET["op"] == "excluirhistorico"){
			$sql = "DELETE FROM td_movimentacaohistorico WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);
			exit;
		}

		if ($_GET["op"] == "listarmovimentacao"){
			$sql = "SELECT id,atributo atributo,legenda FROM ".PREFIXO."movimentacaoalterar a WHERE movimentacao = {$_GET["movimentacao"]} ORDER BY id DESC";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum campo de <strong>alterar</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo 			= $linha["atributo"];
				$legenda 			= executefunction("tdc::utf8",array($linha["legenda"]));
				$sqlAtributo 		= "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo 		= $conn->query($sqlAtributo);
				$linhaAtributo 		= $queryAtributo->fetch();
				$atributoDescricao 	= executefunction("tdc::utf8",array($linhaAtributo["descricao"]));
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong>
						<button type='button' class='btn btn-default' onclick='excluirAlterar({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-idalterar='{$linha["id"]}' data-legenda='{$linha["legenda"]}' onclick='editarAlterar({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
			exit;
		}
		
		if ($_GET["op"] == "listarstatus"){
			$sql = "SELECT id,atributo atributo,operador,valor FROM ".PREFIXO."movimentacaostatus a WHERE movimentacao = {$_GET["movimentacao"]}";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum atributo de <strong>status</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo 				= $linha["atributo"];
				$operador 				= $linha["operador"];
				$valor 					= $linha["valor"];
				$sqlAtributo 			= "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo 			= $conn->query($sqlAtributo);
				$linhaAtributo 			= $queryAtributo->fetch();
				$atributoDescricao 		= executefunction("tdc::utf8",array($linhaAtributo["descricao"]));
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>. Valor: <small class='text-info'>{$valor}</small>.
						<button type='button' class='btn btn-default' onclick='excluirStatus({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-valor='{$valor}' data-idstatus='{$linha["id"]}' onclick='editarStatus({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
			exit;
		}
		if ($_GET["op"] == "listarhistorico"){
			$sql = "SELECT id,atributo atributo,legenda FROM ".PREFIXO."movimentacaohistorico a WHERE movimentacao = {$_GET["movimentacao"]}";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum atributo de <strong>histórico</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo 			= $linha["atributo"];
				$legenda 			= $linha["legenda"];
				$sqlAtributo 		= "SELECT descricao,entidade FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo 		= $conn->query($sqlAtributo);
				$linhaAtributo 		= $queryAtributo->fetch();
				$atributoDescricao 	= executefunction("tdc::utf8",array($linhaAtributo["descricao"]));
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong>
						<button type='button' class='btn btn-default' onclick='excluirHistorico({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-entidade='{$linhaAtributo["entidade"]}' data-legenda='{$legenda}' data-idhistorico='{$linha["id"]}' onclick='editarHistorico({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
			exit;
		}
	}
	if ($id != ""){
		$sql = "SELECT descricao,entidade,motivo,exigirobrigatorio,exibirtitulo,exibirvaloresantigos FROM ".PREFIXO."movimentacao WHERE id = {$id}";
		$query = $conn->query($sql);
		foreach ($query->fetchAll() as $linha){
			$entidade				= $linha["entidade"];
			$descricao				= $linha["descricao"];
			$motivo					= $linha["motivo"];
			$exigirobrigatorio 		= $linha["exigirobrigatorio"];
			$exibirtitulo 			= $linha["exibirtitulo"];
			$exibirvaloresantigos	= $linha["exibirvaloresantigos"];
		}
	}
?>
<html>
	<head>
		<title>Criar Movimentação</title>
		<?php include 'head.php'; ?>
		<script type="text/javascript">
			window.onload = function(){
				document.getElementById("id").value = "<?=$id?>";
				if ("<?=$id?>" != ""){
					$("#accordion_alterar").show();
					$("#descricao").val("<?=$descricao?>");
					$("#entidade").val("<?=$entidade?>");
					$("#motivo").val("<?=$motivo?>");
					document.getElementById("exigirobrigatorio").checked = (<?=(int)$exigirobrigatorio?>==0)?false:true;
					document.getElementById("exibirtitulo").checked = (<?=(int)$exibirtitulo?>==0)?false:true;
					document.getElementById("exibirvaloresantigos").checked = (<?=(int)$exibirvaloresantigos?>==0)?false:true;
				}else{
					$("#accordion_alterar").hide();
				}
				$("#valor").val("");
				atualizarListaAlterar("<?=$id?>");
				atualizarListaStatus("<?=$id?>");
				atualizarListaHistorico("<?=$id?>");
			}
			function validar(){
				if ($("#entidade").val() == "" || $("#entidade").val() == null){
					alert('Entidade não pode ser vazio');
					return false;
				}
				return true;
			}
			function editarAlterar(id){
				$("#form-alterar #movimentacao").val(id);
				$("#form-alterar #atributo").val($("#lista-alterar #atributo-editar-" + id).data("atributo"));
				$("#form-alterar #legenda").val($("#lista-alterar #atributo-editar-" + id).data("legenda"));
				$("#form-alterar #idalterar").val($("#lista-alterar #atributo-editar-" + id).data("idalterar"));
				$("#modalCadastroAlterar").modal('show');
			}
			function editarStatus(id){
				carregarValoresAtributo($("#form-status #atributo"),id);
				setTimeout(function(){
					$("#form-status #movimentacao").val(id);
					$("#form-status #atributo").val($("#lista-status #atributo-editar-" + id).data("atributo"));
					$("#form-status #operador").val($("#lista-status #atributo-editar-" + id).data("operador"));
					$("#form-status #valor").val($("#lista-status #atributo-editar-" + id).data("valor"));
					$("#form-status #idstatus").val($("#lista-status #atributo-editar-" + id).data("idstatus"));
					$("#modalCadastroStatus").modal('show');					
				},500);
			}
			function editarHistorico(id){
				$("#form-historico #entidade").val($("#lista-historico #atributo-editar-" + id).data("entidade"));
				console.log($("#lista-historico #atributo-editar-" + id).data("entidade"));
				carregarAtributosHistorico();
				setTimeout(function(){
					$("#form-historico #movimentacao").val(id);
					$("#form-historico #atributo").val($("#lista-historico #atributo-editar-" + id).data("atributo"));
					$("#form-historico #legenda").val($("#lista-historico #atributo-editar-" + id).data("legenda"));
					$("#form-historico #idstatus").val($("#lista-historico #atributo-editar-" + id).data("idstatus"));
					$("#modalCadastroHistorico").modal('show');
				},500);
			}
			$(document).ready(function(){
				$("#salvarAlterar").click(function(){
					$.ajax({
						type:"POST",
						url:"criarMovimentacao.php",
						data:{
							op:"salvaralterar",
							atributo: $("#form-alterar #atributo").val(),
							movimentacao:"<?=$id?>",
							id:$("#form-alterar #idalterar").val(),
							legenda:$("#form-alterar #legenda").val()
						},
						complete:function(r){
							atualizarListaAlterar("<?=$id?>");
							$("#modalCadastroAlterar").modal('hide');
						}
					});
				});
				$("#salvarStatus").click(function(){
					$.ajax({
						type:"POST",
						url:"criarMovimentacao.php",
						data:{
							op:"salvarstatus",
							operador: $("#form-status #operador").val(),
							valor: $("#form-status #valor").val(),
							atributo: $("#form-status #atributo").val(),
							movimentacao:"<?=$id?>",
							id:$("#form-status #idstatus").val()
						},
						complete:function(r){
							atualizarListaStatus("<?=$id?>");
							$("#modalCadastroStatus").modal('hide');
						}
					});
				});
				$("#salvarHistorico").click(function(){
					$.ajax({
						type:"POST",
						url:"criarMovimentacao.php",
						data:{
							op:"salvarhistorico",
							legenda: $("#form-historico #legenda").val(),
							atributo: $("#form-historico #atributo").val(),
							movimentacao:"<?=$id?>",
							id:$("#form-historico #idhistorico").val()
						},
						complete:function(r){
							atualizarListaHistorico("<?=$id?>");
							$("#modalCadastroHistorico").modal('hide');
						}
					});
				});
				$("#form-status #atributo").change(function(){
					carregarValoresAtributo($(this));
				});
				carregarValoresAtributo($("#form-status #atributo"));
				$("#form-historico #entidade").change(function(){
					console.log("TESTE");
					carregarAtributosHistorico();
				});				
			});
			function novoAlterar(){
				$("#modalCadastroAlterar").modal({
					backdrop:false
				});
				$("#modalCadastroAlterar").modal('show');
				$("#form-alterar #movimentacao,#form-alterar #idalterar,#form-alterar #legenda").val("");
				$("#form-alterar #atributo").val($("#form-alterar #atributo option:first").val());
			}
			function novoStatus(){
				$("#modalCadastroStatus").modal({
					backdrop:false
				});
				$("#modalCadastroStatus").modal('show');				
				$("#form-status #movim,#form-status #valor,#form-status #idstatus").val("");
				$("#form-status #operador").val("=");
				$("#form-status #atributo").val($("#form-status #atributo option:first").val());
				$("#form-status #status").val($("#form-status #status option:first").val());
			}
			function novoHistorico(){
				$("#form-historico #entidade").val($("#form-historico #entidade option:first").val());
				carregarAtributosHistorico();
				$("#modalCadastroHistorico").modal({
					backdrop:false
				});
				$("#form-historico #legenda,#form-historico #idhistorico").val("");
				$("#form-historico #atributo").val($("#form-historico #atributo option:first").val());
				$("#modalCadastroHistorico").modal('show');
			}
			function atualizarListaAlterar(movimentacao){
				$("#lista-alterar").load("criarMovimentacao.php?op=listarmovimentacao&movimentacao=" + movimentacao);
			}
			function atualizarListaStatus(movimentacao){
				$("#lista-status").load("criarMovimentacao.php?op=listarstatus&movimentacao=" + movimentacao);
			}
			function atualizarListaHistorico(movimentacao){
				$("#lista-historico").load("criarMovimentacao.php?op=listarhistorico&movimentacao=" + movimentacao);
			}
			function excluirAlterar(id){
				$.ajax({
					url:"criarMovimentacao.php",
					data:{
						op:"excluiralterar",
						id:id
					},
					complete:function(){
						atualizarListaAlterar("<?=$id?>");
					}
				});
			}
			function excluirStatus(id){
				$.ajax({
					url:"criarMovimentacao.php",
					data:{
						op:"excluirstatus",
						id:id
					},
					complete:function(){
						atualizarListaStatus("<?=$id?>");
					}
				});
			}
			function excluirHistorico(id){
				$.ajax({
					url:"criarMovimentacao.php",
					data:{
						op:"excluirhistorico",
						id:id
					},
					complete:function(){
						atualizarListaHistorico("<?=$id?>");
					}
				});				
			}
			function carregarValoresAtributo(obj){
				if (arguments.length > 1){
					var valor = arguments[1];
				}else{
					var valor = "";
				}
				var fk = obj.find("option:selected").data("chaveestrangeira");
				if (fk > 0 && fk != "" && fk != undefined){
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
							valor:valor
						},
						complete:function(ret){
							$("#form-status #valor").html(ret.responseText);
						}
					});
				}else{
					var checkbox = obj.find("option:selected").data("checkbox");
					if (checkbox > 0 && checkbox != "" && checkbox != undefined){
						$.ajax({
							url:"../../index.php?controller=requisicoes",
							type:"GET",
							data:{
								op:"carregar_options_checkbox",
								atributo:obj.find("option:selected").val()
							},
							complete:function(ret){
								$(".form-control[data-tipoatributo=input]").hide();
								$(".form-control[data-tipoatributo=input]").attr("id","");
								$(".form-control[data-tipoatributo=input]").attr("name","");
								$(".form-control[data-tipoatributo=lista]").attr("id","valor");
								$(".form-control[data-tipoatributo=lista]").attr("name","valor");
								$(".form-control[data-tipoatributo=lista]").show();
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
			}
			function carregarAtributosHistorico(){
				$("#form-historico #atributo").load("criarMovimentacao.php?op=lista_atributos&entidade=" + $("#form-historico #entidade").val());
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
					<?php include 'menu_movimentacao.php'; ?>
				</div>
				<div class="col-md-10">
					<form action="criarMovimentacao.php" method="post" onsubmit="return validar();">
						<legend>
							Movimentação
						</legend>						
						<fieldset>
							<input type="hidden" id="id" name="id">
							<input type="hidden" id="op" name="op" value="salvar">
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
											$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
											echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'">'.$descricao.' [ '.$linha["nome"].' ]</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="motivo">Motivo <small>( Entidade )</small></label>
								<select id="motivo" name="motivo" class="form-control">
									<option value="0">-- Selecione --</option>
									<?php 
										$sql = "SELECT id,nome,descricao FROM ".PREFIXO."entidade";
										$query = $conn->query($sql);
										foreach($query->fetchAll() as $linha){
											$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
											echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'">'.$descricao.' [ '.$linha["nome"].' ]</option>';
										}
									?>
								</select>
							</div>
							<div class="checkbox">
								<label for="exigirobrigatorio">
									<input id="exigirobrigatorio" name="exigirobrigatorio" type="checkbox"> Obrigatório o preenchimento do campo "Observação"
								</label>
							</div>
							<div class="checkbox">
								<label for="exibirtitulo">
									<input id="exibirtitulo" name="exibirtitulo" type="checkbox"> Exibir Título (Legenda) na página
								</label>
							</div>
							<div class="checkbox">
								<label for="exibirvaloresantigos">
									<input id="exibirvaloresantigos" name="exibirvaloresantigos" type="checkbox"> Exibir Valores Antigos
								</label>
							</div>							
							<div id="error"></div>
							<button type="submit" class="btn btn-primary" >Salvar</button>
						</fieldset>
					</form>
					
					<!-- ALTERAR -->
					<div class="panel-group" id="accordion_alterar" role="tablist" aria-multiselectable="true">
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
						  <h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion_alterar" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
							  Alterar							  
							</a>
						  </h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						  <div class="panel-body">
								<button type="button" class="btn btn-default" aria-label="Novo Alterar" style="float:right;margin-top:-3px;" onclick="novoAlterar();">
								  <span class="fas fa-plus-circle" aria-hidden="true"></span>
								</button>

								<!-- CADASTRO DE ALTERAR -->
								<div class="modal fade" id="modalCadastroAlterar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								  <div class="modal-dialog" role="document">
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Cadastro de Alterar</h4>
									  </div>
									  <div class="modal-body">

											<!-- FORMULARIO DE ALTERAR -->
											<form id="form-alterar" action="criarMovimentacao.php" method="post">
												<fieldset>
													<input type="hidden" id="idalterar" name="idalterar" />
													<input type="hidden" id="op" name="op" value="salvaralterar" />
													<input type="hidden" id="movimentacao" name="movimentacao" value="<?=$id?>" />									
													<div class="form-group">
														<label for="atributo">Atributo</label>
														<select id="atributo" name="atributo" class="form-control">
														<?php 
															$sql = "SELECT id,nome,descricao FROM ".PREFIXO."atributo WHERE entidade = " . $entidade;
															$query = $conn->query($sql);
															foreach($query->fetchAll() as $linha){
																$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
																echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'">'.$descricao.' [ '.$linha["nome"].' ]</option>';
															}
														?>
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
											<button type="button" class="btn btn-primary" id="salvarAlterar">Salvar</button>
									  </div>
									</div>
								  </div>
								</div>
								<!-- CADASTRO DE ALTERAR -->
								<br/><br/>
								<div id="lista-alterar" class="list-group">
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
								<button type="button" class="btn btn-default" aria-label="Novo Alterar" style="float:right;margin-top:-3px;" onclick="novoStatus();">
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
									  
											<form id="form-status">
												<fieldset>
													<input type="hidden" id="idstatus" name="idstatus" />
													<input type="hidden" id="op" name="op" value="salvarstatus" />
													<input type="hidden" id="movimentacao" name="movimentacao" value="<?=$id?>" />									
													<div class="form-group">
														<label for="atributo">Atributo</label>
														<select id="atributo" name="atributo" class="form-control">
														<?php 
															$sql = "SELECT id,nome,descricao,chaveestrangeira,tipohtml FROM ".PREFIXO."atributo WHERE entidade = " . $entidade;
															$query = $conn->query($sql);
															foreach($query->fetchAll() as $linha){
																if ($linha["tipohtml"] == 7){
																	$carregalista =  'data-checkbox="'.$linha["tipohtml"].'"';
																}else{
																	if ($linha["chaveestrangeira"] != "" && $linha["chaveestrangeira"] > 0){
																		$carregalista = 'data-chaveestrangeira="'.$linha["chaveestrangeira"].'"';
																	}else{
																		$carregalista = '';
																	}
																}
																$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
																echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'"  '.$carregalista.'>'.$descricao.' [ '.$linha["nome"].' ]</option>';
															}
														?>
														</select>
													</div>
													<div class="form-group">
														<label for="operador">Operador</label>
														<select name="operador" id="operador" class="form-control">
															<option value="=">Igual</option>
														</select>
													</div>
													<div class="form-group">
														<label for="valor">Valor</label>
														<input type="text" name="" id="" class="form-control" data-tipoatributo="input">
														<select name="" id="" class="form-control" data-tipoatributo="lista">
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
					
					<!-- HISTÓRICO -->
					<div class="panel-group" id="accordion_historico" role="tablist" aria-multiselectable="true">
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
						  <h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion_historico" href="#collapseTrhee" aria-expanded="false" aria-controls="collapseOne">
							  Histórico							  
							</a>
						  </h4>
						</div>
						<div id="collapseTrhee" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						  <div class="panel-body">
								<button type="button" class="btn btn-default" aria-label="Novo Histórico" style="float:right;margin-top:-3px;" onclick="novoHistorico();">
								  <span class="fas fa-plus-circle" aria-hidden="true"></span>
								</button>

								<!-- CADASTRO DE HISTÓRICO -->
								<div class="modal fade" id="modalCadastroHistorico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								  <div class="modal-dialog" role="document">
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Cadastro de Histórico</h4>
									  </div>
									  <div class="modal-body">

											<!-- FORMULARIO DE HISTÓRICO -->
											<form id="form-historico" action="criarMovimentacao.php" method="post">
												<fieldset>
													<input type="hidden" id="idhistorico" name="idhistorico" />
													<input type="hidden" id="op" name="op" value="salvarhistorico" />
													<input type="hidden" id="movimentacao" name="movimentacao" value="<?=$id?>" />
													<div class="form-group">
														<label for="entidade">Entidade</label>
														<select id="entidade" name="entidade" class="form-control">
														<?php 
															$sql = "SELECT id,nome,descricao FROM ".PREFIXO."entidade ";
															$query = $conn->query($sql);
															foreach($query->fetchAll() as $linha){
																$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
																echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'">'.$descricao.' [ '.$linha["nome"].' ]</option>';
															}
														?>
														</select>
													</div>													
													<div class="form-group">
														<label for="atributo">Atributo</label>
														<select id="atributo" name="atributo" class="form-control">
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
											<button type="button" class="btn btn-primary" id="salvarHistorico">Salvar</button>
									  </div>
									</div>
								  </div>
								</div>
								<!-- CADASTRO DE HISTÓRICO -->
								<br/><br/>
								<div id="lista-historico" class="list-group">
								</div>
						  </div>
						</div>
					  </div>
					</div>
					
					
				</div>
			</div>
		</div>
	</body>
</html>
