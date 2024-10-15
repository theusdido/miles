<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_listainteresseimovel";
	$entidadeDescricao = "Lista de Interesse Imóvel";

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
	$imovel = criarAtributo($conn,$entidadeID,"imovel","Imóvel","int",0,1,4,0,installDependencia($conn,'erp_imobiliaria_imovel'),0,"",1,0);
	$listainteresse = criarAtributo($conn,$entidadeID,"listainteresse","Lista Interesse","int",0,1,4,0,installDependencia($conn,'erp_imobiliaria_listainteresse'),0,"",1,0);
