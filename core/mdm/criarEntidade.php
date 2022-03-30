<?php
	require 'conexao.php';
	require 'prefixo.php';
	require_once 'funcoes.php';
	include 'configuracoes.php';
	
	$id = $nome = $descricao = $exibirmenuadministracao = $exibircabecalho = $exibirlegenda = $registrounico = $carregarlibjavascript = $tipoaba = "";
	$campodescchave = $atributogeneralizacao = 0;
	$ncolunas = 1;

	if (isset($_GET['op'])){
		if ($_GET['op'] == 'alterar_id'){
			$conn->exec('UPDATE td_atributo SET entidade=' . $_GET['entidade_new'] . ' WHERE entidade = ' . $_GET['entidade']);
			$conn->exec('UPDATE td_entidade SET id=' . $_GET['entidade_new'] . ' WHERE id = ' . $_GET['entidade']);
			exit;
		}
	}
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
		$nome			 			= isset($_POST["nome"])?$_POST["nome"]:'';		
		$descricao 					= executefunction("utf8charset",array($_POST["descricao"]));
		$ncolunas					= ($_POST["ncolunas"]=='')?0:$_POST["ncolunas"];
		$exibirmenuadministracao 	= isset($_POST["exibirmenuadministracao"])?1:0;
		$exibircabecalho 			= isset($_POST["exibircabecalho"])?1:0;
		$campodescchave				= ($_POST["campodescchave"]=='')?'0':$_POST["campodescchave"];
		$atributogeneralizacao		= ($_POST["atributogeneralizacao"]=='')?'0':$_POST["atributogeneralizacao"];
		$exibirlegenda				= isset($_POST["exibirlegenda"])?1:0;
		$criarprojeto				= isset($_POST["criarprojeto"])?1:0;
		$criarempresa				= isset($_POST["criarempresa"])?1:0;
		$criarauth					= isset($_POST["criarauth"])?1:0;
		$registrounico				= isset($_POST["registrounico"])?1:0;
		$carregarlibjavascript		= isset($_POST["carregarlibjavascript"])?1:0;
		$tipoaba					= isset($_POST["tipoaba"])?$_POST["tipoaba"]:'';

		if ($_POST["id"] == ""){
			if ($bancodados == "mysql"){
				$nome = PREFIXO . str_replace('td_','',$nome);
				$atributos_iniciais = "";
				if ($criarprojeto == 1){
					$atributos_iniciais .= ",projeto int not null";				
				}
				if ($criarempresa == 1){
					$atributos_iniciais .= ",empresa int not null";
				}
				if ($criarauth == 1){
					$atributos_iniciais .= ",auth varchar(45) not null,auth0 varchar(45)";
				}
				
				$sql = "CREATE TABLE IF NOT EXISTS {$nome}(id int not null primary key{$atributos_iniciais}) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
				$criar = $conn->query($sql);
				if ($criar){

					// ID Última entidade
					$linha_ultimo 	= $conn->query("SELECT MAX(id)+1 id FROM ".PREFIXO."entidade")->fetchAll();
					$prox 			= $linha_ultimo[0]['id'];
					$sql 			= "INSERT INTO ".PREFIXO."entidade (id,nome,descricao,exibirmenuadministracao,exibircabecalho,ncolunas,atributogeneralizacao,exibirlegenda,registrounico,carregarlibjavascript,tipoaba) VALUES ({$prox},'{$nome}','{$descricao}',{$exibirmenuadministracao},{$exibircabecalho},{$ncolunas},{$atributogeneralizacao},{$exibirlegenda},{$registrounico},{$carregarlibjavascript},'{$tipoaba}');";
					$query 			= $conn->query($sql);

					if($query){

						// ID Última entidade
						$linha_ultimo_attr 	= $conn->query("SELECT MAX(id)+1 id FROM ".PREFIXO."atributo")->fetchAll();
						$prox_attr 			= $linha_ultimo_attr[0]['id'];

						if ($criarprojeto == 1){
							$sql_projeto = "INSERT INTO ".PREFIXO."atributo (id,entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa,inicializacao) VALUES ({$prox_attr},{$prox},'projeto','Projeto','smallint','',0,'16',0,3,0,'session.projeto');";
							$conn->exec($sql_projeto);
							$prox_attr++;
						}

						if ($criarempresa == 1){
							$sql_empresa = "INSERT INTO ".PREFIXO."atributo (id,entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa,inicializacao) VALUES ({$prox_attr},{$prox},'empresa','Empresa','smallint','',0,'16',0,4,0,'session.empresa');";
							$conn->exec($sql_empresa);
							$prox_attr++;
						}
						if ($criarauth == 1){
							$sql_auth = "INSERT INTO ".PREFIXO."atributo (id,entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa) VALUES ({$prox_attr},{$prox},'auth','Auth','varchar','45',0,'16',0,null,0);";
							$conn->exec($sql_auth);
							$prox_attr++;
							
							$sql_auth0 = "INSERT INTO ".PREFIXO."atributo (id,entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa) VALUES ({$prox_attr},{$prox},'auth0','Auth0','varchar','45',0,'16',0,null,0);";
							$conn->exec($sql_auth0);
							$prox_attr++;
						}					
					}
				}
			}else{
				// ID Última entidade
				$linha_ultimo 	= $conn->query("SELECT MAX(id)+1 id FROM ".PREFIXO."entidade")->fetchAll();
				$prox 			= $linha_ultimo[0]['id'];
				$sql 			= "INSERT INTO entidade (id,nome,descricao,exibirmenuadministracao,exibircabecalho,ncolunas,atributogeneralizacao,exibirlegenda,registrounico,carregarlibjavascript,tipoaba) VALUES ({$prox},'{$nome}','{$descricao}',{$exibirmenuadministracao},{$exibircabecalho},{$ncolunas},{$atributogeneralizacao},{$exibirlegenda},{$registrounico},{$carregarlibjavascript},'{$tipoaba}');";
				$query 			= $conn->query($sql);
			}	
		}else{
			if ($bancodados == "mysql"){
				$sql_ent_nome 		= "SELECT nome FROM " . PREFIXO ."entidade WHERE id = " . $id;
				$linha_ent_nome 	= $conn->query($sql_ent_nome)->fetchAll();
				if ($linha_ent_nome[0]["nome"] != $nome){				
					$sql_update = "RENAME TABLE {$linha_ent_nome[0]["nome"]} TO {$nome};";
				}				
			}			

			$prox 	= $_POST["id"];
			$sql 	= "UPDATE ".PREFIXO."entidade SET nome = '{$nome}' , descricao = '{$descricao}' , ncolunas = {$ncolunas} , exibirmenuadministracao = {$exibirmenuadministracao} , exibircabecalho = {$exibircabecalho} , campodescchave = {$campodescchave} , atributogeneralizacao = {$atributogeneralizacao} , exibirlegenda = {$exibirlegenda} , registrounico = {$registrounico} , carregarlibjavascript = {$carregarlibjavascript}, tipoaba = '{$tipoaba}' WHERE id = ".$_POST['id'].";";
			$query 	= $conn->query($sql);
		}
		header("Location: criarEntidade.php?entidade=" . $prox . "&" . getURLParamsProject());
	}

	if (isset($_GET["entidade"])){
		$sql = "SELECT nome,descricao,exibirmenuadministracao,exibircabecalho,ncolunas,campodescchave,atributogeneralizacao,exibirlegenda,registrounico,carregarlibjavascript,tipoaba FROM ".PREFIXO."entidade WHERE id = {$id};";

		$query = $conn->query($sql);
		foreach ($query->fetchAll() as $linha){
			$nome 						= executefunction("utf8charset",array($linha["nome"]));
			$descricao 					= executefunction("utf8charset",array($linha["descricao"]));
			$exibirmenuadministracao 	= $linha["exibirmenuadministracao"];
			$exibircabecalho 			= $linha["exibircabecalho"];
			$ncolunas 					= $linha["ncolunas"];
			$campodescchave 			= $linha["campodescchave"];
			$atributogeneralizacao 		= $linha["atributogeneralizacao"];
			$exibirlegenda 				= $linha["exibirlegenda"];
			$registrounico 				= $linha["registrounico"];
			$carregarlibjavascript 		= $linha["carregarlibjavascript"];
			$tipoaba					= $linha["tipoaba"];
		}
	}
?>
<html>
	<head>
		<title>Criar Entidade</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
			window.onload = function(){
				var id = "<?=$id?>";
				document.getElementById("id").value 					= "<?=$id?>";
				document.getElementById("nome").value 					= "<?=$nome?>";
				document.getElementById("descricao").value 				= "<?=$descricao?>";
				document.getElementById("ncolunas").value 				= "<?=$ncolunas?>";
				document.getElementById("campodescchave").value 		= "<?=$campodescchave?>";
				document.getElementById("atributogeneralizacao").value 	= "<?=$atributogeneralizacao?>";

				if ("<?=$id?>" == ""){
					document.getElementById("exibirlegenda").checked 			= true;
					document.getElementById("exibircabecalho").checked 			= true;
					document.getElementById("exibirmenuadministracao").checked 	= false;
					document.getElementById("registrounico").checked 			= false;
					document.getElementById("carregarlibjavascript").checked 	= true;					
					document.getElementById("criarempresa").checked 			= false;
					document.getElementById("criarprojeto").checked 			= false;
					document.getElementById("criarauth").checked 				= false;
				}else{
					document.getElementById("exibirmenuadministracao").checked 	= (<?=(int)$exibirmenuadministracao?>==0)?false:true;
					document.getElementById("exibirlegenda").checked 			= (<?=(int)$exibirlegenda?>==0)?false:true;
					document.getElementById("registrounico").checked 			= (<?=(int)$registrounico?>==0)?false:true;
					document.getElementById("carregarlibjavascript").checked 	= (<?=(int)$carregarlibjavascript?>==0)?false:true;					
					document.getElementById("exibircabecalho").checked 			= (<?=(int)$exibircabecalho?>==0)?false:true;
				}
				setTipoAba();
			}

			function setTipoAba(){
				switch('<?=$tipoaba?>'){
					case 'tabs':
						document.getElementById("aba-tabs").checked = true;
						document.getElementById("aba-pills").checked = false;
					break;
					case 'pills':
						document.getElementById("aba-tabs").checked = false;
						document.getElementById("aba-pills").checked = true;
					break;
					default:
						document.getElementById("aba-tabs").checked = true;
						document.getElementById("aba-pills").checked = false;
				}

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
					<form action="criarEntidade.php" method="post">
						<legend>
							Entidade
						</legend>
						<fieldset>
							<input type="hidden" id="currentproject" name="currentproject" value="<?=$_SESSION["currentproject"]?>">

							<div class="form-group">
							<label for="nome">ID</label>
								<div class="input-group">
									<input type="text" class="form-control" id="id" name="id" readonly />
									<span class="input-group-btn">
										<button class="btn btn-info" type="button" id="btn-edit-id-entidade">
											<span class="fas fa-pencil-alt" aria-hidden="true"></span>
										</button>
									</span>
								</div>
							</div>		

							<div class="form-group">
								<label for="nome">Nome</label>
								<input type="text" name="nome" id="nome" class="form-control"> 
							</div>
							<div class="form-group">
								<label for="descricao">Descrição</label>
								<input type="text" name="descricao" id="descricao" class="form-control"> 
							</div>
							<div class="form-group">
								<label for="ncolunas">Colunas</label>
								<small>( N&uacute;mero de colunas na tela de cadastro do CRUD )</small>
								<select id="ncolunas" name="ncolunas" class="form-control">
									<option value="1" selected>1</option>
									<option value="2">2</option>
									<option value="3">3</option>
								</select>								
							</div>
							<div class="form-group">
								<label for="campodescchave">Campo Descri&ccedil;&atilde;o</label>
								<small>(Chave Estrangeira)</small>
								<select id="campodescchave" name="campodescchave" class="form-control">
									<option value="0" selected>Nenhum Selecionado</option>
									<?php 
										if ($id!=""){
											$sql = "SELECT id,descricao FROM ".PREFIXO."atributo WHERE entidade = " . $id;
											$query = $conn->query($sql);
											foreach($query->fetchAll() as $linha){
												echo '<option value="'.$linha["id"].'">'.executefunction("utf8charset",array($linha["descricao"])).'</option>';
											}						
										}					
									?>
								</select>			
							</div>
							<div class="form-group">
								<label for="atributogeneralizacao">Campo Relacionamento Generalização</label>
								<select id="atributogeneralizacao" name="atributogeneralizacao" class="form-control">
									<option value="0" selected>Nenhum Selecionado</option>
									<?php 
										if ($id!=""){						
											$sql = "SELECT id,descricao FROM ".PREFIXO."atributo WHERE entidade = " . $id;
											$query = $conn->query($sql);
											foreach($query->fetchAll() as $linha){
												echo '<option value="'.$linha["id"].'">'.executefunction("utf8charset",array($linha["descricao"])).'</option>';						
											}						
										}					
									?>
								</select>			
							</div>							
							<div class="checkbox">
								<label for="exibirmenuadministracao">
									<input type="checkbox" name="exibirmenuadministracao" id="exibirmenuadministracao"> Exibir no menu "Administração"
								</label>
							</div>
							<div class="checkbox">
								<label for="exibircabecalho">
									<input type="checkbox" name="exibircabecalho" id="exibircabecalho">Exibir cabeçalho no CRUD controller
								</label>
							</div>
							<div class="checkbox">
								<label for="exibirlegenda">
									<input type="checkbox" name="exibirlegenda" id="exibirlegenda" checked>Exibir legenda no CRUD controller
								</label>
							</div>
							<?php
								if ($id == ""){
							?>
							<div class="checkbox">
								<label for="criarprojeto">
									<input type="checkbox" name="criarprojeto" id="criarprojeto" checked>Criar atributo <strong>PROJETO</strong>.
								</label>
							</div>							
							<div class="checkbox">
								<label for="criarempresa">
									<input type="checkbox" name="criarempresa" id="criarempresa" checked>Criar atributo <strong>EMPRESA</strong>.
								</label>
							</div>
							<div class="checkbox">
								<label for="criarauth">
									<input type="checkbox" name="criarauth" id="criarauth">Criar atributo <strong>AUTH e AUTH0</strong>.
								</label>
							</div>
							<?php
								}
							?>	
							<div class="checkbox">
								<label for="registrounico">
									<input type="checkbox" name="registrounico" id="registrounico">Registro Único.
								</label>
							</div>
							<div class="checkbox">
								<label for="carregarlibjavascript">
									<input type="checkbox" name="carregarlibjavascript" id="carregarlibjavascript">Carregar Biblioteca JavaScript no CRUID
								</label>
							</div>
							<div class="checkbox">
								<label for="btnimportarcsv">
									<input type="checkbox" name="btnimportarcsv" id="btnimportarcsv">Exibir botão para importar registro em CSV
								</label>
							</div>							
							<div class="checkbox">
								<label for="btnexportarcsv">
									<input type="checkbox" name="btnexportarcsv" id="btnexportarcsv">Exibir botão para exportar registro em CSV
								</label>
							</div>
							<div class="checkbox">
								<label for="btnimprimirtodosregistroshtml">
									<input type="checkbox" name="btnimprimirtodosregistroshtml" id="btnimprimirtodosregistroshtml">Exibir botão para imprimir todos os registro em HTML
								</label>
							</div>
							<div class="checkbox">
								<label for="btnimprimirtodosregistrospdf">
									<input type="checkbox" name="btnimprimirtodosregistrospdf" id="btnimprimirtodosregistrospdf">Exibir botão para imprimir todos os registro em PDF
								</label>
							</div>

							<div class="form-group">
								<label class="control-label">Tipo de Aba: </label>
								<label class="radio-inline">
  									<input type="radio" name="tipoaba" id="aba-tabs" value="tabs" <?=($tipoaba=='tabs'?'checked':'')?>> Tabs
								</label>
								<label class="radio-inline">
  									<input type="radio" name="tipoaba" id="aba-pills" value="pills" <?=($tipoaba=='pills'?'checked':'')?>> Pills
								</label>
							</div>

							<button type="submit" class="btn btn-primary" >Salvar</button>							  
						</fieldset>	  
					</form>					
				</div>
			</div>
		</div>
		<script type="text/javascript">
			let is_edit_id = false;
			$('#btn-edit-id-entidade').click(function(){

				if (is_edit_id){
					alterarIdEntidade();
				}else{
					$('#id').removeAttr('disabled');
					$('#id').removeAttr('readonly');
					$('#id').focus();
					$(this).find('.fas').removeClass('fa-pencil-alt');
					$(this).find('.fas').addClass('fa-save');
					is_edit_id = true;
				}



			});

			function alterarIdEntidade()
			{
				$.ajax({
					url:"criarEntidade.php",
					data:{
						op:'alterar_id',
						entidade:<?=$entidade?>,
						entidade_new:$('#id').val(),
						currentproject:<?=$_SESSION["currentproject"]?>
					},
					success:function(){
						location.href = "criarEntidade.php?currentproject=<?=$_SESSION["currentproject"]?>&entidade=" + $('#id').val();
					}
				});
			}
		</script>
	</body>
</html>