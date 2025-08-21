<?php
	// Setando variáveis
	$entidadeNome = "erp_geral_email";
	$entidadeDescricao = "E-Mail";

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
	$email 		= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar","200",1,12,0,0,0,"");
	$contato 	= criarAtributo($conn,$entidadeID,"contato","Contato","varchar","60",1,3,1,0,0,"");

	// Criando Acesso
	$menu_webiste 	= addMenu($conn,'Geral','#','',0,0,'geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID,'cadastro');
