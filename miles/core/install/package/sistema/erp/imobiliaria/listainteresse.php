<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_listainteresse";
	$entidadeDescricao = "Lista de Interesse";

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

	// Criando Atributosf
	$pessoainteresse = criarAtributo($conn,$entidadeID,"pessoainteresse","Pessoa","int",0,1,4,0,installDependencia($conn,'erp_imobiliaria_pessoa'),0,"",1,0);
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",1,3,1,0,0,"");
	$cpfj = criarAtributo($conn,$entidadeID,"cpfj","CPF/CNPJ","varchar","25",0,17,1,0,0,"",1,0);
	$rg = criarAtributo($conn,$entidadeID,"rg","RG","varchar","35",1,3,1,0,0,"");
	$telefone = criarAtributo($conn,$entidadeID,"telefone","Telefone","varchar","25",1,8,1,0,0,"");
	
	$listainteresseimovel = installDependencia($conn,'erp_imobiliaria_listainteresseimovel');
	