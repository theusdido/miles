<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_atendente";
	$entidadeDescricao = "Atendente";
	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	/*
	Exclusivo para Locativa
	$nomecompleto = criarAtributo($conn,$entidadeID,"nomecompleto","Nome Completo","varchar",200,0,3);
	$usuariocache = criarAtributo($conn,$entidadeID,"usuariocache","Usuário Caché","varchar",200,0,3);
	$senhausuariocache = criarAtributo($conn,$entidadeID,"senhausuariocache","Senha do Usuário Caché","varchar",60,0,6);
	$cpf = criarAtributo($conn,$entidadeID,"cpf","CPF","varchar",14,0,10);
	$inativo = criarAtributo($conn,$entidadeID,"inativo","Inativo ?","tinyint",0,0,7);
	*/
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID,'cadastro');