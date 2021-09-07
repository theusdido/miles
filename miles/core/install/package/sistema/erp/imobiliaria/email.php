<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_email";
	$entidadeDescricao = "E-Mail";

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
	$pessoa = criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,0,16,0,getEntidadeId('erp_imobiliaria_pessoa',$conn),0,"",1,0);
	$principal  = criarAtributo($conn,$entidadeID,"principal","Principal","varchar",200,0,12);
	$nfse  = criarAtributo($conn,$entidadeID,"nfse","NFSE","varchar",200,1,12);
	$boleto  = criarAtributo($conn,$entidadeID,"boleto","Boleto","varchar",200,1,12);

	criarRelacionamento(
		$conn,
		6,
		getEntidadeId("erp_imobiliaria_pessoa",$conn),
		$entidadeID,
		"E-Mail",
		$pessoa
	);