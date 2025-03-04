<?php

	// Setando variáveis
	$entidadeNome 		= "ecommerce_pagseguro";
	$entidadeDescricao 	= "Pag Seguro";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 1
	);

	// Criando Atributos
	$email 			= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",200,0,12,1,0,0,'',1,0);
	$token 			= criarAtributo($conn,$entidadeID,"token","Token","varchar",200,0,3,1,0,0,'',1,0);
	$producao 		= criarAtributo($conn,$entidadeID,"producao","Produção","tinyint",0,1,7);
	$notificacao 	= criarAtributo($conn,$entidadeID,"notificacaourl","Notificação (URL)","varchar",1000,1,3,0,0,0,'',1,0);
	

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');