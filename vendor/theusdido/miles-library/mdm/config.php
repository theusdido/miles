<?php
	require 'conexao.php';
	require 'prefixo.php';	
	include 'funcoes.php';	
	
	$urlupload = $urlrequisicoes = $urlloadform = $enderecofiltro = $urlpesquisafiltro = $urlexcluirregistros = $linguagemprogramacao = $bancodados = "";	
	$urlinicializacao = $urlloadgradededados = $urlloading = $urlmenu = $pathfileupload = $pathfileuploadtemp = $tipogradedados = $urluploadform = "";
	$casasdecimais = 0;

	if (!empty($_POST)){
		$urlupload				= $_POST["urlupload"];
		$urlrequisicoes			= $_POST["urlrequisicoes"];
		$urlsaveform 			= $_POST["urlsaveform"];
		$urlloadform			= $_POST["urlloadform"];
		$urluploadform			= $_POST["urluploadform"];
		$urlpesquisafiltro		= $_POST["urlpesquisafiltro"];
		$urlenderecofiltro		= $_POST["urlenderecofiltro"];
		$urlexcluirregistros	= $_POST["urlexcluirregistros"];
		$urlinicializacao 		= $_POST["urlinicializacao"];
		$urlloadgradededados	= $_POST["urlloadgradededados"];
		$urlloading 			= $_POST["urlloading"];
		$urlrelatorio 			= $_POST["urlrelatorio"];
		$urlmenu				= $_POST["urlmenu"];
		$linguagemprogramacao 	= $_POST["linguagemprogramacao"];
		$bancodados				= $_POST["bancodados"];
		$pathfileupload			= $_POST["pathfileupload"];
		$pathfileuploadtemp		= $_POST["pathfileuploadtemp"];
		$tipogradedados			= $_POST["tipogradedados"];
		$casasdecimais			= $_POST["casasdecimais"];

		$sql = "UPDATE ".PREFIXO."config SET urlupload = '{$urlupload}' ,urlrequisicoes = '{$urlrequisicoes}', urlsaveform = '{$urlsaveform}' 
		, urlloadform = '{$urlloadform}', urlpesquisafiltro = '{$urlpesquisafiltro}', urlenderecofiltro = '{$urlenderecofiltro}'
		, urlexcluirregistros = '{$urlexcluirregistros}', linguagemprogramacao = '{$linguagemprogramacao}', bancodados = '{$bancodados}' 
		, urlinicializacao = '{$urlinicializacao}', urlloadgradededados = '{$urlloadgradededados}', urlloading = '{$urlloading}'
		, urlrelatorio = '{$urlrelatorio}' , urlmenu = '{$urlmenu}', pathfileupload = '{$pathfileupload}' , pathfileuploadtemp = '{$pathfileuploadtemp}'
		, tipogradedados = '{$tipogradedados}' , urluploadform = '{$urluploadform}', casasdecimais = {$casasdecimais}
		
		WHERE id=1; ";
		$query = $conn->query($sql);
		if ($query){
			//addLog($sql);
		}
		header("Location: config.php");
	}
	
	$sql = "SELECT * FROM ".PREFIXO."config WHERE id=1;";
	$query = $conn->query($sql);
	foreach ($query->fetchAll() as $linha){
		$urlupload 				= $linha["urlupload"];
		$urlrequisicoes 		= $linha["urlrequisicoes"];
		$urlsaveform 			= $linha["urlsaveform"];
		$urlloadform 			= $linha["urlloadform"];
		$urluploadform			= $linha["urluploadform"];
		$urlpesquisafiltro		= $linha["urlpesquisafiltro"];
		$urlenderecofiltro		= $linha["urlenderecofiltro"];
		$urlexcluirregistros	= $linha["urlexcluirregistros"];
		$urlinicializacao 		= $linha["urlinicializacao"];
		$urlloadgradededados	= $linha["urlloadgradededados"];
		$urlloading 			= $linha["urlloading"];
		$urlrelatorio 			= $linha["urlrelatorio"];
		$urlmenu	 			= $linha["urlmenu"];
		$linguagemprogramacao 	= $linha["linguagemprogramacao"];
		$bancodados				= $linha["bancodados"];
		$pathfileupload			= $linha["pathfileupload"];
		$pathfileuploadtemp		= $linha["pathfileuploadtemp"];
		$tipogradedados			= $linha["tipogradedados"];
		$casasdecimais			= $linha["casasdecimais"];
	}
?>
<html>
	<head>
		<title>Configurações</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
			window.onload = function(){
				document.getElementById("urlupload").value 					= "<?=$urlupload?>";
				document.getElementById("urlrequisicoes").value 			= "<?=$urlrequisicoes?>";
				document.getElementById("urlsaveform").value 				= "<?=$urlsaveform?>";
				document.getElementById("urlloadform").value 				= "<?=$urlloadform?>";
				document.getElementById("urluploadform").value 				= "<?=$urluploadform?>";				
				document.getElementById("urlenderecofiltro").value 			= "<?=$urlenderecofiltro?>";
				document.getElementById("urlpesquisafiltro").value 			= "<?=$urlpesquisafiltro?>";
				document.getElementById("urlexcluirregistros").value 		= "<?=$urlexcluirregistros?>";
				document.getElementById("urlinicializacao").value 			= "<?=$urlinicializacao?>";
				document.getElementById("urlloadgradededados").value 		= "<?=$urlloadgradededados?>";
				document.getElementById("urlloading").value 				= "<?=$urlloading?>";
				document.getElementById("urlrelatorio").value 				= "<?=$urlrelatorio?>";
				document.getElementById("urlmenu").value 					= "<?=$urlmenu?>";
				document.getElementById("linguagemprogramacao").value 		= "<?=$linguagemprogramacao?>";
				document.getElementById("bancodados").value 				= "<?=$bancodados?>";
				document.getElementById("pathfileupload").value 			= "<?=$pathfileupload?>";
				document.getElementById("pathfileuploadtemp").value 		= "<?=$pathfileuploadtemp?>";
				document.getElementById("tipogradedados").value 			= "<?=$tipogradedados?>";
				document.getElementById("casasdecimais").value 				= "<?=$casasdecimais?>";
				
			}
		</script>
	</head>
	<body>
		<?php include 'menu_topo.php'; ?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-2">
					<?php						
						//include 'menu_entidade.php';
					?>
				</div>
				<div class="col-md-10">
					<form action="config.php" method="post">
						<legend>
							Configurações
						</legend>						
						<fieldset>
							<div class="form-group">
								<label for="urlrequisicoes">Requições - URL</label>
								<input type="text" name="urlrequisicoes" id="urlrequisicoes" class="form-control"> 
							</div>
							<div class="form-group">
								<label for="urlupload">Upload - URL</label>
								<input type="text" name="urlupload" id="urlupload" class="form-control"> 
							</div>
							<div class="form-group">
								<label for="urlsaveform">Salvar Formulário Padrão - URL</label>
								<input type="text" name="urlsaveform" id="urlsaveform" class="form-control"> 
							</div>
							<div class="form-group">
								<label for="urlloadform">Carregar Dados do Formulário - URL</label>
								<input type="text" name="urlloadform" id="urlloadform" class="form-control"> 
							</div>
							<div class="form-group">
								<label for="urluploadform">Enviar Arquivos do Formulário para UPLOAD - URL</label>
								<input type="text" name="urluploadform" id="urluploadform" class="form-control"> 
							</div>							
							<div class="form-group">
								<label for="urlpesquisafiltro">Carregar Pesquisa ( Filtro ) - URL</label>
								<input type="text" name="urlpesquisafiltro" id="urlpesquisafiltro" class="form-control">
							</div>
							<div class="form-group">
								<label for="urlenderecofiltro">Endereço ( Filtro ) - URL</label>
								<input type="text" name="urlenderecofiltro" id="urlenderecofiltro" class="form-control">
							</div>
							<div class="form-group">
								<label for="urlexcluirregistros">Excluir Registros - URL</label>
								<input type="text" name="urlexcluirregistros" id="urlexcluirregistros" class="form-control">
							</div>
							<div class="form-group">
								<label for="urlinicializacao">Dados de Inicialização - URL</label>
								<input type="text" name="urlinicializacao" id="urlinicializacao" class="form-control">
							</div>
							<div class="form-group">
								<label for="urlloadgradededados">Carrega Grade de Dados - URL</label>
								<input type="text" name="urlloadgradededados" id="urlloadgradededados" class="form-control">
							</div>
							<div class="form-group">
								<label for="urlloading">Loader - URL</label>
								<input type="text" name="urlloading" id="urlloading" class="form-control">
							</div>
							<div class="form-group">
								<label for="urlmenu">Menu - URL</label>
								<input type="text" name="urlmenu" id="urlmenu" class="form-control">
							</div>
							<div class="form-group">
								<label for="urlrelatorio">Relatório - URL</label>
								<input type="text" name="urlrelatorio" id="urlrelatorio" class="form-control">
							</div>
							<div class="form-group">
								<label for="nome">Linguagem de Programação</label>
								<select id="linguagemprogramacao" name="linguagemprogramacao" class="form-control">
									<option value="csp">CSP (Caché Server Page)</option>
									<option value="php">PHP (Personal Home Page)</option>
								</select>
							</div>
							<div class="form-group">
								<label for="nome">Banco de Dados</label>
								<select id="bancodados" name="bancodados" class="form-control">
									<option value="cache">Caché</option>
									<option value="mysql">MySQL</option>
								</select>
							</div>
							<div class="form-group">
								<label for="pathfileupload">Diretório de Arquivos ( Upload )</label>
								<input type="text" name="pathfileupload" id="pathfileupload" class="form-control">
							</div>
							<div class="form-group">
								<label for="pathfileuploadtemp">Diretório Temporário de Arquivos ( Upload )</label>
								<input type="text" name="pathfileuploadtemp" id="pathfileuploadtemp" class="form-control">
							</div>
							<div class="form-group">
								<label for="nome">Tipo de Grade de Dados Padrão</label>
								<select id="tipogradedados" name="tipogradedados" class="form-control">
									<option value="table">TABLE</option>
									<option value="panel">PANEL</option>
								</select>
							</div>
							<div class="form-group">
								<label for="casasdecimais">Quantidade de casas decimais</label>
								<input type="text" name="casasdecimais" id="casasdecimais" class="form-control" />
							</div>
							<button type="submit" class="btn btn-primary" >Salvar</button>							  
						</fieldset>	  
					</form>					
				</div>
			</div>
		</div>		
	</body>
</html>