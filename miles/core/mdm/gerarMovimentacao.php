<?php
	require 'conexao.php';
	include_once 'log.php';
	require 'prefixo.php';
	
	if (isset($_POST["op"])){
		if ($_POST["op"] == "criarmovimentacao");{			

			$path = "../../" . $_COOKIE["path_files_movimentacao"];

			$fp = fopen($path . $_POST["filename"] ,'w');
			fwrite($fp,htmlespecialcaracteres_($_POST["html"],1));
			fclose($fp);			
			
			$jsFile = $path . "/" . $_POST["filenamejs"];
			if (!file_exists($jsFile)){
				$fp = fopen($jsFile ,'w');
				fwrite($fp,"// JS da Movimentação");
				fwrite($fp,"// Invocado ao clicar no botão Novo");
				fwrite($fp,"\n");
				fwrite($fp,"function beforeNew(){");
				fwrite($fp,"\n");
				fwrite($fp,"}");
				fwrite($fp,"\n");
				fwrite($fp,"// Executa após o carregamento padrão de uma novo registro");
				fwrite($fp,"\n");
				fwrite($fp,"function afterNew(){");
				fwrite($fp,"\n");
				fwrite($fp,"}");
				fwrite($fp,"\n");
				fwrite($fp,"// Invocado ao clicar no botão Salvar");
				fwrite($fp,"\n");
				fwrite($fp,"function beforeSave(){");
				fwrite($fp,"\n");
				fwrite($fp,"}");
				fwrite($fp,"\n");
				fwrite($fp,"// Executa após o salvamento padrão de um registro");
				fwrite($fp,"\n");
				fwrite($fp,"function afterSave(){");
				fwrite($fp,"\n");
				fwrite($fp,"}");
				fwrite($fp,"\n");
				fwrite($fp,"// Invocado ao clicar no botão Editar ");
				fwrite($fp,"\n");
				fwrite($fp,"function beforeEdit(){");
				fwrite($fp,"\n");
				fwrite($fp,"}");
				fwrite($fp,"\n");
				fwrite($fp,"// Executa após o carregamento padrão da edição de registro");
				fwrite($fp,"\n");
				fwrite($fp,"function afterEdit(){");
				fwrite($fp,"\n");
				fwrite($fp,"}");
				fwrite($fp,"\n");
				fwrite($fp,"// Invocado ao clicar no botão Voltar");
				fwrite($fp,"\n");
				fwrite($fp,"function beforeBack(){");
				fwrite($fp,"\n");
				fwrite($fp,"}");
				fwrite($fp,"\n");
				fwrite($fp,"// Executa após a ação de voltar a tela anterior");
				fwrite($fp,"\n");
				fwrite($fp,"function afterBack(){");
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
				fclose($fp);
			}

			// Move os arquivos para sua respectiva pasta
			$path_files_cadastro = "../../projects/".$config["CURRENT_PROJECT"]."/files/movimentacao/"; #Fora do escopo do sistema para recepurar a constante PATH_FILES_CADASTRO

			$diretorio = dir($path_files_cadastro);
			while($arquivo = $diretorio -> read()){
				if ($arquivo != "" && $arquivo != "." && $arquivo != "..");{
					if (strpos($arquivo,'.') > 0){							
						copy($path_files_cadastro . $arquivo,$path . $arquivo);
						unlink($path_files_cadastro . $arquivo);
					}
				}
			}
			exit;
		}
	}

	if (isset($_GET["t"])){
		$entidade = $_GET["t"];
	}

	$id = $_GET["id"];
	$entidade = $_GET["entidade"];
?>
<html>
	<head>
		<title>HTML Code</title>
		<?php include 'head.php' ?>
		<style type="text/css">
			#movimentacao-gerada{
				border:3px solid #EEE;
				float:left;
				width:100%;
				padding:15px;				
			}
		</style>
		<script type="text/javascript" src="../../lib/jquery/jquery.mask.js"></script>
		<script type="text/javascript" src="../../lib/jquery/jquery.maskMoney.js"></script>
	</head>
	<body>
		<?php include 'menu_topo.php'; ?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-2">
					<?php include 'menu_movimentacao.php'; ?>	
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
								<input type="hidden" id="filenamejs" name="filenamejs" class="form-control" value="<?php echo $linha[0]["nome"]; ?>.js" style="float:right;width:89%;margin-right:1%;"/>
								<!-- <textarea id="codigo" name="codigo" style="width:100%;height:400px;" class="form-control"> -->
								<div id="movimentacao-gerada"></div>
							</div>
						</fieldset>						
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	$("#gerar").click(function(){		
		$("#movimentacao-gerada").html('<img src="../tema/padrao/loading2.gif" id="loading" style="float:left;margin-left:48%;" />');
		$.ajax({
			url:"../../index.php?controller=gerarmovimentacao&id=<?=$id?>",
			complete:function(retorno){
				gerarMovimentacao(retorno.responseText);
			}
		});
		function gerarMovimentacao(html){
			$.ajax({
				url:"gerarMovimentacao.php?op=salvar",
				type:"POST",
				data:{
					op:"criarmovimentacao",
					html:html,
					filename:$("#filename").val(),
					filenamejs:$("#filenamejs").val(),
					entidade:"<?=$entidade?>",
					urlupload:$("#urlupload").val()
				},
				complete:function(){
					$("#movimentacao-gerada").html("Carregou");;
				}
			});
		}	
	});
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
			else echo utf8_encode($parms);
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