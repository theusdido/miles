<?php
	require 'conexao.php';
	require 'prefixo.php';	
	//include 'log.php';
	include '../funcoes.php';
	
	$id = $tipo = $entidade = $atributo = $descricao = $ent = $entidadefilho = "";
	
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
			$descricao				= utf8charset($_POST["descricao"]);

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."menucrud");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."menucrud (id,descricao) VALUES ({$id},'{$descricao}');";
			}else{
				$sql = "UPDATE ".PREFIXO."menucrud SET descricao = '{$descricao}' WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
				//addLog($sql);
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}

			header("Location: criarMenucrud.php?id=" . $id);
		}
		if ($_POST["op"] == "salvaritemmenu"){
			$id			 			= isset($_POST["id"])?$_POST["id"]:'';
			$descricao				= $_POST["descricao"];
			$menucrud	 			= $_POST["menucrud"];
			$tipo	 				= $_POST["tipo"];
			$link 					= $_POST["link"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."menucruditens");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO ".PREFIXO."menucruditens (id,descricao,".PREFIXO."menucrud,tipo,link) VALUES ({$id},'{$descricao}',{$menucrud},{$tipo},'{$link}');";
			}else{
				$sql = "UPDATE ".PREFIXO."menucruditens SET ".PREFIXO."menucrud = {$menucrud} , descricao = '{$descricao}' , tipo = {$tipo} , link = '{$link}' WHERE id = {$id};";
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
		
		if ($_GET["op"] == "excluiritem"){
			
			$sql = "DELETE FROM td_menucruditens WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);

			$sql = "DELETE FROM td_menucruditens WHERE id = " . $_GET["atributo"];
			$query = $conn->query($sql);

			//addLog($sql);
			exit;
		}
		
		if ($_GET["op"] == "listaritem"){
			$sql = "SELECT id,descricao,tipo,link FROM ".PREFIXO."menucruditens a WHERE menucrud = {$_GET["menucrud"]} ORDER BY id DESC";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum iten de menu configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$link = $linha["link"];
				$tipo = $linha["tipo"];

				switch ($tipo) {
					case 1: $tipodescricao = "Cadastro"; break;
					case 2: $tipodescricao = "Consulta"; break;
					case 3: $tipodescricao = "Movimentação"; break;
					case 4: $tipodescricao = "Relatório"; break;
				}
				$descricao = utf8_encode($linha["descricao"]);
				echo "<span class='list-group-item'>
						Item do Menu <strong>{$descricao}</strong> do tipo  ( <strong>{$tipodescricao} ) </strong>
						<button type='button' class='btn btn-default' onclick='excluirItem({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-tipo='{$tipo}' data-descricao='{$descricao}' data-iditemmenu='{$linha["id"]}' data-link='{$linha["link"]}' onclick='editarFiltro({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
			exit;
		}
	}
	if ($id != ""){
		$sql = "SELECT descricao FROM ".PREFIXO."menucrud WHERE id = {$id}";
		$query = $conn->query($sql);
		foreach ($query->fetchAll() as $linha){
			$descricao		= utf8_encode($linha["descricao"]);
		}
	}
?>
<html>
	<head>
		<title>Menu ( CRUD )</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
			window.onload = function(){
				document.getElementById("id").value = "<?=$id?>";
				if ("<?=$id?>" != ""){
					$("#accordion_itens").show();
					$("#descricao").val("<?=$descricao?>");
				}else{
					$("#accordion_itens").hide();
				}
				$("#valor").val("");
				atualizarListaItem("<?=$id?>");
			}
			function validar(){
				if ($("#descricao").val() == "" || $("#descricao").val() == null){
					alert('Descrição não pode ser vazio');
					return false;
				}
				return true;
			}
			function editarFiltro(id){
				$("#form-itemmenu #td_menucrud").val(id);
				$("#form-itemmenu #tipo").val($("#lista-item #atributo-editar-" + id).data("tipo"));
				$("#form-itemmenu #descricao").val($("#lista-item #atributo-editar-" + id).data("descricao"));
				$("#form-itemmenu #link").val($("#lista-item #atributo-editar-" + id).data("link"));
				$("#form-itemmenu #iditemmenu").val($("#lista-item #atributo-editar-" + id).data("iditemmenu"));
				$("#modalCadastroItens").modal('show');
			}
			$(document).ready(function(){
				$("#salvarItemmenu").click(function(){
					$.ajax({
						type:"POST",
						url:"criarMenucrud.php",
						data:{
							op:"salvaritemmenu",
							tipo: $("#form-itemmenu #tipo").val(),
							link: $("#form-itemmenu #link").val(),
							menucrud:"<?=$id?>",
							id:$("#form-itemmenu #iditemmenu").val(),
							descricao:$("#form-itemmenu #descricao").val()
						},
						complete:function(r){
							atualizarListaItem("<?=$id?>");
							$("#modalCadastroItens").modal('hide');
						}
					});
				});
			});
			function novoItemMenu(){
				$("#modalCadastroItens").modal({
					backdrop:false
				});
				$("#modalCadastroItens").modal('show');
				$("#form-itemmenu #descricao,#form-itemmenu #link,#form-itemmenu #iditemmenu").val("");
				$("#form-itemmenu #tipo").val($("#form-itemmenu #tipo option:first").val());
			}
			function atualizarListaItem(menucrud){
				$("#lista-item").load("criarMenucrud.php?op=listaritem&menucrud=" + menucrud);
			}
			function excluirItem(id){
				$.ajax({
					url:"criarMenucrud.php",
					data:{
						op:"excluiritem",
						id:id
					},
					complete:function(){
						atualizarListaItem("<?=$id?>");
					}
				});
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
					<?php include 'menu_menucrud.php'; ?>
				</div>
				<div class="col-md-10">
					<form action="criarMenucrud.php" method="post" onsubmit="return validar();">
						<legend>
							Menu ( CRUD )
						</legend>						
						<fieldset>
							<input type="hidden" id="id" name="id">
							<input type="hidden" id="op" name="op" value="salvar">
							<div class="form-group">
								<label for="nome">Descri&ccedil;&atilde;o</label>
								<input type="text" name="descricao" id="descricao" class="form-control">
							</div>
							<div id="error"></div>
							<button type="submit" class="btn btn-primary" >Salvar</button>
						</fieldset>
					</form>
					
					<!-- ITENS -->
					<div class="panel-group" id="accordion_itens" role="tablist" aria-multiselectable="true">
					  <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
						  <h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion_itens" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
							  Itens do Menu							  
							</a>
						  </h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						  <div class="panel-body">
								<button type="button" class="btn btn-default" aria-label="Novo Item Menu" style="float:right;margin-top:-3px;" onclick="novoItemMenu();">
								  <span class="fas fa-plus-circle" aria-hidden="true"></span>
								</button>

								<!-- CADASTRO DE ITEM MENU -->
								<div class="modal fade" id="modalCadastroItens" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								  <div class="modal-dialog" role="document">
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Cadastro de Item</h4>
									  </div>
									  <div class="modal-body">

											<!-- FORMULARIO DE ITEM MENU -->
											<form id="form-itemmenu" action="criarMenucrud.php" method="post">
												<fieldset>
													<input type="hidden" id="iditemmenu" name="iditemmenu" />
													<input type="hidden" id="op" name="op" value="salvaritemmenu" />
													<input type="hidden" id="td_menucrud" name="td_menucrud" value="<?=$id?>" />
													<div class="form-group">
														<label for="tipo">Tipo</label>
														<select name="tipo" id="tipo" class="form-control">
															<option value="1">Cadastro</option>
															<option value="2">Consulta</option>
															<option value="3">Movimentação</option>
															<option value="4">Relatório</option>
														</select>
													</div>
													<div class="form-group">
														<label for="descricao">Descrição</label>
														<input type="text" name="descricao" id="descricao" class="form-control">
													</div>
													<div class="form-group">
														<label for="link">Link</label>
														<input type="text" name="link" id="link" class="form-control">
													</div>													
												</fieldset>
											</form>
									  
									  </div>
									  <div class="modal-footer">
											<div id="error"></div>
											<button type="button" class="btn btn-primary" id="salvarItemmenu">Salvar</button>
									  </div>
									</div>
								  </div>
								</div>
								<!-- CADASTRO DE FILTRO -->
								<br/><br/>
								<div id="lista-item" class="list-group">
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
