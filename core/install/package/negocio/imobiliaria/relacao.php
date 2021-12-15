<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_pessoarelacao";
	$entidadeDescricao = "Pessoa Relação";

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
	$relacao = criarAtributo($conn,$entidadeID,"relacao","Relação","int",0,0,4,0,getEntidadeId('erp_geral_relacao',$conn),0,"",1,0);
	$pessoarelacao = criarAtributo($conn,$entidadeID,"pessoarelacao","Pessoa","int",0,0,22,0,getEntidadeId('erp_imobiliaria_pessoa',$conn),0,"",1,0);

	criarRelacionamento(
		$conn,
		6,
		getEntidadeId("erp_imobiliaria_pessoa",$conn),
		$entidadeID,
		"Relação",
		$pessoa
	);