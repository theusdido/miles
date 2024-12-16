<?php

	// Setando variáveis
	$entidadeNome = "imobiliaria_chaves";
	$entidadeDescricao = "Chaves";

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
	$tipochave 	= criarAtributo($conn,$entidadeID,"tipochave","Tipo de Chave","int",0,1,4,1,installDependencia('imobiliaria_tipochave','package/negocio/imobiliaria/tipochave'),0,"",1,0);
	$quantidade = criarAtributo($conn,$entidadeID,"quantidade","Quantidade","int",0,1,25,1);
	$imovel 	= criarAtributo($conn,$entidadeID,"imovel","Imóvel","int",0,1,16,0,installDependencia('imobiliaria_imovel','package/negocio/imobiliaria/imovel'));

	// Criar Relacionamento
	criarRelacionamento($conn,6,$imovel,$entidadeID,"Chaves",$imovel);