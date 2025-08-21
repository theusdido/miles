<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_negociacaoetapa";
	$entidadeDescricao = "Negociação Etapa";

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
	$negociacao = criarAtributo($conn,$entidadeID,"negociacao","Negociação","int",0,0,22,0,installDependencia("imobiliaria_negociacao",'package/negocio/imobiliaria/negociacao'));
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3);
	$encerrada = criarAtributo($conn,$entidadeID,"encerrada","Encerrada ?","tinyint",0,0,7);
	$ordem = criarAtributo($conn,$entidadeID,"ordem","Ordem","int",0,0,25);