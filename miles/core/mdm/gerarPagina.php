<?php
	require 'conexao.php';
	require 'prefixo.php';
	require 'funcoes.php';

	if (isset($_POST["op"])){
		if ($_POST["op"] == "criarpagina");{
			if (!isset($_SESSION["userid"])){
				echo 'Sessão expirou';
				exit;
			}
			$sql = "UPDATE " . PREFIXO . "entidade SET htmlpagefile = '".addslashes($_POST["html"])."' WHERE id={$_POST["entidade"]};";
			$query = $conn->query($sql);

			if ($query){
				$path = '/' . $_COOKIE["path_files_cadastro"];
				$fp_teste = fopen($path . '/teste.txt','a');
				fwrite($fp_teste,'teste');
				fclose($fp_teste);
				exit;

				// Documentação
				$datacriacaodoc = "* @Data de Criacao: ".date("d/m/Y H:i:s");
				$authordoc = "* @Criado por: ".$_SESSION["username"].", @id: ".$_SESSION["userid"];
				$paginadoc = "* @Página: {$_POST["entidade"]} - {$_POST["descricaoentidade"]} [{$_POST["nomeentidade"]}]";				

				// Cria o arquivo HTML
				$fp = fopen($path . $_POST["filename"] ,'w');
				fwrite($fp,htmlespecialcaracteres_($_POST["html"],1));
				fclose($fp);

				// Arquivo HTML do Componente Angular
				$fp = fopen("../../../../miles/angularjs/aplicacao/src/app/pages/crm/pessoa/pessoa.component.html" ,'w');
				fwrite($fp,htmlespecialcaracteres_(
				'
					<div class="main-content">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-12">
									<div class="card">
										<div class="card-header card-header-danger">
											<h4 class="card-title">PESSOA</h4>
											<p class="card-category">Cadastro Único de Locador, Locatário, Fiador.</p>
										</div>
										<div class="card-body">'.$_POST["html"].'</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				'
				,1));
				fclose($fp);

				// Cria o arquivo HTML Embutido Dinâmico
				$dhtmlFile = $path . "/" . $_POST["nomeentidade"] . ".htm";
				if (!file_exists($dhtmlFile)){
					$fp = fopen($dhtmlFile,'w');
					fwrite($fp,"<!--\n * HTML Personalizado \n {$datacriacaodoc} \n {$authordoc} \n {$paginadoc} \n\n Escreve seu código HTML personalizado aqui! \n-->\n");
					fclose($fp);
				}	
				
				// Cria o arquivo CSS
				$cssFile = $path . "/" . $_POST["filenamecss"];
				if (!file_exists($cssFile)){
					$fp = fopen($cssFile ,'w');
					fwrite($fp,"/*\n * CSS Personalizado \n {$datacriacaodoc} \n {$authordoc} \n {$paginadoc} \n\n Escreve seu código CSS personalizado aqui! \n*/\n");
					fclose($fp);
				}
				
				// Cria o arquivo JS
				$jsFile = $path . "/" . $_POST["filenamejs"];
				if (!file_exists($jsFile)){
					$fp = fopen($jsFile ,'w');
					fwrite($fp,"/*\n * JS Personalizado \n {$datacriacaodoc} \n {$authordoc} \n {$paginadoc} \n */\n\n");
					fwrite($fp,"// Invocado ao clicar no botão Novo");
					fwrite($fp,"\n");
					fwrite($fp,"function beforeNew(){");
					fwrite($fp,"\n\t var btnnew = arguments[0];");
					fwrite($fp,"\n");
					fwrite($fp,"}");
					fwrite($fp,"\n");
					fwrite($fp,"// Executa após o carregamento padrão de uma novo registro");
					fwrite($fp,"\n");
					fwrite($fp,"function afterNew(){");					
					fwrite($fp,"\n\t var contexto = arguments[0];");
					fwrite($fp,"\n");					
					fwrite($fp,"}");
					fwrite($fp,"\n");
					fwrite($fp,"// Invocado ao clicar no botão Salvar");
					fwrite($fp,"\n");
					fwrite($fp,"function beforeSave(){");
					fwrite($fp,"\n\t var btnsave = arguments[0];");
					fwrite($fp,"\n");
					fwrite($fp,"}");
					fwrite($fp,"\n");
					fwrite($fp,"// Executa após o salvamento padrão de um registro");
					fwrite($fp,"\n");
					fwrite($fp,"function afterSave(){");
					fwrite($fp,"\n\t var fp = arguments[0];");
					fwrite($fp,"\n\t var btnsave = arguments[1];");
					fwrite($fp,"\n");
					fwrite($fp,"}");
					fwrite($fp,"\n");
					fwrite($fp,"// Invocado ao clicar no botão Editar ");
					fwrite($fp,"\n");
					fwrite($fp,"function beforeEdit(){");
					fwrite($fp,"\n\t var entidade = arguments[0];");
					fwrite($fp,"\n\t var registro = arguments[1];");
					fwrite($fp,"\n");
					fwrite($fp,"}");
					fwrite($fp,"\n");
					fwrite($fp,"// Executa após o carregamento padrão da edição de registro");
					fwrite($fp,"\n");
					fwrite($fp,"function afterEdit(){");
					fwrite($fp,"\n\t var entidade = arguments[0];");
					fwrite($fp,"\n\t var registro = arguments[1];");					
					fwrite($fp,"\n");
					fwrite($fp,"}");
					fwrite($fp,"\n");
					fwrite($fp,"// Invocado ao clicar no botão Voltar");
					fwrite($fp,"\n");
					fwrite($fp,"function beforeBack(){");
					fwrite($fp,"\n\t var btnback = arguments[0];");
					fwrite($fp,"\n");
					fwrite($fp,"}");
					fwrite($fp,"\n");
					fwrite($fp,"// Executa após a ação de voltar a tela anterior");
					fwrite($fp,"\n");
					fwrite($fp,"function afterBack(){");
					fwrite($fp,"\n\t var btnback = arguments[0];");
					fwrite($fp,"\n");
					fwrite($fp,"}");
					fwrite($fp,"\n");
					fwrite($fp,"// Invocado ao clicar no botão Deletar");
					fwrite($fp,"\n");
					fwrite($fp,"function beforeDelete(){");
					fwrite($fp,"\n");
					fwrite($fp,"}");
					fwrite($fp,"\n");
					fwrite($fp,"// Executa após a exclusão de um registro");
					fwrite($fp,"\n");
					fwrite($fp,"function afterDelete(){");
					fwrite($fp,"\n");
					fwrite($fp,"}");
					fwrite($fp,"\n");
					fwrite($fp,"if (typeof funcionalidade === 'undefined') var funcionalidade = 'cadastro';");
					fwrite($fp,"\n\n/* \n ### Escreva seu código JavaScript abaixo dessa linha ou dentro das funções acima ### \n*/\n");
					fclose($fp);
				}
				
				// Move os arquivos para sua respectiva pasta
				$path_files_cadastro = "../../projects/".$_SESSION["currentproject"]."/files/cadastro/"; #Fora do escopo do sistema para recepurar a constante PATH_FILES_CADASTRO
				if (!file_exists($path_files_cadastro)){
					echo 'Diretório não existe';
					exit;
				}
				$diretorio = dir($path_files_cadastro);
				while($arquivo = $diretorio -> read()){
					if ($arquivo != "" && $arquivo != "." && $arquivo != "..");{
						if (strpos($arquivo,'.') > 0){							
							copy($path_files_cadastro . $arquivo,$path . $arquivo);
							unlink($path_files_cadastro . $arquivo);
						}
					}
				}
			}else{
				echo 0;
			}
			exit;
		}
	}

	if (isset($_GET["t"])){
		$entidade = $_GET["t"];
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
?>
<html>
	<head>
		<title>HTML Code</title>
		<?php include 'head.php'; ?>
		<style type="text/css">
			#pagina-gerada{
				border:3px solid #EEE;
				float:left;
				width:100%;
				padding:15px;
			}
		</style>
	</head>
	<body>
		<?php include 'menu_topo.php'; ?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-2">
					<?php include 'menu_entidade.php'; ?>	
					<?php if ($id!=""); include 'menu_atributo.php'; ?>
				</div>
				<div class="col-md-10">
						<legend>
							HTML Code
						</legend>						
						<fieldset>
							<input type="hidden" id="id" name="id">
							<div class="form-group">
								<button id="gerar" name="gerar" type="button" class="btn btn-primary" style="float:right;margin-bottom:5px;width:10%;">Gerar</button>
								<div id="gravando-pagina"></div>
								<input type="text" id="filename" name="filename" class="form-control" value="<?php echo $linha[0]["nome"]; ?>.html" style="float:right;width:89%;margin-right:1%;"/>
								<input type="hidden" id="filenamejs" name="filenamejs" class="form-control" value="<?php echo $linha[0]["nome"]; ?>.js" />
								<input type="hidden" id="filenamecss" name="filenamecss" value="<?php echo $linha[0]["nome"]; ?>.css" />
								<!-- <textarea id="codigo" name="codigo" style="width:100%;height:400px;" class="form-control"> -->
								<div id="pagina-gerada"></div>
							</div>
						</fieldset>						
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	$("#gerar").click(function(){
		$.ajax({
			url:"../../index.php",
			data:{
				controller:"gerarpagina",
				entidade:<?=$entidade?>,
				principal:true,
				currentproject:"<?=$_SESSION["currentproject"]?>"
			},
			complete:function(retorno){
				gerarPagina(retorno.responseText);
				gerarComponenteAngularJS();
			},
			beforeSend:function(){
				$("#pagina-gerada").html('<img src="../tema/padrao/loading2.gif" id="loading" style="float:left;margin-left:48%;" />');
			}
		});
	});
	
	function gerarPagina(html){
		$.ajax({
			url:"../../index.php?controller=mdm/componente&currentproject=<?=$_SESSION["currentproject"]?>",
			type:"POST",
			data:{
				op:"criarpagina",
				html:html,
				filename:$("#filename").val(),
				filenamejs:$("#filenamejs").val(),
				filenamecss:$("#filenamecss").val(),
				entidade:"<?=$entidade?>",
				nomeentidade:"<?=$nomeEntidadePrincipal?>",
				descricaoentidade:"<?=$descricaoEntidadePrincipal?>",
				urlupload:$("#urlupload").val()
			},
			complete:function(){
				$("#pagina-gerada").html("Carregou");;
			}
		});
	}

	function gerarComponenteAngularJS(){
		$.ajax({
			url:"../../index.php",
			data:{
				controller:"gerarcomponenteangularjs",
				entidade:<?=$entidade?>,
				principal:true,
				currentproject:"<?=$_SESSION["currentproject"]?>"
			}
		});
	}
</script>
<?php
	function write($parms,$retorno=false){
		$cur_encoding = mb_detect_encoding($parms);
		if($cur_encoding == "UTF-8" && mb_check_encoding($parms,'UTF-8')){
			if ($retorno) return $parms;
			else echo $parms;
		}elseif($cur_encoding == "ISO 8859-1" && mb_check_encoding($parms,'ISO 8859-1')){
			if ($retorno) return $parms;
			else echo utf8charset($parms);
		}else{
			if ($retorno) return $parms;
			else echo utf8charset($parms);
		}
	}
	
// Função que retorna o texto em formato HTML Especial
function htmlespecialcaracteres_($string,$tipo){
	
	$html = array('Á'		,'á'		,'Â'		,'â'		,'À'		,'à'		,'Å'		,'å'		,'Ã'		,'ã'		,'Ä'		,'ä'		,'Æ'		,'æ'		,'É'		,'é'		,'Ê'		,'ê'		,'È'		,'è'		,'Ë'		,'ë'		,'Ð'		,'ð'		,'Í'		,'í'		,'Î'		,'î'		,'Ì'		,'ì'		,'Ï'		,'ï'		,'Ó'		,'ó'		,'Ô'		,'ô'		,'Ò'			,'ò'		,'Ø'		,'ø'		,'Õ'		,'õ'		,'Ö'		,'ö'		,'Ú'		,'ú'		,'Û'		,'û'		,'Ù'		,'ù'		,'Ü'		,'ü'		,'Ç'		,'ç'		,'Ñ'		,'ñ'		,'®'	,'©'	,'Ý'		,'ý'		,'Þ'		,'þ'		,'ß'		,'º'		,'ª');
	$char = array('&Aacute;','&aacute;'	,'&Acirc;'	,'&acirc;'	,'&Agrave;'	,'&agrave;'	,'&Aring;'	,'&aring;'	,'&Atilde;'	,'&atilde;'	,'&Auml;'	,'&auml;'	,'&AElig;'	,'&aelig;'	,'&Eacute;'	,'&eacute;'	,'&Ecirc;'	,'&ecirc;'	,'&Egrave;'	,'&egrave;'	,'&Euml;'	,'&euml;'	,'&ETH;'	,'&eth;'	,'&Iacute;'	,'&iacute;'	,'&Icirc;'	,'&icirc;'	,'&Igrave;'	,'&igrave;'	,'&Iuml;'	,'&iuml;'	,'&Oacute;'	,'&oacute;'	,'&Ocirc;'	,'&ocirc;'	,'&Ograve;'		,'&ograve;'	,'&Oslash;'	,'&oslash;'	,'&Otilde;'	,'&otilde;'	,'&Ouml;'	,'&ouml;'	,'&Uacute;'	,'&uacute;'	,'&Ucirc;'	,'&ucirc;'	,'&Ugrave;'	,'&ugrave;'	,'&Uuml;'	,'&uuml;'	,'&Ccedil;'	,'&ccedil;'	,'&Ntilde;'	,'&ntilde;'	,'&reg;','&copy;','&Yacute;','&yacute;'	,'&THORN;'	,'&thorn;'	,'&szlig;'	,'&ordm;'	,'&ordf;');
	
	$string = str_replace($html,$char,$string);
	
	return $string;
}	
?>