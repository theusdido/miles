<?php

	$conn = Transacao::Get();

	// Verifica se a variável "entidade" foi passada por parâmetro
	if (isset($_GET["entidade"])){
		$entidade = tdClass::Criar("persistent",array(ENTIDADE,$_GET["entidade"]));
		if (!$entidade->contexto->hasData()){
			$alert 			= tdClass::Criar("Alert",array("Não foi possível abrir a entidade. "));
			$alert->type 	= "alert-danger";
			$bloco->add($alert);
			exit;
		}
	}else{
		tdClass::Write('Parâmetro <b>entidade</b> não encontrado.');
		exit;
	}

	$isprincipal = tdc::r("principal") == "" ? false : json_decode($_GET["principal"]);
	$cf = getCurrentConfigFile();
	if ($isprincipal){
		$pathfilepage 	= PATH_FILES_CADASTRO . $entidade->contexto->id . "/";
		$urlfilepage	= URL_FILES_CADASTRO . $entidade->contexto->id . "/";
	}

	// Bloco (BOOTSTRAP)
	$bloco 			= tdClass::Criar("bloco");
	$bloco->class 	= "crud-contexto";

	// Relacionamentos
	if (tdc::r("relacionamento") != ''){
		$relacionamento 	= tdClass::Criar("persistent",array(RELACIONAMENTO,tdClass::Read("relacionamento")))->contexto;
		$relacionamentoPai 	= $relacionamento->pai;
		$relacionamentoTipo = $relacionamento->tipo;
	}else{
		$relacionamento = $relacionamentoPai = $relacionamentoTipo = "";
	}

	// Utilizado para determinar a aréa de atuação de cada "tela"
	if ($isprincipal){
		$contexto = $entidade->contexto->nome;
	}else{
		$contexto = getHierarquia($entidade->contexto->id,$relacionamentoPai) . $entidade->contexto->nome;
	}

	// Barra de Título
	if ($entidade->contexto->exibirlegenda == 1){
		$titulo 				= tdClass::Criar("titulo");
		$titulo->class 			= $isprincipal ? "" : "td-titulopagina-relacionamento";
		
		$titulo->add(
			tdc::html("span",utf8charset($entidade->contexto->descricao,7),null,"descricao-entidade"),
			tdc::html("small","[ {$entidade->contexto->nome} ]",null,"nome-entidade"),
			tdc::o("badge",array($entidade->contexto->id))
		);
		$bloco->add($titulo);
	}

	// Loader ( Página )
	$divLoader = tdClass::Criar("div");
	$divLoader->class="loader-pagina";
	$bloco->add($divLoader);

	// Span LISTAR
	$contextoListar 				= "#crud-contexto-listar-" . $contexto;
	$crudListar 					= tdClass::Criar("span");
	$crudListar->id 				= str_replace("#","",$contextoListar);
	$crudListar->class 				= "crud-contexto-listar " . ($isprincipal?"fp":"");
	$crudListar->data_entidade 		= $entidade->contexto->nome;
	$crudListar->data_entidadeid 	= $entidade->contexto->id;
	$crudListar->data_entidadepai		= tdc::r("entidadepai");

	// Span ADD
	$contextoAdd 					= "#crud-contexto-add-" . $contexto;
	$crudAdd 						= tdClass::Criar("span");
	$crudAdd->id 					= str_replace("#","",$contextoAdd);
	$crudAdd->class 				= "crud-contexto-add " . ($isprincipal?"fp":"");
	$crudAdd->data_entidade 		= $entidade->contexto->nome ;
	$crudAdd->data_entidadeid 		= $entidade->contexto->id;
	$crudAdd->data_entidadepai		= tdc::r("entidadepai");

	if (tdClass::Read("relacionamento") == "" || $relacionamentoTipo == "2" || $relacionamentoTipo == "6" || $relacionamentoTipo == "5" || $relacionamentoTipo == "8" || $relacionamentoTipo == "10"){
		$crudAdd->style = "display:none;";
		$crudListar->style = "display:inline;";
	}else{
		$crudAdd->style = "display:inline;";
		$crudListar->style = "display:none;";
	}

	if ($entidade->contexto->registrounico == 1){
		$crudAdd->style = "display:inline;";
		$crudListar->style = "display:none;";
	}

	// Configuração Visual dos Botões
	$btnNovoType 	= "btn-primary";
	$btnNovoLabel 	= " Novo";
	$btnSalvarType 	= "btn-success";
	$btnSalvarLabel	= " Salvar";

	// Variavel com o nome da Grade de Dados
	$gdJS = $entidade->contexto->nome . "GD";
	if ($isprincipal){

		// Adiciona CSS personalizado
		$cssCustom = tdClass::Criar("link");
		$cssCustom->type 	= "text/css";
		$cssCustom->href 	= $urlfilepage . $entidade->contexto->nome . ".css";
		$cssCustom->rel 	= "stylesheet";
		$bloco->add($cssCustom);

		if ($entidade->contexto->carregarlibjavascript == 1){

			// Adiciona a classe Grade de Dados em JavaScript
			$jsGrade 		= tdClass::Criar("script");
			$jsGrade->src 	= URL_CLASS_TDC . "gradededados.class.js";
			$bloco->add($jsGrade);

			// Adiciona a página padrão de validação dos campos do formulário
			$jsValidar 		= tdClass::Criar("script");
			$jsValidar->src = URL_SYSTEM . "validar.js";
			$bloco->add($jsValidar);

			// Arquivo JS Incorporado
			$js 		= tdClass::Criar("script");
			$js->src 	= $urlfilepage . $entidade->contexto->nome . ".js";
			$bloco->add($js);

			// Classe do formulário
			$jsFormularioClass 			= tdClass::Criar("script");
			$jsFormularioClass->src 	= URL_CLASS_TDC . "formulario.class.js";
			$bloco->add($jsFormularioClass);

		}

		// HTML Personalizado
		$htmlPersonalizado 		= tdClass::Criar("div");
		$htmlPersonalizado->id 	= "div-htmlpersonalizado";
		$bloco->add($htmlPersonalizado);

		$EntidadePrincipalID = Campos::Oculto("entidadeprincipalid","entidadeprincipalid",$entidade->contexto->id);
		$bloco->add($EntidadePrincipalID);
	}

	// CK Editor Instancias
	$_SESSION["ckEditorInstancias"] = false;

	// Relacionamentos
	include 'crud/relacionamento.php';

	// Cria a página listar ( Paginação )
	include 'crud/listar.php';

	// Inclui página de Inserção
	include 'crud/add.php';

	$bloco->add($crudListar,$crudAdd);

	switch(tdc::r("acao")){
		case "compilar":
			echo 1;

			// Cria o arquivo HTML
			$fp = fopen($pathfilepage . $entidade->contexto->nome . ".html" ,'w');
			fwrite($fp,$bloco->toString());
			fclose($fp);

		break;
		default:
			// Exibe o resultado HTML do bloco
			$bloco->mostrar();
	}

	Transacao::Commit();