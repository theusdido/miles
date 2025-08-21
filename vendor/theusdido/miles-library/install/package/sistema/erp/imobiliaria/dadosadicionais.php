<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_dadosadicionais";
	$entidadeDescricao = "Dados Adicionais";

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
	$conceito = criarAtributo($conn,$entidadeID,"conceito","Conceito","varchar",5,1,3);
	$mensagemextrato = criarAtributo($conn,$entidadeID,"mensagemextrato","Mensagem Extrato","varchar",200,1,3);
	$descontro = criarAtributo($conn,$entidadeID,"desconto","% de Desconto","int",0,1,26);
	$despesabancaria = criarAtributo($conn,$entidadeID,"despesabancaria","Despesa Bancária ?","tinyint",0,1,7);
	$despesapostal = criarAtributo($conn,$entidadeID,"despesapostal","Despesa Postal ?","tinyint",0,1,7);
	$imprimeextrato = criarAtributo($conn,$entidadeID,"imprimeextrato","Imprime Extrato ?","tinyint",0,1,7);
	$resideexterior = criarAtributo($conn,$entidadeID,"resideexterior","Reside no Exterior ?","tinyint",0,1,7);

	criarRelacionamento(
		$conn,
		1,
		getEntidadeId("erp_imobiliaria_pessoa",$conn),
		$entidadeID,
		"Dados Adicionais",
		$pessoa
	);