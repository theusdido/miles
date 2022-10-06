<?php

	if (!file_exists(PATH_CURRENT_FILE_TEMP)){
		tdFile::mkdir(PATH_CURRENT_FILE_TEMP);
		exit;
	}

	$atributo = tdClass::Criar("persistent",array(ATRIBUTO,retornar("atributo")));
	$entidade = tdClass::Criar("persistent",array(ENTIDADE,$atributo->contexto->entidade));

	$identicador	= $atributo->contexto->nome;
	$id 			= isset($_GET["id"])?$_GET["id"]:0;
	$id_input 		= "file_" 		. $identicador;
	$id_form		= "form_" 		. $identicador;
	$id_registro	= "registro_" 	. $identicador;
	$id_display		= "display_" 	. $identicador;

	$bootstrap = tdClass::Criar("link");
	$bootstrap->href = URL_LIB . 'bootstrap/3.3.1/css/bootstrap.css';
	$bootstrap->rel = 'stylesheet';
	$bootstrap->mostrar();
	
	$fontAwesome 				= tdClass::Criar("script");
	$fontAwesome->src 			= "https://kit.fontawesome.com/ea948eea7a.js";
	$fontAwesome->crossorigin 	= "anonymous";
	$fontAwesome->mostrar();
	
	$tema_default 			= tdClass::Criar("link");
	$tema_default->href 	= URL_SYSTEM_THEME.'geral.css';
	$tema_default->rel 		= 'stylesheet';
	$tema_default->mostrar();

	$style = tdClass::Criar("style");
	$style->type = "text/css";
	$style->add('
		body{
			overflow-y:hidden;
			overflow-x:hidden;
		}	
	');
	$style->mostrar();
	
	$jquery = tdClass::Criar("script");
	$jquery->src = URL_LIB . "jquery/jquery.js";
	$jquery->mostrar();

	if (isset($_GET["excluir"])){
		$fileDelete = PATH_CURRENT_FILE_TEMP . $_GET["excluir"];
		if (file_exists($fileDelete)){
			unlink($fileDelete);
		}
	}

	// Quando o arquivo for Uploaded
	if (isset($_FILES[$id_input])){
		$idregistro 		= isset($_POST["idregistro"])?$_POST["idregistro"]:0;
		$valor  			= $_FILES[$id_input]["name"];
		$extensao 			= getExtensao($_FILES[$id_input]["name"]);
		$nomeentidade		= tdClass::Criar("persistent",array(ENTIDADE,$atributo->contexto->entidade))->contexto->nome;
		$retorno		 	= tdFile::uploadTDForm($_FILES,tdc::a($atributo->contexto->id));

		$script = tdClass::Criar("script");
		$script->add('
			parent.$("#'.$atributo->contexto->nome.'[data-entidade='.$nomeentidade.']").val(\''.json_encode($retorno).'\');
		');
		$script->mostrar();
	}else{
		if (isset($_GET["valor"])){
			$valor = $_GET["valor"];
		}else{
			$valor = "";
		}
	}

	if ($valor!=""){
		$isnew = false;
		$valorjson = json_decode($valor);
		if (is_object($valorjson)){
			$nomeexibicaoarquivo = $valorjson->filename;
			// Verifica se é um registro novo e está em modo de edição no formulário
			if ($valorjson->tipo == "" && ($valorjson->filename == null || $valorjson->filename == "null")){
				$isnew = true;
			}
		}else{
			$nomeexibicaoarquivo = $valor;
		}
		
		if (!$isnew){
			$nome_arquivo_completo = $atributo->contexto->nome."-".$entidade->contexto->id."-".(isset($_GET["id"])?$_GET["id"]:"").".".getExtensao($nomeexibicaoarquivo);
			
			$uploaded 						= tdClass::Criar("div");
			$uploaded->class 				= "input-group";
			$input_up 						= tdClass::Criar("input");
			$input_up->type 				= "text";
			$input_up->class 				= "form-control input-td-uploadfile";
			$input_up->readonly 			= "true";
			$input_up->value 				= $nomeexibicaoarquivo;
			$span_up 						= tdClass::Criar("span");
			$span_up->class 				= "input-group-btn";
			
			$button_up_deletar 				= tdClass::Criar("button");
			$button_up_deletar->class 		= "btn btn-default deletar-td-upload";
			$icon_up 						= tdClass::Criar("span");
			$icon_up->class 				= "fas fa-trash-alt file-upload-icon";
			$icon_up->aria_hidden 			= "true";
			$button_up_deletar->add($icon_up,'&nbsp;');
			$button_up_deletar->onclick 	= "parent.excluirArquivoUpload(".str_replace('"',"'",getHTMLTipoFormato(19,$nomeexibicaoarquivo,$atributo->contexto->entidade,$atributo->contexto->id)).",{$entidade->contexto->id},{$atributo->contexto->id});";
			
			$button_up_visualizar 			= tdClass::Criar("button");
			$button_up_visualizar->class 	= "btn btn-default visualizar-td-upload";
			$srcRetorno 					= isset($retorno["src"])?$retorno["src"]:URL_CURRENT_FILE.$nome_arquivo_completo;
			$srcFile 						= file_exists(PATH_CURRENT_FILE.$nome_arquivo_completo)?URL_CURRENT_FILE.$nome_arquivo_completo:$srcRetorno;
			$icon_up 						= tdClass::Criar("span");
			$icon_up->class 				= "fas fa-external-link-alt file-upload-icon";
			$icon_up->aria_hidden 			= "true";
			$button_up_visualizar->add($icon_up,'&nbsp;');
			$button_up_visualizar->onclick 	= "window.open('".$srcFile."','_blank');";

			$span_up->add($button_up_visualizar,$button_up_deletar);
			$uploaded->add($input_up,$span_up);
			$uploaded->mostrar();
			exit;
		}
	}

	$form 						= tdClass::Criar("form");
	$form->action 				= URL_MILES . tdClass::Criar("persistent",array(CONFIG,1))->contexto->urlupload;
	$form->method 				= "POST";
	$form->enctype 				= "multipart/form-data";
	$form->id 					= $id_form;
	$form->class 				= "tdform-upload-file";
	$form->onsubmit 			= "return false";

	$atributo 					= tdClass::Criar("input");
	$atributo->type 			= "hidden";
	$atributo->id 				= "atributo";
	$atributo->name 			= "atributo";
	$atributo->value 			= retornar("atributo");

	$currentProject 			= tdClass::Criar("input");
	$currentProject->type 		= "hidden";
	$currentProject->id 		= "currentproject";
	$currentProject->name 		= "currentproject";
	$currentProject->value 		= CURRENT_PROJECT_ID;

	$registro 					= tdClass::Criar("input");
	$registro->type 			= "hidden";
	$registro->id 				= $id_registro;
	$registro->name 			= $id_registro;
	
	$group 						= tdClass::Criar("div");
	$group->class 				= "input-group";
	$input_up 					= tdClass::Criar("input");
	$input_up->type 			= "text";
	$input_up->class 			= "form-control input-td-uploadfile";
	$input_up->readonly 		= "true";
	$span_up 					= tdClass::Criar("span");
	$span_up->class 			= "input-group-btn";
	$button_up 					= tdClass::Criar("button");
	$button_up->class 			= "btn btn-default button-td-uploadfile";
	$icon_up 					= tdClass::Criar("span");
	$icon_up->class 			= "fas fa-upload";
	$icon_up->aria_hidden 		= "true";
	$button_up->add($icon_up,'&nbsp;'.'Carregar');
	$button_up->onclick = "$('#".$id_input."').click();";
	$span_up->add($button_up);
	$group->add($input_up,$span_up);

	$file_oculto 				= tdClass::Criar("input");
	$file_oculto->type="file";
	$file_oculto->style 		= "display:none";
	$file_oculto->id 			= $id_input;
	$file_oculto->name 			= $id_input;

	$input_idregistro = tdClass::Criar("input");
	$input_idregistro->id 		= "idregistro";
	$input_idregistro->name 	= "idregistro";
	$input_idregistro->value 	= $id;

	$form->add($group,$atributo,$registro,$file_oculto,$input_idregistro,$currentProject);
	$form->mostrar();
	$script = tdClass::Criar("script");
	$script->add('		
		$("#'.$id_input.'").change(function(){
			$("#'.$id_registro.'").val(parent.$("#id").val());
			$("#'.$id_form.'").attr("onsubmit","return true");
			$("#'.$id_form.'").submit();
		});
	');
	$script->mostrar();