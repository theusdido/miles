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
	$pais_nome 	= criarAtributo($conn,$paisID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$pais_sigla	= criarAtributo($conn,$paisID,"sigla","Sigla","char","2",0,3,1,0,0,"");
	Entity::setDescriptionField($conn,$paisID,$pais_nome,true);
	
	// Adicionando Menu
	addMenu($conn,"País","files/cadastro/".$paisID."/".getSystemPREFIXO()."pais.html",'',$menu_geral,0,'geral-pais',$paisID,'cadastro');

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
	$uf_nome	= criarAtributo($conn,$ufID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$uf_sigla	= criarAtributo($conn,$ufID,"sigla","Sigla","char","2",0,3,1,0,0,"");
	$uf_pais	= criarAtributo($conn,$ufID,"pais","País","int",0,1,4,1,$paisID,0,"");
	Entity::setDescriptionField($conn,$ufID,$uf_sigla,true);

	// Adicionando Menu
	addMenu($conn,"Estado ( UF )","files/cadastro/".$ufID."/".getSystemPREFIXO()."uf.html",'',$menu_geral,0,'geral-uf',$ufID,'cadastro');

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
	$cidade_nome 		= criarAtributo($conn,$cidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$cidade_bairromap	= criarAtributo($conn,$cidadeID,"bairromapeado","Bairro Mapeado","tinyint",0,0,7,0,0,0,"");
	$cidade_uf			= criarAtributo($conn,$cidadeID,"uf","UF","int",0,0,4,1,$ufID,0,"");
	Entity::setDescriptionField($conn,$cidadeID,$cidade_nome,true);

	// Adicionando Menu
	addMenu($conn,"Cidade","files/cadastro/".$cidadeID."/".getSystemPREFIXO()."cidade.html",'',$menu_geral,0,'geral-cidade',$cidadeID,'cadastro');

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
	$bairro_nome	= criarAtributo($conn,$bairroID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$bairro_cidade	= criarAtributo($conn,$bairroID,"cidade","Cidade","int",0,0,22,1,$cidadeID,0,"");
	Entity::setDescriptionField($conn,$bairroID,$bairro_nome,true);

	// Adicionando Menu
	addMenu($conn,"Bairro","files/cadastro/".$bairroID."/".getSystemPREFIXO()."bairro.html",'',$menu_geral,0,'geral-bairro',$bairroID,'cadastro');
	
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
	$endereco_bairro		= criarAtributo($conn,$enderecoID,"bairro","Bairro","varchar","35",0,22,1,$bairroID,0,"");
	$endereco_logradouro	= criarAtributo($conn,$enderecoID,"logradouro","Logradouro","varchar","200",0,3,1,0,0,"");
	$endereco_numero		= criarAtributo($conn,$enderecoID,"numero","Número","varchar","5",0,3,1,0,0,"");
	$endereco_complemento	= criarAtributo($conn,$enderecoID,"complemento","Complemento","varchar","200",0,3,1,0,0,"");
	$endereco_cep			= criarAtributo($conn,$enderecoID,"cep","CEP","varchar","10",0,9,1,0,0,"");
	Entity::setDescriptionField($conn,$enderecoID,$endereco_logradouro,true);

	// Adicionando Menu
	addMenu($conn,"Endereço","files/cadastro/".$enderecoID."/".getSystemPREFIXO()."endereco.html",'',$menu_geral,0,'geral-endereco',$enderecoID,'cadastro');
