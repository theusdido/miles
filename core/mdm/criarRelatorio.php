<?php
	require 'conexao.php';
	require 'prefixo.php';	
	include 'funcoes.php';
	
	$id = $entidade = $atributo = $descricao = $ent = $entidadefilho = $urlpersonalizada =  "";
	
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
			$urlpersonalizada		= $_POST["urlpersonalizada"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."relatorio");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."relatorio (id,descricao,entidade,urlpersonalizada) VALUES ({$id},'{$descricao}',{$entidade},'{$urlpersonalizada}');";
			}else{
				$sql = "UPDATE ".PREFIXO."relatorio SET entidade = {$entidade} , descricao = '{$descricao}' , urlpersonalizada = '{$urlpersonalizada}' WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if(!$query){
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}

			header("Location: criarRelatorio.php?id=" . $id);
		}
		if ($_POST["op"] == "salvarfiltro"){
			$id			 			= isset($_POST["id"])?$_POST["id"]:'';
			$operador				= $_POST["operador"];
			#$atributo	 			= $id==""?clonarAtributo($_POST["atributo"],$conn):$_POST["atributo"];
			$atributo	 			= $_POST["atributo"];
			$relatorio	 			= $_POST["relatorio"];
			$legenda 				= $_POST["legenda"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."relatoriofiltro");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."relatoriofiltro (id,operador,atributo,relatorio,legenda) VALUES ({$id},'{$operador}',{$atributo},{$relatorio},'{$legenda}');";
			}else{
				$sql = "UPDATE ".PREFIXO."relatoriofiltro SET atributo = {$atributo} , relatorio = {$relatorio} , operador = '{$operador}' , legenda = '{$legenda}' WHERE id = {$id};";
			}			
			$query = $conn->query($sql);
			if($query){
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
			$relatorio	 			= $_POST["relatorio"];
			$status 				= $_POST["status"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."relatoriostatus");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."relatoriostatus (id,operador,valor,atributo,relatorio,status) VALUES ({$id},'{$operador}','{$valor}',{$atributo},{$relatorio},{$status});";
			}else{
				$sql = "UPDATE ".PREFIXO."relatoriostatus SET atributo = {$atributo} , relatorio = {$relatorio} , valor = '{$valor}' , operador = '{$operador}' , status = {$status} WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
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
		
		if ($_GET["op"] == "excluirfiltro"){
			
			$sql = "DELETE FROM td_relatoriofiltro WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);
			exit;
		}

		if ($_GET["op"] == "excluirstatus"){
			
			$sql = "DELETE FROM td_relatoriostatus WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);

			exit;
		}
		
		if ($_GET["op"] == "listarrelatorio"){
			$sql = "SELECT id,atributo atributo,operador,legenda FROM ".PREFIXO."relatoriofiltro a WHERE relatorio = {$_GET["relatorio"]} ORDER BY id DESC";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum campo de <strong>filtro</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo 			= $linha["atributo"];
				$operador 			= $linha["operador"];
				$legenda 			= executefunction("utf8charset",array($linha["legenda"]));
				$sqlAtributo 		= "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo 		= $conn->query($sqlAtributo);
				$linhaAtributo 		= $queryAtributo->fetch();
				$atributoDescricao 	= executefunction("utf8charset",array($linhaAtributo["descricao"]));
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
		
		if ($_GET["op"] == "listarstatus"){
			$sql 	= "SELECT id,atributo atributo,operador,valor,status FROM ".PREFIXO."relatoriostatus a WHERE relatorio = {$_GET["relatorio"]}";
			$query 	= $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum filtro de <strong>status</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo 			= $linha["atributo"];
				$operador 			= $linha["operador"];
				$valor 				= $linha["valor"];
				$sqlAtributo 		= "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo 		= $conn->query($sqlAtributo);
				$linhaAtributo 		= $queryAtributo->fetch();
				$atributoDescricao 	= executefunction("utf8charset",array($linhaAtributo["descricao"]));
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
		$sql 					= "SELECT descricao,entidade,urlpersonalizada FROM ".PREFIXO."relatorio WHERE id = {$id}";
		$query 					= $conn->query($sql);
		foreach ($query->fetchAll() as $linha){
			$entidade			= $linha["entidade"];
			$descricao			= executefunction("utf8charset",array($linha["descricao"]));
			$urlpersonalizada 	= $linha["urlpersonalizada"];
		}
	}
?>
<html>
	<head>
		<title>Criar Relatório</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
			window.onload = function(){
				document.getElementById("id").value = "<?=$id?>";
				if ("<?=$id?>" != ""){
					$("#accordion_filtros").show();
					$("#descricao").val("<?=$descricao?>");
					$("#entidade").val("<?=$entidade?>");
					$("#urlpersonalizada").val("<?=$urlpersonalizada?>");
				}else{
					$("#accordion_filtros").hide();
				}
				$("#valor").val("");
				atualizarListaFiltro("<?=$id?>");
				atualizarListaStatus("<?=$id?>");
			}
			function validar(){
				if ($("#entidade").val() == "" || $("#entidade").val() == null){
					alert('Entidade não pode ser vazio');
					return false;
				}
				return true;
			}
			function editarFiltro(id){
				$("#form-filtro #relatorio").val(id);
				$("#form-filtro #atributo").val($("#lista-filtro #atributo-editar-" + id).data("atributo"));
				$("#form-filtro #operador").val($("#lista-filtro #atributo-editar-" + id).data("operador"));
				$("#form-filtro #legenda").val($("#lista-filtro #atributo-editar-" + id).data("legenda"));
				$("#form-filtro #idfiltro").val($("#lista-filtro #atributo-editar-" + id).data("idfiltro"));
				$("#modalCadastroFiltro").modal('show');
			}
			function editarStatus(id){
				$("#form-status #relatorio").val(id);
				$("#form-status #atributo").val($("#lista-status #atributo-editar-" + id).data("atributo"));
				$("#form-status #operador").val($("#lista-status #atributo-editar-" + id).data("operador"));
				$("#form-status #valor").val($("#lista-status #atributo-editar-" + id).data("valor"));
				$("#form-status #status").val($("#lista-status #atributo-editar-" + id).data("status"));
				$("#form-status #idstatus").val($("#lista-status #atributo-editar-" + id).data("idstatus"));
				$("#modalCadastroStatus").modal('show');
			}
			$(document).ready(function(){
				$("#salvarFiltro").click(function(){
					$.ajax({
						type:"POST",
						url:"criarRelatorio.php",
						data:{
							op:"salvarfiltro",
							operador: $("#form-filtro #operador").val(),
							atributo: $("#form-filtro #atributo").val(),
							relatorio:"<?=$id?>",
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
				$("#salvarStatus").click(function(){
					$.ajax({
						type:"POST",
						url:"criarRelatorio.php",
						data:{
							op:"salvarstatus",
							operador: $("#form-status #operador").val(),
							valor: $("#form-status #valor").val(),
							atributo: $("#form-status #atributo").val(),
							relatorio:"<?=$id?>",
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
				$("#form-filtro #relatorio,#form-filtro #idfiltro,#form-filtro #legenda").val("");
				$("#form-filtro #operador").val("=");
				$("#form-filtro #atributo").val($("#form-filtro #atributo option:first").val());
			}
			function novoStatus(){
				$("#modalCadastroStatus").modal({
					backdrop:false
				});
				$("#modalCadastroStatus").modal('show');				
				$("#form-status #relatorio,#form-status #valor,#form-status #idstatus").val("");
				$("#form-status #operador").val("=");
				$("#form-status #atributo").val($("#form-status #atributo option:first").val());
				$("#form-status #status").val($("#form-status #status option:first").val());
			}
			function atualizarListaFiltro(relatorio){
				$("#lista-filtro").load("criarRelatorio.php?op=listarrelatorio&relatorio=" + relatorio + "&currentproject=" + <?=$_SESSION["currentproject"]?>);
			}
			function atualizarListaStatus(relatorio){
				$("#lista-status").load("criarRelatorio.php?op=listarstatus&relatorio=" + relatorio + "&currentproject=" + <?=$_SESSION["currentproject"]?>);
			}			
			function excluirFiltro(id){
				$.ajax({
					url:"criarRelatorio.php",
					data:{
						op:"excluirfiltro",
						id:id,
						currentproject:<?=$_SESSION["currentproject"]?>
					},
					complete:function(){
						atualizarListaFiltro("<?=$id?>");
					}
				});
			}
			function excluirStatus(id){
				$.ajax({
					url:"criarRelatorio.php",
					data:{
						op:"excluirstatus",
						id:id,
						currentproject:<?=$_SESSION["currentproject"]?>
					},
					complete:function(){
						atualizarListaStatus("<?=$id?>");
					}
				});
			}
			function carregarValoresAtributo(fk){
				console.log(fk);
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
							currentproject:<?=$_SESSION["currentproject"]?>
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
					<?php include 'menu_relatorio.php'; ?>
				</div>
				<div class="col-md-10">
					<form action="criarRelatorio.php" method="post" onsubmit="return validar();">
						<legend>
							Relatório
						</legend>						
						<fieldset>
							<input type="hidden" id="id" name="id" />
							<input type="hidden" id="op" name="op" value="salvar" />
							<input type="hidden" id="currentproject" name="currentproject" value="<?=$_SESSION["currentproject"]?>" />
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
								<label for="urlpersonalizada">URL Personalizada</label>
								<input type="text" name="urlpersonalizada" id="urlpersonalizada" class="form-control">
							</div>						
							<div id="error"></div>
							<button type="submit" class="btn btn-primary" >Salvar</button>
						</fieldset>
					</form>
					
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
											<form id="form-filtro" action="criarRelatorio.php" method="post">
												<fieldset>
													<input type="hidden" id="idfiltro" name="idfiltro" />
													<input type="hidden" id="op" name="op" value="salvarfiltro" />
													<input type="hidden" id="relatorio" name="relatorio" value="<?=$id?>" />
													<input type="hidden" id="currentproject" name="currentproject" value="<?=$_SESSION["currentproject"]?>" />
													<div class="form-group">
														<label for="atributo">Atributo</label>
														<select id="atributo" name="atributo" class="form-control">
														<?php 
															$sql = "SELECT id,nome,descricao FROM ".PREFIXO."atributo WHERE entidade = " . $entidade . " AND exibirgradededados = 1";
															$query = $conn->query($sql);
															foreach($query->fetchAll() as $linha){
																echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'">'.utf8_encode($linha["descricao"]).' [ '.$linha["nome"].' ]</option>';
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
									  
											<form id="form-status" action="criarRelatorio.php" method="post">
												<fieldset>
													<input type="hidden" id="idstatus" name="idstatus" />
													<input type="hidden" id="op" name="op" value="salvarfiltro" />
													<input type="hidden" id="relatorio" name="relatorio" value="<?=$id?>" />									
													<div class="form-group">
														<label for="atributo">Atributo</label>
														<select id="atributo" name="atributo" class="form-control">
														<?php 
															$sql = "SELECT id,nome,descricao,chaveestrangeira FROM ".PREFIXO."atributo WHERE entidade = " . $entidade . " AND exibirgradededados = 1";
															$query = $conn->query($sql);
															foreach($query->fetchAll() as $linha){
																echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'" data-chaveestrangeira="'.$linha["chaveestrangeira"].'">'.utf8_encode($linha["descricao"]).' [ '.$linha["nome"].' ]</option>';
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
																echo '<option value="'.$linha["id"].'">'.utf8_encode($linha["descricao"]).'</option>';
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
				</div>
			</div>
		</div>
	</body>
</html>
