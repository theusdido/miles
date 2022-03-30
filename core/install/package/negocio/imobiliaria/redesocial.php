<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_redesocial";
	$entidadeDescricao = "Rede Social";

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
	$pessoa = criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,0,16,0,getEntidadeId('imobiliaria_pessoa',$conn),0,"",1,0);
	$redesocial = criarAtributo($conn,$entidadeID,"redesocial","Rede Social","int",0,0,16,0,getEntidadeId('erp_geral_redesocial',$conn),0,"",1,0);
	$link  = criarAtributo($conn,$entidadeID,"link","Link","varchar",200,1,3);

	criarRelacionamento(
		$conn,
		6,
		getEntidadeId("imobiliaria_pessoa",$conn),
		$entidadeID,
		"Rede Social",
		$pessoa
	);