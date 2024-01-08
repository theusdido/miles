<?php

	if (tdClass::Read("op") == "listarfarein"){
		
		$conn = Transacao::Get();
		$processo = tdClass::Criar("persistent",array("td_processo",tdClass::Read("processo")))->contexto;
		if ($processo->tipoprocesso == "16"){
			$entidade = "td_recuperanda";
		}else if ($processo->tipoprocesso == "19"){
			$entidade = "td_falencia";
		}
		$sql = "SELECT id,razaosocial FROM {$entidade} WHERE processo = {$processo->id}";
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){
			echo '<option value="'.$linha["id"].'">'.strtoupper($linha["razaosocial"]).'</option>';
		}
		exit;
	}

	if (tdClass::Read("op") == "listarprotocolo"){
		$processo 	= tdClass::Read("processo");
		$farein 	= tdClass::Read("farein");
		$tipo		= tdc::r('tipo');

		$entidade_protocolo = $tipo == 8 ? 'td_arquivosassembleia' : 'td_habilitacaodivergencia';			
		$conn 				= Transacao::Get();
		$sql 				= "
			SELECT 
				a.numero,
				a.credor credor, 
				b.nome nome 
			FROM $entidade_protocolo a,td_relacaocredores b 
			WHERE a.credor = b.id 
			AND a.processo = $processo
			AND b.farein = $farein
			AND b.origemcredor = $tipo
			ORDER BY a.id DESC;
		";
		$query 		= $conn->query($sql);
		while ($linha = $query->fetch()){
			echo '<option value="'.$linha["credor"].'">[ <b>'.$linha["numero"].'</b> ] - '.strtoupper($linha["nome"]).'</option>';
		}
		
		exit;
	}

	if (tdClass::Read("op") == "addfile"){

		$credor 						= $_GET["credor"];
		$_uploaded_file 				= isset($_FILES["fileToUpload"]) ? $_FILES["fileToUpload"] : $_FILES["file"];
		$arquivo_file 					= tdc::p('td_arquivos_credor');
		$proxID 						= $arquivo_file->proximoID();
		$filename 						= $_uploaded_file["name"];
		$arquivo_file->descricao 		= $filename;
		$arquivo_file->nome 			= $proxID . ".pdf";
		$arquivo_file->relacaocredores 	= $credor;
		$arquivo_file->origem 			= 9;
		$arquivo_file->armazenar();

		$_uploaded_path					= PATH_CURRENT_FILE . 'credor/' .  $proxID . ".pdf";
		move_uploaded_file($_uploaded_file["tmp_name"],$_uploaded_path);
		chmod($_uploaded_path,0777);

		Transacao::Commit();
		exit;
	}

	// Bloco
	$bloco 			= tdClass::Criar("bloco");
	$bloco->class	= "col-md-12";	
	
	$titulo 		= tdClass::Criar("p");
	$titulo->class 	= "titulo-pagina";
	$titulo->add(utf8_decode("Envio Arquivo Avulso"));
	
	$processos 		= array();
	$sql 			= tdClass::Criar("sqlcriterio");
	$sql->setPropriedade("order","id DESC");
	$dataset 		= tdClass::Criar("repositorio",array("td_processo"))->carregar($sql);
	foreach($dataset as $p){
		$option = tdClass::Criar("option");
		$option->value = $p->id;
		$option->add("[ {$p->id} ] - {$p->numeroprocesso}");
		array_push($processos,$option);
	}
	
	$tipos_protocolos	= array();

	$option 			= tdClass::Criar("option");
	$option->value 		= 3;
	$option->add("HABILITAÇÃO");
	array_push($tipos_protocolos,$option);

	$option 			= tdClass::Criar("option");
	$option->value 		= 2;
	$option->add("DIVERGÊNCIA");	
	array_push($tipos_protocolos,$option);
	
	$option 			= tdClass::Criar("option");
	$option->value 		= 8;
	$option->add("ASSEMBLEIA");	
	array_push($tipos_protocolos,$option);

	$lprocesso 			= Campos::Lista("processo","processo","Processo",$processos);
	$lfarein 			= Campos::Lista("farein","farein","Empresa");
	$tipo_protocolo		= Campos::Lista("tipo","tipo","Tipo de Protocolo",$tipos_protocolos);
	$lprotocolo 		= Campos::Lista("protocolo","protocolo","Protocolo");	

	$urlupload 			= getURLProject("index.php?controller=envioarquivoavulso&credor=");

	$formDropZone 			= tdClass::Criar("form");
	$formDropZone->action 	= $urlupload;
	$formDropZone->class 	= "dropzone";
	$formDropZone->id 		= "DropzoneForm";
	
	$divFallback 			= tdClass::Criar("div");
	$divFallback->class 	= "fallback";
	
	$inputFile 				= tdClass::Criar("input");
	$inputFile->name 		= "fileToUpload";
	$inputFile->type 		= "file";
	$inputFile->multiple 	= true;
	
	$inputCredor 			= tdClass::Criar("input");
	$inputCredor->type 		= "hidden";
	$inputCredor->value 	= "dido";

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
				url:session.urlmiles,
				data:{
					controller:"envioarquivoavulso",
					op:"listarfarein",
					processo:processo
				},
				complete:function(ret){
					$("#farein").html(ret.responseText);
					carregarProtocolo();
				}
			});
		}
		function carregarProtocolo(){
			$.ajax({
				url:session.urlmiles,
				data:{
					controller:"envioarquivoavulso",
					op:"listarprotocolo",
					farein:$("#farein").val(),
					tipo:$("#tipo").val(),
					processo:$("#processo").val()
				},
				complete:function(ret){
					var retorno = ret.responseText;
					if (retorno != ""){
						habilitarEnvio();
						$("#protocolo").html(ret.responseText);
						setarURLFormEnvio($("#protocolo").val());
					}else{
						habilitarEnvio(false);	
					}
				}
			});
		}

		$("#processo").change(function(){
			carregarFarein($(this).val());
		});

		$("#farein,#tipo").change(function(){
			carregarProtocolo();
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
				$("#DropzoneForm").css("visibility","visible");
			}else{
				$("#protocolo").prop("disabled",true);
				$("#protocolo").html("");
				$("#DropzoneForm").css("visibility","hidden");
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
	$bloco->add($titulo,$lprocesso,$lfarein,$tipo_protocolo,$lprotocolo,$formDropZone,$script,$style);
	$bloco->mostrar();