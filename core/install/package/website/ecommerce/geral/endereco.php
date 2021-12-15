<?php
	
	// Estado
	$paisID = criarEntidade(
		$conn,
		"ecommerce_pais",
		"País",
		$ncolunas=1,
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

	criarAtributo($conn,$paisID,"nome","Nome","varchar",200,0,3,1,0,0,"");
	criarAtributo($conn,$paisID,"sigla","Sigla","varchar",5,0,3,1,0,0,"");

	// Estado
	$entidadeNomeUF 		= "ecommerce_uf";
	$entidadeDescricaoUF 	= "Estado ( UF )";
	$ufID = criarEntidade(
		$conn,
		$entidadeNomeUF,
		$entidadeDescricaoUF,
		$ncolunas=1,
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
	$uf_nome 	= criarAtributo($conn,$ufID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$uf_sigla 	= criarAtributo($conn,$ufID,"sigla","Sigla","char","2",0,3,1,0,0,"");
	$uf_pais 	= criarAtributo($conn,$ufID,"pais","País","int",0,0,4,1,$paisID,0,"");
	Entity::setDescriptionField($conn,$ufID ,$uf_nome,true);

	// Cidade
	$entidadeNomeCidade 		= "ecommerce_cidade";
	$entidadeDescricaoCidade 	= "Cidade ( Localidade )";
	$cidadeID = criarEntidade(
		$conn,
		$entidadeNomeCidade,
		$entidadeDescricaoCidade,
		$ncolunas=1,
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
	criarAtributo($conn,$cidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$cidadeID,"bairromapeado","Bairro Mapeado","tinyint",0,0,7,0,0,0,"");
	criarAtributo($conn,$cidadeID,"uf","UF","int",0,0,4,1,$ufID,0,"");


	// Bairro
	$entidadeNomeBairro 		= "ecommerce_bairro";
	$entidadeDescricaoBairro 	= "Bairro";
	$bairroID = criarEntidade(
		$conn,
		$entidadeNomeBairro,
		$entidadeDescricaoBairro,
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
	criarAtributo($conn,$bairroID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$bairroID,"cidade","Cidade","int",0,0,22,1,$cidadeID,0,"");
	
	// Endereço
	$entidadeNomeEndereco 		= "ecommerce_endereco";
	$entidadeDescricaoEndereco 	= "Endereço";
	$enderecoID = criarEntidade(
		$conn,
		$entidadeNomeEndereco,
		$entidadeDescricaoEndereco,
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

	criarAtributo($conn,$enderecoID,"bairro","Bairro","varchar","35",0,22,1,$bairroID,0,"");
	criarAtributo($conn,$enderecoID,"logradouro","Logradouro","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$enderecoID,"numero","Número","varchar","5",0,3,1,0,0,"");
	criarAtributo($conn,$enderecoID,"complemento","Complemento","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$enderecoID,"cep","CEP","varchar","10",0,9,1,0,0,"");