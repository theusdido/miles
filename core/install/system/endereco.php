<?php
	// Criando Acesso
	$menu_geral = addMenu($conn,'Geral','#','',0,0,'geral');

	// Estado
	$paisID = criarEntidade(
		$conn,
		"pais",
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
	criarAtributo($conn,$paisID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$paisID,"sigla","Sigla","char","2",0,3,1,0,0,"");

	// Estado
	$ufID = criarEntidade(
		$conn,
		"uf",
		"Estado",
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
	criarAtributo($conn,$ufID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$ufID,"sigla","Sigla","char","2",0,3,1,0,0,"");
	criarAtributo($conn,$ufID,"pais","País","int",0,1,4,1,$paisID,0,"");
	
	// Adicionando Menu
	addMenu($conn,"Estado ( UF )","files/cadastro/".$ufID."/".PREFIXO."uf.html",'',$menu_geral,0,'geral-uf');

	// Cidade
	$cidadeID = criarEntidade(
		$conn,
		"cidade",
		"Cidade",
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

	// Adicionando Menu
	addMenu($conn,"Cidade","files/cadastro/".$cidadeID."/".PREFIXO."cidade.html",'',$menu_geral,0,'geral-cidade');

	// Bairro
	$bairroID = criarEntidade(
		$conn,
		"bairro",
		"Bairro",
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

	// Adicionando Menu
	addMenu($conn,"Bairro","files/cadastro/".$bairroID."/".PREFIXO."bairro.html",'',$menu_geral,0,'geral-bairro');
	
	// Endereço
	$enderecoID = criarEntidade(
		$conn,
		"endereco",
		"Endereço",
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
	
	// Adicionando Menu
	addMenu($conn,"Endereço","files/cadastro/".$enderecoID."/".PREFIXO."endereco.html",'',$menu_geral,0,'geral-endereco');