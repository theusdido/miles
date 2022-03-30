<?php
	// Setando variáveis
	$entidadeNome = "erp_geral_pessoajuridica";
	$entidadeDescricao = "Pessoa Jurídica";

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
		$registrounico = 0,
		0,
		false
	);

	// Criando Atributos
	$pessoa 				= criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,0,16,1,getEntidadeId('erp_geral_pessoa',$conn),0,"",1,0);
	$razaosocial 			= criarAtributo($conn,$entidadeID,"razaosocial","Razão Social","varchar","200",1,3,1,0,0,"");
	$cnpj 					= criarAtributo($conn,$entidadeID,"cnpj","CNPJ","varchar","25",0,15,1,0,0,"",1,0);
	$atividadecomercial 	= criarAtributo($conn,$entidadeID,"atividadecomercial","Atividade Comercial","varchar","200",1,3,1,0,0,"");
	$datafundacao 			= criarAtributo($conn,$entidadeID,"datafundacao","Data de Fundação","date",0,0,11,0,0,0,"",1,0);
	$inscricaoestadual 		= criarAtributo($conn,$entidadeID,"inscricaoestadual","Inscrição Estadual","varchar","200",1,3,1,0,0,"");
	$inscricaomunicipal 	= criarAtributo($conn,$entidadeID,"inscricaomunicipal","Inscrição Municipal","varchar","200",1,3,1,0,0,"");
	$site 					= criarAtributo($conn,$entidadeID,"site","Site","varchar","200",1,3,1,0,0,"");

	criarRelacionamento(
		$conn,
		3,
		getEntidadeId("erp_geral_pessoa",$conn),
		$entidadeID,
		"Jurídica",
		$pessoa
	);