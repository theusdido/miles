<?php
	require 'conexao.php';
	require 'prefixo.php';	
	require_once 'funcoes.php';
	if (isset($_GET["op"])){		
		if ($_GET["op"] == "lista_atributos"){
			echo '<option value="0"></option>';		
			$sql = "SELECT id,descricao,nome FROM ".PREFIXO."atributo WHERE entidade = " . $_GET["entidade"];
			$query = $conn->query($sql);
			foreach($query->fetchAll() as $linha){
				echo '<option value="'.$linha["id"].'">'.executefunction("utf8charset",array($linha["descricao"])).' [ '.$linha["nome"].' ]</option>';
			}
		}
		exit;
	}	
	$id = $tipo = $entidade = $atributo = $descricao = $ent = $entidadefilho = "";
	
	if (isset($_GET["entidade"])){
		$entidade = $_GET["entidade"];
	}
	$ent = $entidade; #usado para montar o menu
	
	if (isset($_GET["id"])){
		$id = $_GET["id"];
	}
	if (isset($_GET["excluir"])){
		$sql = "DELETE FROM ".PREFIXO."relacionamento WHERE id = {$_GET["id"]};";
		$query = $conn->query($sql);
		if ($query){
			header("Location: criarRelacionamento.php?entidade={$_GET["entidade"]}" . getURLParamsProject("&"));
		}		
		exit;
	}	
	if (!empty($_POST)){
		$id			 			= isset($_POST["id"])?$_POST["id"]:'';
		$tipo 					= $_POST["tipo"];
		$entidade	 			= $_POST["entidade"];
		$entidadefilho			= $_POST["entidadefilho"];
		$atributo 				= isset($_POST["atributo"])?($_POST["atributo"]==""?"0":$_POST["atributo"]):"0";
		$descricao				= executefunction("utf8charset",array($_POST["descricao"]));
		
		switch($tipo){
			case 1: case 7: case 3: case 9: $cardinalidade = "11"; break;
			case 6: case 2: case 8: $cardinalidade = "1N"; break;
			case 5: case 10: $cardinalidade = "NN"; break;
			default: $cardinalidade = "";
		}

		if ($id == ""){
			$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".PREFIXO."relacionamento");
			$prox = $query_prox->fetch();
			$sql = "INSERT INTO ".PREFIXO."relacionamento (id,pai,tipo,filho,atributo,descricao,cardinalidade) VALUES ({$prox[0]},{$entidade},'{$tipo}','{$entidadefilho}',{$atributo},'{$descricao}','{$cardinalidade}');";
		}else{
			$sql = "UPDATE ".PREFIXO."relacionamento SET pai = {$entidade} , tipo = {$tipo} , filho = {$entidadefilho} , atributo = {$atributo} , descricao = '{$descricao}' , cardinalidade = '{$cardinalidade}' WHERE id = {$id};";
		}

		$query = $conn->query($sql);
		if($query){
			header("Location: criarRelacionamento.php?entidade=" . $entidade . getURLParamsProject("&"));
		}else{
			if (IS_SHOW_ERROR_MESSAGE){
				echo $sql;
				var_dump($conn->errorInfo());
			}
			exit;
		}
	}
	if ($id != ""){
		$sql = "SELECT filho,tipo,pai,atributo,descricao,atributo FROM ".PREFIXO."relacionamento WHERE id = {$id}";
		$query = $conn->query($sql);
		foreach ($query->fetchAll() as $linha){			
			$tipo 			= $linha["tipo"];			
			$atributo		= $linha["atributo"];
			$descricao		= executefunction("utf8charset",array($linha["descricao"]));
			$entidadefilho 	= $linha["filho"];
		}
	}
?>
<html>
	<head>
		<title>Criar Relacionamento</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
			var entidadecarregar = 0;
			window.onload = function(){				
				document.getElementById("id").value = "<?=$id?>";
				$("#entidade").val("<?=$entidade?>");
				if ("<?=$id?>" != ""){
					$("#tipo").val("<?=$tipo?>");
					$("#descricao").val("<?=$descricao?>");
					$("#entidadefilho").val("<?=$entidadefilho?>");
					disableAtributo("<?=$tipo?>");
					carregarAtributos();
				}else{
					disableAtributo($("#tipo").val());
				}
				
				$("#entidadefilho").change(function(){
					disableAtributo($("#tipo").val());
					carregarAtributos();
				});
				$("#tipo").change(function(){
					disableAtributo($(this).val());
				});
			}			
			function disableAtributo(tipo){
				var tipo = parseInt(tipo);
				if (tipo == 5 || tipo == 3 || tipo == 10 || tipo == 1){
					$("#atributo").val(0);
					$("#atributo").attr("disabled",true);
					entidadecarregar = 0;
				}else{					
					$("#atributo").attr("disabled",false);
					if (tipo == 6 || tipo == 2 || tipo == 8 || tipo == 9){
						entidadecarregar = $("#entidadefilho").val();
					}else{
						entidadecarregar = $("#entidade").val();
					}
				}

				if (isEmpty(entidadecarregar)){
					$("#atributo").attr("disabled",true);
				}
			}

			function carregarAtributos(){
				$("#atributo").load("criarRelacionamento.php?op=lista_atributos&entidade=" + entidadecarregar + "&currentproject="+<?=$_SESSION["currentproject"]?>,function(){
					$("#atributo").val("<?=$atributo?>");
				});
			}

			function isComposicao(){
				if ($("#tipo").val() == 2 || $("#tipo").val() == 7){
					return true;
				}else{
					return false;
				}
			}

			function validar(){

				if (isEmpty($('#descricao').val())){
					addMensagemErro('<b>Descrição</b> é obrigatório.');
					return false;
				}
				if ( isComposicao() && isEmpty($("#atributo").val()) ){
					addMensagemErro('<b>Atributo</b> é obrigatório para relacionamento do tipo <b>Composição</b>.');
					return false;
				}else{
					return true;
				}
			}

			function isEmpty(valor){
				switch(valor){
					case 0:
					case '0':
					case '':
					case undefined:
					case null:
						return true;
					break;
					default:
						return false;
				}
			}

			function addMensagemErro(msg){
				$('#error').addClass('alert-danger');
				$('#error').html(msg);
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
					<?php include 'menu_entidade.php'; ?>
					<?php if ($id!="") include 'menu_atributo.php'; ?>
				</div>
				<div class="col-md-10">
					<form action="criarRelacionamento.php" method="post" onsubmit="return validar();">
						<legend>
							Relacionamento
						</legend>						
						<fieldset>
							<input type="hidden" id="id" name="id">
							<input type="hidden" id="entidade" name="entidade">
							<input type="hidden" name="currentproject" value="<?php echo $_SESSION["currentproject"]; ?>">
							<div class="form-group">
								<label for="nome">Descri&ccedil;&atilde;o</label>
								<input type="text" name="descricao" id="descricao" class="form-control"> 
							</div>							
							<div class="form-group">
								<label for="nome">Tipo</label>
								<small>( Padr&atilde;o da UML )</small>
								<select name="tipo" id="tipo" class="form-control">
									<option value="1">01 - Associa&ccedil;&atilde;o - Agrega&ccedil;&atilde;o (1:1)</option>
									<option value="6">06 - Associa&ccedil;&atilde;o - Agrega&ccedil;&atilde;o (1:N)</option>
									<option value="5">05 - Associa&ccedil;&atilde;o - Agrega&ccedil;&atilde;o (N:N)</option>
									<option value="7">07 - Associa&ccedil;&atilde;o - Composi&ccedil;&atilde;o (1:1)</option>
									<option value="2">02 - Associa&ccedil;&atilde;o - Composi&ccedil;&atilde;o (1:N)</option>
									<option value="10">10 - Associa&ccedil;&atilde;o - Composi&ccedil;&atilde;o (N:N)</option>
									<option value="3">03 - Generaliza&ccedil;&atilde;o/Especializa&ccedil;&atilde;o (1:1)</option>
									<option value="8">08 - Generaliza&ccedil;&atilde;o/Especializa&ccedil;&atilde;o (1:N)</option>
									<option value="9">09 - Generaliza&ccedil;&atilde;o/Especializa&ccedil;&atilde;o M&uacute;ltipla (1:1)</option>
									<option value="4">04 - Depend&ecirc;ncia</option>
								</select>
							</div>
							<div class="form-group">
								<label for="filho">Entidade</label>
								<select id="entidadefilho" name="entidadefilho" class="form-control">
									<option value="0">Selecione</option>
									<?php 
										$sql = "SELECT id,nome,descricao FROM ".PREFIXO."entidade ORDER BY id DESC;";
										$query = $conn->query($sql);
										foreach($query->fetchAll() as $linha){
											echo '<option value="'.$linha["id"].'">[ '.$linha["id"].' ] '.executefunction("utf8charset",array($linha["descricao"])).' [ '.$linha["nome"].' ]</option>';
										}
									?>
								</select>								
							</div>
							<div class="form-group">
								<label for="atributo">Atributo</label>
								<small>( Atributo que implementa o relacionamento )</small>
								<select id="atributo" name="atributo" class="form-control">
								</select>							
							</div>
							<div id="error" class="alert"></div>	
							<button type="submit" class="btn btn-primary" >Salvar</button>							  
						</fieldset>	  
					</form>		
					<div class="list-group">
					<?php						
						$sql = "SELECT a.id,a.filho,a.pai pai,a.tipo,a.descricao,(SELECT b.descricao FROM ".PREFIXO."entidade b WHERE a.filho = b.id) as entidade_nome FROM ".PREFIXO."relacionamento a WHERE a.pai = {$entidade}";												
						$query = $conn->query($sql);
						foreach($query->fetchAll() as $linha){
							$tipo_nome = "";
							switch ($linha["tipo"]){
								case 1:
									$tipo_nome = "Associa&ccedil;&atilde;o - Agrega&ccedil;&atilde;o (1:1)";
								break;
								case 2:
									$tipo_nome = "Associa&ccedil;&atilde;o - Composi&ccedil;&atilde;o (1:N)";
								break;
								case 3:
									$tipo_nome = "Generaliza&ccedil;&atilde;o/Especializa&ccedil;&atilde;o (1:1)";
								break;
								case 4:
									$tipo_nome = "Depend&ecirc;ncia";
								break;
								case 5:
									$tipo_nome = "Associa&ccedil;&atilde;o - Agrega&ccedil;&atilde;o (N:N)";
								break;
								case 6:
									$tipo_nome = "Associa&ccedil;&atilde;o - Agrega&ccedil;&atilde;o (1:N)";
								break;
								case 7:
									$tipo_nome = "Associa&ccedil;&atilde;o - Composi&ccedil;&atilde;o (1:1)";
								break;
								case 8:
									$tipo_nome = "Generaliza&ccedil;&atilde;o/Especializa&ccedil;&atilde;o (1:N)";
								break;
								case 9:
									$tipo_nome = "Generaliza&ccedil;&atilde;o/Especializa&ccedil;&atilde;o Múltipla (1:1)";
								break;
								case 10:
									$tipo_nome = "Associa&ccedil;&atilde;o - Composi&ccedil;&atilde;o (N:N)";
								break;
							}

							$descricao = $linha['descricao'];
							$entidade_nome = $linha['entidade_nome'];
							echo "<a class='list-group-item'>
									Relacionamento de <strong>{$tipo_nome}</strong> com <strong>{$entidade_nome}</strong>. <small class='text-info'>{$descricao}</small>.
									<button type='button' class='btn btn-default' onclick=location.href='criarRelacionamento.php?excluir&id={$linha["id"]}&entidade={$entidade}".getURLParamsProject("&")."' style='float:right;margin-top:-4px'>
										<span class='fas fa-trash-alt' aria-hidden='true'></span>
									</button>
									<button type='button' class='btn btn-default' onclick=location.href='criarRelacionamento.php?id={$linha["id"]}&entidade={$entidade}".getURLParamsProject("&")."' style='float:right;margin-top:-4px'>
										<span class='fas fa-edit' aria-hidden='true'></span>
									</button>
								</a>";
						}
					?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>