<?php
	// Setando variáveis
	$entidadeNome = "erp_material_posicaogeralestoque";
	$entidadeDescricao = "Posição Geral Estoque";

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
	$produto 		= criarAtributo($conn,$entidadeID,"produto","Produto","int",0,0,22,1,installDependencia("erp_produto",'package/negocio/erp/produto/produto'),0,"");
	$saldo 			= criarAtributo($conn,$entidadeID,"saldo","Saldo","int",0,1,25,1);
	$datahora 		= criarAtributo($conn,$entidadeID,"datahora","Data/Hora","datetime",0,1,23,1);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Material','#','',0,0,'material');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'material-' . $entidadeNome);