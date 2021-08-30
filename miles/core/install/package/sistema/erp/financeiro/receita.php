<?php
	// Setando variáveis
	$entidadeNome = "erp_financeiro_receita";
	$entidadeDescricao = "Receita";
	
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
	$descricao 			= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1);
	$fonte       		= criarAtributo($conn,$entidadeID,"fonte","Fonte","smallint",0,1,4,0,installDependencia($conn,"erp_contabil_fonterenda"),0,"",0,0);
	$valor 				= criarAtributo($conn,$entidadeID,"valor","Valor","float",0,1,13,0,0,0,"",0,0);
	$formarecebimento 	= criarAtributo($conn,$entidadeID,"formarecebimento","Forma de Recebimento","int",0,0,4,0,installDependencia($conn,"erp_financeiro_formarecebimento"),0,"",0,0);
	$receitafixa 		= criarAtributo($conn,$entidadeID,"receitafixa","Receita Fixa","tinyint",0,1,7,0,0,0,"",0,0);
	$referencia	 		= criarAtributo($conn,$entidadeID,"referencia","Referência","char",7,1,29,1);
	

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Financeiro','#','','','','financeiro');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".PREFIXO.$entidadeNome.".html",'',$menu_webiste,1,'financeiro-' . $entidadeNome,$entidadeID, 'cadastro');