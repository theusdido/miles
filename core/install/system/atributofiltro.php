<?php
	// Setando variáveis
	$entidadeNome = "atributofiltro";
	$entidadeDescricao = "Atributo Filtro";

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$entidade = criarAtributo($conn,$entidadeID,"entidade","Entidade","int",0,0,4,0,0,0,"");
	$atributo = criarAtributo($conn,$entidadeID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$campo = criarAtributo($conn,$entidadeID,"campo","Campo","int",0,0,4,0,0,0,"");
	$operador = criarAtributo($conn,$entidadeID,"operador","Operador","varchar",5,0,3,0,0,0,"");
	$valor = criarAtributo($conn,$entidadeID,"valor","Valor","varchar",200,0,3,0,0,0,"");