<?php
	require 'conexao.php';
	require 'prefixo.php';	
	include 'log.php';	
	
	$id = $nome = $descricao = $exibirmenuadministracao = $exibircabecalho = $ncolunas = $campodescchave = $atributogeneralizacao = $exibirlegenda = "";
	
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
		$descricao 					= utf8charset($_POST["descricao"]);
		$ncolunas					= ($_POST["ncolunas"]=='')?0:$_POST["ncolunas"];
		$exibirmenuadministracao 	= isset($_POST["exibirmenuadministracao"])?1:0;
		$exibircabecalho 			= isset($_POST["exibircabecalho"])?1:0;
		$campodescchave				= ($_POST["campodescchave"]=='')?'null':$_POST["campodescchave"];
		$atributogeneralizacao		= ($_POST["atributogeneralizacao"]=='')?'null':$_POST["atributogeneralizacao"];
		$exibirlegenda				= isset($_POST["exibirlegenda"])?1:0;
		$criarprojeto				= isset($_POST["criarprojeto"])?1:0;
		$criarempresa				= isset($_POST["criarempresa"])?1:0;
		$criarauth					= isset($_POST["criarauth"])?1:0;
		
		
		if ($_POST["id"] == ""){
			$nome = PREFIXO . $nome;
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
			$criar = mysql_query($sql);
			if ($criar){
				addLog($sql);
				// ID Última entidade
				$query_ultimo = mysql_query("SELECT MAX(id)+1 id FROM ".getSystemPREFIXO()."entidade");
				$linha_ultimo = mysql_fetch_array($query_ultimo);
				$prox = $linha_ultimo['id'];
				$sql = "INSERT INTO ".getSystemPREFIXO()."entidade (id,nome,descricao,exibirmenuadministracao,exibircabecalho,ncolunas,atributogeneralizacao,exibirlegenda) VALUES ({$prox},'{$nome}','{$descricao}',{$exibirmenuadministracao},{$exibircabecalho},{$ncolunas},{$atributogeneralizacao},{$exibirlegenda});";
				$query = mysql_query($sql);
				if($query){					
					addLog($sql);
					// ID Última entidade
					$query_ultimo_attr = mysql_query("SELECT MAX(id)+1 id FROM ".getSystemPREFIXO()."atributo");
					$linha_ultimo_attr = mysql_fetch_array($query_ultimo_attr);
					$prox_attr = $linha_ultimo_attr['id'];
					
					if ($criarprojeto == 1){
						$sql_projeto = "INSERT INTO ".getSystemPREFIXO()."atributo (id,entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa) VALUES ({$prox_attr},{$prox},'projeto','Projeto','smallint','',0,'16',0,3,0);";
						mysql_query($sql_projeto);
						addLog($sql_projeto);
						$prox_attr++;
					}
					if ($criarempresa == 1){
						$sql_empresa = "INSERT INTO ".getSystemPREFIXO()."atributo (id,entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa) VALUES ({$prox_attr},{$prox},'empresa','Empresa','smallint','',0,'16',0,4,0);";
						mysql_query($sql_empresa);
						addLog($sql_empresa);
						$prox_attr++;
					}
					if ($criarauth == 1){
						$sql_auth = "INSERT INTO ".getSystemPREFIXO()."atributo (id,entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa) VALUES ({$prox_attr},{$prox},'auth','Auth','varchar','45',0,'16',0,null,0);";
						mysql_query($sql_auth);
						addLog($sql_auth);
						$prox_attr++;
						
						$sql_auth0 = "INSERT INTO ".getSystemPREFIXO()."atributo (id,".PREFIXO."entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa) VALUES ({$prox_attr},{$prox},'auth0','Auth0','varchar','45',0,'16',0,null,0);";
						mysql_query($sql_auth0);
						addLog($sql_auth0);
						$prox_attr++;
					}					
				}
			}
		}else{
			$sql_ent_nome = "SELECT nome FROM " . PREFIXO ."entidade WHERE id = " . $id;
			$query_ent_nome = mysql_query($sql_ent_nome);
			$linha_ent_nome = mysql_fetch_array($query_ent_nome);
			$sql_update = "RENAME TABLE {$linha_ent_nome["nome"]} TO {$nome};";			
			if (mysql_query($sql_update)){
				addLog($sql_update);
			}	
			$prox = $_POST["id"];
			$sql = "UPDATE ".getSystemPREFIXO()."entidade SET nome = '{$nome}' , descricao = '{$descricao}' , ncolunas = {$ncolunas} , exibirmenuadministracao = {$exibirmenuadministracao} , exibircabecalho = {$exibircabecalho} , campodescchave = {$campodescchave} , atributogeneralizacao = {$atributogeneralizacao} , exibirlegenda = {$exibirlegenda} WHERE id = {$_POST['id']};";
			$query = mysql_query($sql);
			if ($query){
				addLog($sql);
			}
			
		}
		header("Location: criarEntidade.php?entidade=" . $prox);
	}
	if (isset($_GET["entidade"])){		
		$sql = "SELECT nome,descricao,exibirmenuadministracao,exibircabecalho,ncolunas,campodescchave,atributogeneralizacao,exibirlegenda FROM ".getSystemPREFIXO()."entidade WHERE id = {$id};";

		$query = mysql_query($sql);
		if ($linha = mysql_fetch_array($query)){			
			$nome = utf8_encode($linha["nome"]);			
			$descricao = utf8_encode($linha["descricao"]);
			$exibirmenuadministracao = $linha["exibirmenuadministracao"];
			$exibircabecalho = $linha["exibircabecalho"];
			$ncolunas = $linha["ncolunas"];
			$campodescchave = $linha["campodescchave"];
			$atributogeneralizacao = $linha["atributogeneralizacao"];
			$exibirlegenda = $linha["exibirlegenda"];
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
				document.getElementById("id").value = "<?=$id?>";
				document.getElementById("nome").value = "<?=$nome?>";
				document.getElementById("descricao").value = "<?=$descricao?>";				
				document.getElementById("exibirmenuadministracao").checked = (<?=(int)$exibirmenuadministracao?>==0)?false:true;					
				if (id == ""){
					document.getElementById("exibircabecalho").checked = true;
				}else{
					document.getElementById("exibircabecalho").checked = (<?=(int)$exibircabecalho?>==0)?false:true;	
				}
				document.getElementById("ncolunas").value = "<?=$ncolunas?>";
				document.getElementById("campodescchave").value = "<?=$campodescchave?>";
				
				document.getElementById("atributogeneralizacao").value = "<?=$atributogeneralizacao?>";
				if ("<?=$id?>" == ""){
					document.getElementById("exibirlegenda").checked = true;
				}else{
					document.getElementById("exibirlegenda").checked = (<?=(int)$exibirlegenda?>==0)?false:true;
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
							<input type="hidden" id="id" name="id">
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
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
								</select>								
							</div>
							<div class="form-group">
								<label for="campodescchave">Campo Descri&ccedil;&atilde;o</label>
								<small>(Chave Estrangeira)</small>
								<select id="campodescchave" name="campodescchave" class="form-control">
									<option value="null" selected></option>
									<?php 
										if ($id!=""){						
											$sql = "SELECT id,descricao FROM ".getSystemPREFIXO()."atributo WHERE ".getSystemPREFIXO()."entidade = " . $id;
											$query = mysql_query($sql);
											while ($linha = mysql_fetch_array($query)){
												echo '<option value="'.$linha["id"].'">'.utf8_encode($linha["descricao"]).'</option>';						
											}						
										}					
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="atributogeneralizacao">Campo Relacionamento Generalização</label>
								<select id="atributogeneralizacao" name="atributogeneralizacao" class="form-control">
									<option value="null" selected></option>
									<?php 
										if ($id!=""){						
											$sql = "SELECT id,descricao FROM ".getSystemPREFIXO()."atributo WHERE ".getSystemPREFIXO()."entidade = " . $id;
											$query = mysql_query($sql);
											while ($linha = mysql_fetch_array($query)){
												echo '<option value="'.$linha["id"].'">'.utf8_encode($linha["descricao"]).'</option>';						
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
							<button type="submit" class="btn btn-primary" >Salvar</button>							  
						</fieldset>	  
					</form>					
				</div>
			</div>
		</div>		
	</body>
</html>