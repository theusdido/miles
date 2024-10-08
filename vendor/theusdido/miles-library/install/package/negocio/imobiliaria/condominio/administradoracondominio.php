<?php
	// Setando variáveis
	$entidadeNome 		= "imobiliaria_administradoracondominio";
	$entidadeDescricao 	= "Administradora de Condominio";

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
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3,1);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$nome,true);

	// Criando Acesso
	$menu = addMenu($conn,'Condomínio','#','',0,0,'condominio');

	// Adicionando Menu
	addMenu(
		$conn,
		$entidadeDescricao,
		"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",
		'',
		$menu,
		0,
		'imovel-' . $entidadeNome,
		$entidadeID,
		'cadastro'
	);		