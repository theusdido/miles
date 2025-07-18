<?php
	$entidadeNome 		= "googlemaps";
	$entidadeDescricao 	= "Google Maps";

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

	// 2 PASSO
	criarAtributo($conn,$entidadeID,"iframe"	,"IFrame"	,"text","",1,21	,0,0,0,"");