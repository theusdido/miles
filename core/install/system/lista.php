<?php
	// Setando variáveis
	$entidadeNome = "lista";
	$entidadeDescricao = "Lista";
	
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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$entidadepai 	= criarAtributo($conn,$entidadeID,"entidadepai","Pai","int",0,0,4,0,0,0,"");
	$entidadefilho 	= criarAtributo($conn,$entidadeID,"entidadefilho","Filho","int",0,0,4,0,0,0,"");
	$regpai 		= criarAtributo($conn,$entidadeID,"regpai","Pai","int",0,0,4,0,0,0,"");
	$regfilho 		= criarAtributo($conn,$entidadeID,"regfilho","Filho","int",0,0,4,0,0,0,"");