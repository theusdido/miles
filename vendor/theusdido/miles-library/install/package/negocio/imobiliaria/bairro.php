<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_bairro";
	$entidadeDescricao = "Bairro";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3);
	$cidade = criarAtributo($conn,$entidadeID,"cidade","Cidade","int",0,1,22,0,installDependencia('imobiliaria_cidade','package/negocio/imobiliaria/cidade'),0,"",1,0);