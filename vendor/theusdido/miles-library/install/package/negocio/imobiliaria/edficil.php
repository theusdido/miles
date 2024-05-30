<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_edficil";
	$entidadeDescricao = "Edfícil";
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
	$dataregistro = criarAtributo($conn,$entidadeID,"dataregistro","Data registro","date",0,0,11);
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3);
	$enderecoedficil = criarAtributo($conn,$entidadeID,"enderecoedficil","Endereço do Edfícil","Int",0,0,4,0,getEntidadeId("imobiliaria_enderecoedficil",$conn));
	
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');