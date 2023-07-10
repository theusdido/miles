<?php

	if (tdClass::Read("op") == "listarfarein"){
		
		$conn = Transacao::Get();
		$processo = tdClass::Criar("persistent",array("td_processo",tdClass::Read("processo")))->contexto;
		if ($processo->tipoprocesso == "16"){
			$entidade = "td_recuperanda";
		}else if ($processo->tipoprocesso == "19"){
			$entidade = "td_falencia";
		}
		$sql = "SELECT id,razaosocial FROM {$entidade} WHERE td_processo = {$processo->id}";
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){
			echo '<option value="'.$linha["id"].'">'.strtoupper($linha["razaosocial"]).'</option>';
		}
		exit;
	}

	if (tdClass::Read("op") == "listarprotocolo"){
		$processo = tdClass::Read("processo");
		$farein = tdClass::Read("farein");
		$conn = Transacao::Get();
		$sql = 'select a.numero,a.td_credor credor,b.nome nome from td_habilitacaodivergencia a,td_relacaocredores b where a.td_credor = b.id and a.td_processo = '.$processo.' AND b.farein = '.$farein.' order by a.id DESC';
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){
			echo '<option value="'.$linha["credor"].'">[ <b>'.$linha["numero"].'</b> ] - '.strtoupper($linha["nome"]).'</option>';
		}
		exit;
	}

	if (tdClass::Read("op") == "addfile"){

		$credor = $_GET["credor"];
		
		$arquivofile = tdClass::Criar("persistent",array("td_arquivos_credor"))->contexto;
		$proxID = $arquivofile->proximoID();
		$arquivofile->id = $proxID;
		$filename = $_FILES["file"]["name"];
		$arquivofile->descricao = $filename;
		$arquivofile->nome = $proxID . ".pdf";
		$arquivofile->td_relacaocredores = $credor;
		$arquivofile->origem = 9;
		$arquivofile->armazenar();

		// Dados do servidor
		Transacao::abrir("miles");
		$ftpOBJ = tdc::da("td_connectionftp",tdc::f("td_projeto","=",Session::Get()->projeto))[0];
		
		$servidor 	= $ftpOBJ["host"]; // Endereço
		$usuario 	= $ftpOBJ["user"]; // Usuário
		$senha 		= $ftpOBJ["password"]; // Senha

		// Abre a conexão com o servidor FTP
		$ftp = ftp_connect($servidor); // Retorno: true ou false
		if ($ftp){
			// Faz o login no servidor FTP
			$login = ftp_login($ftp, $usuario, $senha); // Retorno: true ou false
			if ($login){
				ftp_pasv($ftp, true);
				// Envia o arquivo pelo FTP em modo ASCII
				$envio = ftp_put($ftp,"/public_html/site/enviodocumentos/arquivos_temp/" .  $proxID . ".pdf",$_FILES["file"]["tmp_name"], FTP_BINARY); // Retorno: true / false
				//$envio = ftp_put($ftp,$_FILES["file"]["tmp_name"], "/" .$proxID . ".pdf", FTP_ASCII); // Retorno: true / false
			}
		}
		ftp_close($ftp);
		#move_uploaded_file($_FILES["file"]["tmp_name"],"../site/enviodocumentos/arquivos_temp/" .  $proxID . ".pdf");
		echo $proxID . "^" . utf8_encode($filename);
		exit;
	}

	// Bloco
	$bloco = tdClass::Criar("bloco");
	$bloco->class="col-md-12";	
	
	$titulo = tdClass::Criar("p");
	$titulo->class = "titulo-pagina";
	$titulo->add(utf8_decode("Envio Arquivo Avulso"));
	
	$processos = array();
	$sql = tdClass::Criar("sqlcriterio");
	$sql->setPropriedade("order","id DESC");
	$dataset = tdClass::Criar("repositorio",array("td_processo"))->carregar($sql);
	foreach($dataset as $p){
		$option = tdClass::Criar("option");
		$option->value = $p->id;
		$option->add("[ {$p->id} ] - {$p->numeroprocesso}");
		array_push($processos,$option);
	}

	$lprocesso = Campos::Lista("processo","processo","Processo",$processos);
	$lfarein = Campos::Lista("farein","farein","Recuperanda / Falida / Insolvente");
	$lprotocolo = Campos::Lista("protocolo","protocolo","Protocolo");	

	$urlupload = getURLProject("index.php?controller=envioarquivoavulso&credor=");

	$formDropZone = tdClass::Criar("form");
	$formDropZone->action = $urlupload;
	$formDropZone->class = "dropzone";
	$formDropZone->id = "DropzoneForm";
	
	$divFallback = tdClass::Criar("div");
	$divFallback->class = "fallback";
	
	$inputFile = tdClass::Criar("input");
	$inputFile->name = "fileToUpload";
	$inputFile->type = "file";
	$inputFile->multiple = true;
	
	$inputCredor = tdClass::Criar("input");
	$inputCredor->type = "hidden";
	$inputCredor->value = "dido";

	$divFallback->add($inputFile,$inputCredor);
	$formDropZone->add($divFallback);

	$script = tdClass::Criar("script");
	$script->add('
		var meuDropzone = new Dropzone("form", {url:getURLProject("index.php?controller=envioarquivoavulso&credor=" + $("#protocolo").val() + "&op=addfile")});
		Dropzone.options.DropzoneForm = {
			paramName: "fileToUpload", 
			dictDefaultMessage: "Arraste seus arquivos para cá!",
			maxFilesize: 300, 
			accept: function(file, done) {
				if (file.name == "olamundo.png") {
					done("Arquivo não aceito.");
				} else {
					done();
				}
			}
		}

		$(document).ready(function(){
			habilitarEnvio(false);
			carregarFarein($("#processo").val());
		});	
		function carregarFarein(processo){
			$.ajax({
				url:session.urlsystem,
				data:{
					controller:"envioarquivoavulso",
					op:"listarfarein",
					processo:processo,
					currentproject:'.$_SESSION["currentproject"].'
				},
				complete:function(ret){
					$("#farein").html(ret.responseText);
					carregarProtocolo($("#farein").val());
				}
			});
		}
		function carregarProtocolo(farein){
			$.ajax({
				url:session.urlsystem,
				data:{
					controller:"envioarquivoavulso",
					op:"listarprotocolo",
					farein:farein,
					processo:$("#processo").val(),
					currentproject:'.$_SESSION["currentproject"].'
				},
				complete:function(ret){
					var retorno = ret.responseText;
					if (retorno != ""){
						habilitarEnvio();
						$("#protocolo").html(ret.responseText);
						setarURLFormEnvio($("#protocolo").val());
					}
				}
			});
		}

		$("#processo").change(function(){
			carregarFarein($(this).val());
		});

		$("#farein").change(function(){
			carregarProtocolo($(this).val());
		});

		$("#protocolo").change(function(){
			setarURLFormEnvio($(this).val());
		});

		function setarURLFormEnvio(credor){
			meuDropzone.options.url = getURLProject("index.php?controller=envioarquivoavulso&credor=" + credor + "&op=addfile");
		}
		function habilitarEnvio(habilitar = true){
			if (habilitar){
				$("#protocolo").prop("disabled",false);
				$("#DropzoneForm").show();
			}else{
				$("#protocolo").prop("disabled",true);
				$("#DropzoneForm").hide();
			}
		}
	');

	$style = tdClass::Criar("style");
	$style->add('
		.dz-default.dz-message{
			font-size:36px;
			color:#999;
		}
		.dropzone{
			border:3px solid #EEE;
		}
		#DropzoneForm{
			margin-bottom:30px;
		}
	');
	$bloco->add($titulo,$lprocesso,$lfarein,$lprotocolo,$formDropZone,$script,$style);
	$bloco->mostrar();