<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_registrointeresse";
	$entidadeDescricao = "Registro de Interesse";

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
	$cliente = criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,0,4,0,getEntidadeId("crm_imobiliaria_cliente",$conn));
	$corretor = criarAtributo($conn,$entidadeID,"corretor","Corretor","int",0,0,4,0,getEntidadeId("crm_imobiliaria_corretor",$conn));
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');