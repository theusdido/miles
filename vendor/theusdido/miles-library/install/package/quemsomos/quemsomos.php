<?php
	$entidadeNome 		= "quemsomos";
	$entidadeDescricao 	= "Quem Somos";

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 1
	);

	$texto 			= criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text",0,1,21	,0,0,0,"");
	$imagem			= criarAtributo($conn,$entidadeID,"imagem","Imagem"	,"text",0,1,19,1);
	
	criarAba($conn,$entidadeID,"Capa", array($texto,$imagem));	
