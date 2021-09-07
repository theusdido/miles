<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_estado";
	$entidadeDescricao = "Cadastro de Estado";

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
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3);
	$pais = criarAtributo($conn,$entidadeID,"pais","País","Int",0,0,4,0,getEntidadeId("crm_imobiliaria_pais",$conn));
	$sila = criarAtributo($conn,$entidadeID,"sigla","Sigla","char",2,0,1);
	$denominacao = criarAtributo($conn,$entidadeID,"denominacao","Denominação","varchar",50,0,2);
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','','','','Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome);