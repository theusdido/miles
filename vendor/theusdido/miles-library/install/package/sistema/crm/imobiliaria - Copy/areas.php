<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_areas";
	$entidadeDescricao = "Áreas";

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
	$areautil = criarAtributo($conn,$entidadeID,"areautil","Área Útil","float",0,0,26);
	$areaconstruida = criarAtributo($conn,$entidadeID,"areaconstruida","Área Construida","float",0,0,26);
	$areatotal = criarAtributo($conn,$entidadeID,"areatotal","Área Total","float",0,0,26); 

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');