<?php
	require 'conexao.php';
	require 'funcoes.php';
	require 'prefixo.php';
	
	if (isset($_POST["op"])){
		if ($_POST["op"] == "criarrelatorio"){			

			$path = "../../" . $_COOKIE["path_files_relatorio"];
			
			$fp = fopen($path . $_POST["filename"] ,'w');
			fwrite($fp,htmlespecialcaracteres_($_POST["html"],1));
			fclose($fp);			
			
			$jsFile = $path . "/" . $_POST["filenamejs"];
			if (!file_exists($jsFile)){
				$fp = fopen($jsFile ,'w');
				fwrite($fp,"// JS da Relatório");			
				fclose($fp);
			}

			// Move os arquivos para sua respectiva pasta
			$path_files_cadastro = "../../project/files/relatorio/"; #Fora do escopo do sistema para recepurar a constante PATH_FILES_RELATORIO
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

	$id 		= $_GET["id"];
	$entidade 	= $_GET["entidade"];
?>
<html>
	<head>
		<title>HTML Code</title>
		<?php include 'head.php' ?>
		<style type="text/css">
			#relatorio-gerada{
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
					<?php include 'menu_relatorio.php'; ?>	
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
								<input type="hidden" id="filenamecss" name="filenamecss" value="<?php echo $linha[0]["nome"]; ?>.css" />
								<input type="hidden" id="filenamehtm" name="filenamehtm" value="<?php echo $linha[0]["nome"]; ?>.htm" />								
								<div id="relatorio-gerada"></div>
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
				controller:"gerarrelatorio",
				id:"<?=$id?>",
				currentproject:"<?=$_SESSION["currentproject"]?>"
			},
			beforeSend:function(){
				$("#relatorio-gerada").html('<img src="../tema/padrao/loading2.gif" id="loading" style="float:left;margin-left:48%;" />');
			},
			complete:function(retorno){
				gerarRelatorio(retorno.responseText);
			}
		});
		function gerarRelatorio(html){
			$.ajax({
				url:"../../index.php",
				type:"POST",
				data:{
					controller:"mdm/componente",
					op:"criarrelatorio",
					html:html,
					filename:$("#filename").val(),
					filenamejs:$("#filenamejs").val(),
					filenamecss:$("#filenamecss").val(),
					filenamehtm:$("#filenamehtm").val(),
					entidade:"<?=$entidade?>",
					urlupload:$("#urlupload").val(),
					currentproject:<?=$_SESSION["currentproject"]?>,
					id:<?=$id?>
				},
				complete:function(){
					$("#relatorio-gerada").html("Carregou");
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
			else echo executefuncion("tdc::utf8",array($parms));
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