<?php
	$entidadeNome 		= "whatsapp";
	$entidadeDescricao 	= "Whatsapp";
	
	// 1º PASSO
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
		$registrounico = 1
	);
	
	// 2º PASSO	
	criarAtributo($conn,$entidadeID,"numero","Número","varchar","20",0,8,1,0,0,"");
	criarAtributo($conn,$entidadeID,"mensagem","Mensagem","varchar","250",1,3,1,0,0,"");