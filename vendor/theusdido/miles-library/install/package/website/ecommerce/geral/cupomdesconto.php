<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_cupomdesconto";
	$entidadeDescricao 	= "Cupom de Desconto";

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
		$registrounico = 0,
		$carregarlibjavascript = 1,
		$criarinativo = false
	);

	// Criando Atributos
	$codigo 			= criarAtributo($conn,$entidadeID,"codigo","Código","varchar","200",0,3);
    $datahoravalidade	= criarAtributo($conn,$entidadeID,"datahoravalidade","Data/Hora de Validade","datetime",0,0,23,1);
	$percentual 		= criarAtributo($conn,$entidadeID,"percentual","Percentual %","float",0,1,13,1,0,0,"");

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'ecommerce-' . $entidadeNome);