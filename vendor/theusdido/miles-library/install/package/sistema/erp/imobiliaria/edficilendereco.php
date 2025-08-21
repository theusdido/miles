<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_edficilendereco";
	$entidadeDescricao = "edficilendereco";
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
	$endereco = criarAtributo($conn,$entidadeID,"endereco","Endereço","Int",0,0,4,0,getEntidadeId("erp_imobiliaria_endereco",$conn));
	$edficil = criarAtributo($conn,$entidadeID,"edficil","Edfícil","Int",0,0,4,0,getEntidadeId("erp_imobiliaria_edficil",$conn));
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');