<?php
	$entidadeNome = "aplicativo_usuario";
	$entidadeDescricao = "Usuário";
	
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
		$registrounico = 0,
		$criarinativo = true
	);

	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,1,3);
	$celular 	= criarAtributo($conn,$entidadeID,"celular","Celular","varchar",15,1,8);