<?php
	$entidadeNome 		= "menuprincipal";
	$entidadeDescricao 	= "Menu ( Principal )";
	
	// 1º PASSO
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
		$registrounico = 0
	);
	
	// 2º PASSO	
	$descricao 		= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar","200",0,3,1,0,0,"");
	$link 			= criarAtributo($conn,$entidadeID,"link","Link","text","",1,3,0,0,0,"");
	$target 		= criarAtributo($conn,$entidadeID,"target","Target","varchar","15",1,3,0,0,0,"");
	$pai 			= criarAtributo($conn,$entidadeID,"pai","Pai","int","",1,4,1,$entidadeID,0,"");
	$ordem 			= criarAtributo($conn,$entidadeID,"ordem","Ordem","int","",1,3,0,0,0,"");
	$fixo 			= criarAtributo($conn,$entidadeID,"fixo","Fixo","varchar","35",1,3,0,0,0,"");
	$ancora 		= criarAtributo($conn,$entidadeID,"ancora","Âncora","varchar","35",1,3,0,0,0,"");
	
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);