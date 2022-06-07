<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_negociacao";
	$entidadeDescricao = "Negociação";

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
	$corretor = criarAtributo($conn,$entidadeID,"corretor","Corretor","int",0,0,22,0,installDependencia("crm_imobiliaria_corretor",'package/negocio/imobiliaria/corretor'));
	$cliente = criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,0,22,0,installDependencia("imobiliaria_pessoa",'package/negocio/imobiliaria/pessoa'));
	$datainicial = criarAtributo($conn,$entidadeID,"datainicial","Data Inicial","date",0,0,11);
	$datafinal = criarAtributo($conn,$entidadeID,"datafinal","Data Final","date",0,0,11);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'CRM','#','',0,0,'crm');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'crm-' . $entidadeNome ,$entidadeID,'cadastro');
	
	// Entidades adicionais
	installDependencia("imobiliaria_negociacaoetapa",'package/negocio/imobiliaria/negociacaoetapa');
	installDependencia("imobiliaria_negociacaoimoveis",'package/negocio/imobiliaria/negociacaoimoveis');
	installDependencia("imobiliaria_negociacaointeracao",'package/negocio/imobiliaria/negociacaointeracao');
