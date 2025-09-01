<?php
	// Setando variáveis
	$entidadeNome 		= "imobiliaria_imovelfoto";
	$entidadeDescricao 	= "Imóvel Foto";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// ID da Entidade do Imóvel
	$imovel_entidade = installDependencia('imobiliaria_imovel','package/negocio/imobiliaria/imovel/imovel');

	// Criando Atributos
	$imovel 	= criarAtributo($conn,$entidadeID,"imovel","Imóvel","int",0,1,16,0,$imovel_entidade,0,"",1,0);
	$legenda 	= criarAtributo($conn,$entidadeID,"legenda","Legenda","varchar","200",1,3,1,0,0,"");
	$foto 		= criarAtributo($conn,$entidadeID,"foto","Foto","text",0,0,19,0,0,0,'',1,0);

	// Criando Relacionamento
	criarRelacionamento(
		$conn,
		6,
		$imovel_entidade,
		$entidadeID,
		"Fotos",
		$imovel
	);