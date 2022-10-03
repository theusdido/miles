<?php
	// Exibição do ID e do Campo Descrição da Entidade
	if ($isprincipal){
		$idDescricao 			= tdClass::Criar("span");
		$idDescricao->class 	= "idExibirEdicao";
		$campoDescricao 		= tdClass::Criar("span");
		$campoDescricao->class 	= "campodescricaoExibirEdicao";
		$divDescricao 			= tdClass::Criar("div");
		$divDescricao->class 	= "descricaoExibirEdicao alert alert-info";
		$divDescricao->add($idDescricao);
		$divDescricao->add($campoDescricao);
		$crudAdd->add($divDescricao);
	}

	// Local da mensagem de Retorno
	$msgRetorno = 'msg-retorno-form-' . $entidade->contexto->nome;

	// Mensagem de Retorno
	$retorno 		= tdClass::Criar("retorno");
	$retorno->class = $msgRetorno;
		
	// Botão Novo
	$btn_novo 			= tdClass::Criar("button");
	$btn_novo->class 	= "btn {$btnNovoType} b-novo";
	$span_novo 			= tdClass::Criar("span");
	$span_novo->class 	= "fas fa-plus";	
	$btn_novo->add($span_novo,$btnNovoLabel);

	// Botão Salvar
	$btn_salvar 					= tdClass::Criar("button");
	$btn_salvar->class 				= "btn {$btnSalvarType} b-salvar";
	$btn_salvar->id 				= 'b-salvar-'.$entidade->contexto->nome;
	$btn_salvar->data_loading_text 	= "Aguarde...";
	$span_salvar 					= tdClass::Criar("span");
	$span_salvar->class 			= "fas fa-check";
	$btn_salvar->add($span_salvar,$btnSalvarLabel);

	// Botão Voltar
	$btn_voltar 					= tdClass::Criar("button");
	$btn_voltar->class 				= "btn btn-link b-voltar";
	$btn_voltar->id 				= $entidade->contexto->nome;
	$span_voltar 					= tdClass::Criar("span");
	$span_voltar->class 			= "fas fa-arrow-left";

	$btn_voltar->add($span_voltar,'Voltar');

	// Grupo de Botões
	$grupo_botoes 			= tdClass::Criar("div");
	$grupo_botoes->class 	= "form-grupo-botao";

	// Loader Salvar
	$loader_salvar 			= tdClass::Criar("div");
	$loader_salvar->class 	= "loader-salvar";

	$grupo_botoes->add($loader_salvar,$btn_salvar,$btn_voltar,$retorno);

	if ($relacionamentoTipo == "1" || $relacionamentoTipo == "3" || $relacionamentoTipo == "7" || $relacionamentoTipo == "9"){
		$grupo_botoes->style = "display:none;";
	}
	$crudAdd->add($grupo_botoes);

	include 'formulario.php';