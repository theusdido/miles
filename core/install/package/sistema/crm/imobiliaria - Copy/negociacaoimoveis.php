<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_negociacaoimoveis";
	$entidadeDescricao = "Negociação de Imoveis";

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
	$imovel = criarAtributo($conn,$entidadeID,"imovel","Imóvel","int",0,0,22,0,getEntidadeId("crm_imobiliaria_imovel",$conn));
	$negociacao = criarAtributo($conn,$entidadeID,"negociacao","Negociação","int",0,0,22,0,getEntidadeId("crm_imobiliaria_negociacao",$conn));
	$vendido = criarAtributo($conn,$entidadeID,"vendido","Vendido ?","tinyint",0,0,7);
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');