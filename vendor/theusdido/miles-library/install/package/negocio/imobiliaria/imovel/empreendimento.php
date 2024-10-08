<?php
	// Setando variáveis
	$entidadeNome 			= "imobiliaria_empreendimento";
	$entidadeDescricao 		= "Empreendimento";

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
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,1,3,1);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);

	// Criando Acesso
	$menu = addMenu($conn,'Imóvel','#','',0,0,'imovel');

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