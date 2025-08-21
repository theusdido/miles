<?php	
	// Status
	$statusID = criarEntidade(
		$conn,
		"status",
		"Status",
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$descricao = criarAtributo($conn,$statusID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$classe = criarAtributo($conn,$statusID,"classe","Classe","varchar",100,0,3,1,0,0,"");