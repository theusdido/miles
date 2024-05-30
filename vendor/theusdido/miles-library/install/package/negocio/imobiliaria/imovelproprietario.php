<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_imovelproprietario";
	$entidadeDescricao = "Imóvel Proprietário";

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
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$pessoa = criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,0,22,1,installDependencia('imobiliaria_pessoa','package/negocio/imobiliaria/pessoa'),0,"",1,0);
	$percentualparticipacaoimovel = criarAtributo($conn,$entidadeID,"percentualparticipacaoimovel","% Participação Imóvel","int",0,1,25,1);

	// Criando Relacionamento
	criarRelacionamento(
		$conn,
		2,
		installDependencia("imobiliaria_imovel",'package/negocio/imobiliaria/imovel'),
		$entidadeID,
		"Proprietário(s)",
		$pessoa
	);	