<?php
	// Setando variáveis
	$entidadeNome = "erp_geral_pessoafisica";
	$entidadeDescricao = "Pessoa Física";

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0,
		0,
		false
	);

	// Criando Atributos
	$pessoa 			= criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,1,16,0,installDependencia('erp_geral_pessoa','package/sistema/erp/pessoa/pessoa'),0,"",1,0);
	$cpf 				= criarAtributo($conn,$entidadeID,"cpf","CPF","varchar","15",0,10,1,0,0,"",1,0);
	$genero 			= criarAtributo($conn,$entidadeID,"genero","Gênero","int",0,0,4,0,installDependencia('erp_geral_genero','package/sistema/erp/geral/genero'),0,"",1,0);
	$estadocivil 		= criarAtributo($conn,$entidadeID,"estadocivil","Estado Civil","int",0,0,4,0,installDependencia('erp_geral_estadocivil','package/sistema/erp/geral/estadocivil'),0,"",1,0);
	$datanascimento 	= criarAtributo($conn,$entidadeID,"datanascimento","Data de Nascimento","date",0,0,11,0,0,0,"",1,0);
	$nacionalidade 		= criarAtributo($conn,$entidadeID,"nacionalidade","Nacionalidade","int",0,0,4,0,installDependencia('erp_geral_nacionalidade','package/sistema/erp/geral/nacionalidade'),0,"",1,0);
	$profissao 			= criarAtributo($conn,$entidadeID,"profissao","Profissão","int",0,0,4,0,installDependencia('erp_geral_profissao','package/sistema/erp/geral/profissao'),0,"",1,0);
	$pai 				= criarAtributo($conn,$entidadeID,"pai","Pai","varchar",120,0,3,0);
	$mae 				= criarAtributo($conn,$entidadeID,"mae","Mãe","varchar",120,0,3,0);

	// Criando Relacionamento
	criarRelacionamento(
		$conn,
		3,
		installDependencia("erp_pessoa",'package/sistema/erp/pessoa/pessoa'),
		$entidadeID,
		"Física",
		$pessoa
	);