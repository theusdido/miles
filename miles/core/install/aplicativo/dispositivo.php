<?php
	$entidadeNome = "aplicativo_dispositivo";
	$entidadeDescricao = "Dispositivo";

	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	$usuario 	= criarAtributo($conn,$entidadeID,"usuario","Usuário","int",0,1,16);
	$token 		= criarAtributo($conn,$entidadeID,"token","Token","varchar",256,0,3);
	$aparelho 	= criarAtributo($conn,$entidadeID,"aparelho","Iparelho","varchar",32,0,3);
	$inativo 	= criarAtributo($conn,$entidadeID,"inativo","Inativo","boolean",true,0,7);