<?php
	// Setando variáveis
	$entidadeNome = "charset";
	$entidadeDescricao = "CharSet";
	
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
	$local = criarAtributo($conn,$entidadeID,"local","Local","varchar",500,0,3);
	$charset = criarAtributo($conn,$entidadeID,"charset","CharSet","char",1,0,3);
	include "charsetarquivos.php";
