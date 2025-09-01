<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_listainteresseimovel";
	$entidadeDescricao = "Interesse de Imóveis";

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
	$imoveis = criarAtributo($conn,$entidadeID,"imovel","Imóvel","int",0,0,4,0,installDependencia("imobiliaria_imovel",'package/negocio/imobiliaria/imovel'));
	$interessado = criarAtributo($conn,$entidadeID,"interessado","Interessado ?","tinyint",0,0,7);
	$motivonaointerese = criarAtributo($conn,$entidadeID,"motivonaointerese","Motivo de Não Interesse","int",0,0,4,0,installDependencia("imobiliaria_motivonaointeresse",'package/negocio/imobiliaria/motivointeresse'));
	$observacao = criarAtributo($conn,$entidadeID,"observacao","Observação","varchar",1000,0,3);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'CRM','#','',0,0,'crm');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'crm-' . $entidadeNome ,$entidadeID,'cadastro');