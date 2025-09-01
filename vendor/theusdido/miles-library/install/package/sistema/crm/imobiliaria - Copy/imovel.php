<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_imovel";
	$entidadeDescricao = "Cadastro de Imóvel";

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
	$codigo = criarAtributo($conn,$entidadeID,"codigo","Código","int",0,0,3);
	$situacaoimovel = criarAtributo($conn,$entidadeID,"situacaoimovel","Situação do Imóvel","int",0,0,4,0,getEntidadeId("crm_imobiliaria_situacaoimovel",$conn));
	$condominio = criarAtributo($conn,$entidadeID,"condominio","Condomínio","tinyint",0,0,7);
	$edificil = criarAtributo($conn,$entidadeID,"edificil","Edifícil","int",0,0,4,0,getEntidadeId("crm_imobiliaria_edificil",$conn));
	$vaisite = criarAtributo($conn,$entidadeID,"vaisite","Vai para o Site ?","tinyint",0,0,7);
	$promocao = criarAtributo($conn,$entidadeID,"promocao","Promoção ?","tinyint",0,0,7);
	$destaque = criarAtributo($conn,$entidadeID,"destaque","Destaque ?","tinyint",0,0,7);
	$datacadastro = criarAtributo($conn,$entidadeID,"datacadastro","Data de Cadastro","date",0,0,11);
	$administradoracondominio = criarAtributo($conn,$entidadeID,"administradoracondominio","Administradora de Condomínio","int",0,0,4,0,getEntidadeId("crm_imobiliaria_administradoracondominio",$conn));
	$construtora = criarAtributo($conn,$entidadeID,"construtora","Construtora","int",0,0,4,0,getEntidadeId("crm_imobiliaria_construtora",$conn));
	$seguradora = criarAtributo($conn,$entidadeID,"seguradora","Seguradora","int",0,0,4,0,getEntidadeId("crm_imobiliaria_seguradora",$conn));
	

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');