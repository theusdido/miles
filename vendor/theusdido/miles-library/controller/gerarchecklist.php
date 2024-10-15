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
			tdc::html("span",$entidade->contexto->descricao,null,"descricao-entidade"),
			tdc::html("small","[ {$entidade->contexto->nome} ]",null,"nome-entidade"),
			tdc::o("badge",array($entidade->contexto->id))
		);
		$bloco->add($titulo);
	}

	// Loader ( Página )
	$divLoader          = tdClass::Criar("div");
	$divLoader->class   = "loader-pagina";
	$bloco->add($divLoader);

	// Span LISTAR
	$contextoChecklist 				    = "#crud-contexto-checklist-" . $contexto;
	$crudChecklist 					    = tdClass::Criar("span");
	$crudChecklist->id 				    = str_replace("#","",$contextoChecklist);
	$crudChecklist->class 				= "crud-contexto-checklist " . ($isprincipal?"fp":"");
	$crudChecklist->data_entidade 		= $entidade->contexto->nome;
	$crudChecklist->data_entidadeid 	= $entidade->contexto->id;
	$crudChecklist->data_entidadepai	= tdc::r("entidadepai");    

	// Variavel com o nome da Grade de Dados
	$gdJS = $entidade->contexto->nome . "GD";
	if ($isprincipal){

		// Adiciona CSS personalizado
		$cssCustom = tdClass::Criar("link");
		$cssCustom->type 	= "text/css";
		$cssCustom->href 	= $urlfilepage . $entidade->contexto->nome . ".css";
		$cssCustom->rel 	= "stylesheet";
		$bloco->add($cssCustom);

		// Arquivo JS Incorporado
		$js 		= tdClass::Criar("script");
		$js->src 	= $urlfilepage . $entidade->contexto->nome . ".js";
		$bloco->add($js);

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


    $div_group_botao        = tdc::html('div');
    $div_group_botao->class = 'form-grupo-botao';

    // Botão Novo
    $btn_add 			= tdClass::Criar("button");
    $btn_add->class 	= "btn btn-default b-novo";
    $span_novo 			= tdClass::Criar("span");
    $span_novo->class 	= "fas fa-plus";
    $btn_add->add($span_novo,"Adicinar"); 
    $div_group_botao->add($btn_add);

    $list_checklist         = tdc::html('ul');
    $list_checklist->id     = 'checklist-' . $entidade->contexto->nome;
    $list_checklist->class  = 'list-group td-checklist td-checklist-contexto';

    $li_nenhum_registro         = tdc::html('li');
    $li_nenhum_registro->class  =  'list-group-item list-group-item-warning text-center td-nenhumregistro-list-item';
    $li_nenhum_registro->add('Nenhum Registro');
    $list_checklist->add($li_nenhum_registro);

    // Modal
    $modal_checklist          = tdc::o("modal");
    $modal_checklist->nome    = "modal-checklist-" . $entidade->contexto->nome;
    $modal_checklist->tamanho = "modal-lg";
    $modal_checklist->addHeader(tdc::utf8($entidade->contexto->descricao),null);
    $modal_checklist->addBody('');

    $crudChecklist->add($div_group_botao,$list_checklist,$modal_checklist);
    $bloco->add($crudChecklist);

	switch(tdc::r("acao")){
		case "compilar":
			echo 1;
			getUrl(URL_MILES . 'index.php?controller=mdm/componente&entidade=' . $entidade->contexto->id);
			tdFile::add($pathfilepage . $entidade->contexto->nome . ".html", $bloco->toString());
		break;
		default:
			// Exibe o resultado HTML do bloco
			$bloco->mostrar();
	}

	Transacao::Commit();