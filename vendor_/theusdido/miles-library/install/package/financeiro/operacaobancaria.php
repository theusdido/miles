<?php
	// Setando variáveis
	$entidadeNome 		= "operacaobancaria";
	$entidadeDescricao 	= "Operação Bancária";

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

	addAutoIncrement($entidadeID);

	// Criando Atributos
	$descricao 		= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",50,0,3,1);
	$codigo 		= criarAtributo($conn,$entidadeID,"codigo","Código","int",0,1,25);
	$banco			= criarAtributo($conn,$entidadeID,"banco","Banco","int",0,0,4,0,installDependencia("erp_financeiro_banco","package/sistema/erp/financeiro/banco"));

	// Campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);