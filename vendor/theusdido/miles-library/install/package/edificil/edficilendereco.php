<?php
	// Setando variáveis
	$entidadeNome = "edficilendereco";
	$entidadeDescricao = "Endereço do Edifício";
	
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
	$endereco = criarAtributo($conn,$entidadeID,"endereco","Endereço","Int",0,0,4,0,getEntidadeId("imobiliaria_endereco",$conn));
	$edficil = criarAtributo($conn,$entidadeID,"edficil","Edfícil","Int",0,0,4,0,getEntidadeId("imobiliaria_edficil",$conn));
	