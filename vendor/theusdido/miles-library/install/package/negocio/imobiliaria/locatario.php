<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_locatario";
	$entidadeDescricao = "Locatário";

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
	$quantidadeocúpantesimoveis = criarAtributo($conn,$entidadeID,"quantidadeocupantesimoveis","Qunatidade Ocupantes no Imóvei","int",0,1,25);
	$quantidadecontribuintes = criarAtributo($conn,$entidadeID,"quantidadecontribuintes","Quantidade de Pessoas Contribuintes","int",0,1,25);
	$temanimais = criarAtributo($conn,$entidadeID,"temanimais","Tem Animais ?","tinyint",0,1,7);
	$citeanimais  = criarAtributo($conn,$entidadeID,"citeanimais","Cite Animais","varchar",200,1,3);

	criarRelacionamento(
		$conn,
		9,
		getEntidadeId("imobiliaria_pessoa",$conn),
		$entidadeID,
		"Locatário",
		$pessoa
	);