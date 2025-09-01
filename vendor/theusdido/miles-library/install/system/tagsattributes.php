<?php
	// Setando variáveis
	$entidadeNome 		= "tagsattributes";
	$entidadeDescricao 	= "Tags Atributos";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$tags 			= criarAtributo($conn,$entidadeID,"tags","Tags","int",0,0,4,0,getEntidadeId("tags",$conn),0,"");	
	$atributo 		= criarAtributo($conn,$entidadeID,"atributo","Atributo","varchar",50,0,3,1,0,0,"");
	$valor 			= criarAtributo($conn,$entidadeID,"valor","Valor","varchar",150,0,3,1,0,0,"");
	$tagpai 		= criarAtributo($conn,$entidadeID,"tagpai","Tag ( Pai )","int",0,0,3,1,0,0,"");