<?php
	// Setando variáveis
	$entidadeNome = "erp_geral_funcionario";
	$entidadeDescricao = "Funcionário";
	
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
	$dataadimissao = criarAtributo($conn,$entidadeID,"dataadminissao","Data de Adimissão","date",0,1,11);
	$salario = criarAtributo($conn,$entidadeID,"salario","Salário","float",0,1,13);

	// Criando Relacionamento
	criarRelacionamento($conn,9,installDependencia($conn,"erp_geral_pessoa"),$entidadeID,"Funcionário",0);;