<?php
	$entidadeNome 		= "perfil";
	$entidadeDescricao 	= "Perfil";
	
	// 1 PASSO
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$descricao = $entidadeDescricao,
		$ncolunas=1,
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

	// 2 PASSO
	criarAtributo($conn,$entidadeID,"titulo","Titulo"	,"text","",0,3	,1,0,0,"");
	criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text","",1,21	,0,0,0,"");