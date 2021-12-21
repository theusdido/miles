<?php

	// Setando variáveis
	$entidadeNome 		= "ecommerce_cliente";
	$entidadeDescricao 	= "Cliente";
	
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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$nome 				= criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$genero 			= criarAtributo($conn,$entidadeID,"genero","Gênero","tinyint",1,1,7,1,0,0,"");
	$datanascimento 	= criarAtributo($conn,$entidadeID,"datanascimento","Data de Nascimento","date",0,1,11,0,0,0,"");
	$email 				= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",120,0,3,1,0,0,"");
	$senha 				= criarAtributo($conn,$entidadeID,"senha","Senha","varchar",50,0,6,0,0,0,"");
	$telefone 			= criarAtributo($conn,$entidadeID,"telefone","Telefone","varchar","35",1,8,0,0,0,"");
	$cpf 				= criarAtributo($conn,$entidadeID,"cpf","CPF","varchar",35,1,10,1,0,0,"",0,0);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');
	
	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
	
	// Criar Aba
	$camposAba = array($nome,$genero,$datanascimento,$email,$senha,$telefone,$cpf);
	criarAba($conn,$entidadeID,"Capa",implode(",",$camposAba));
	
	/* *** ENDEREÇO *** */
	
	// Criando Acesso
	$menu_geral = addMenu($conn,'Geral','#','',0,0,'geral');
	
	// Estado
	$ufID = criarEntidade(
		$conn,
		"ecommerce_uf",
		"Estado",
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);	
	criarAtributo($conn,$ufID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$ufID,"sigla","Sigla","char","2",0,3,1,0,0,"");

	// Adicionando Menu
	addMenu($conn,"Estado ( UF )","files/cadastro/".$ufID."/".PREFIXO."uf.html",'',$menu_geral,0,'geral-uf',$ufID,'cadastro');
	
	// Cidade
	$cidadeID = criarEntidade(
		$conn,
		"ecommerce_cidade",
		"Cidade",
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);
	criarAtributo($conn,$cidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$cidadeID,"bairromapeado","Bairro Mapeado","boolean",0,1,7);
	criarAtributo($conn,$cidadeID,"uf","UF","int",0,0,4,1,$ufID,0,"");
	
	// Adicionando Menu
	addMenu($conn,"Cidade","files/cadastro/".$cidadeID."/".PREFIXO."cidade.html",'',$menu_geral,0,'geral-cidade',$cidadeID,'cadastro');
	
	// Bairro
	$bairroID = criarEntidade(
		$conn,
		"ecommerce_bairro",
		"Bairro",
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);
	criarAtributo($conn,$bairroID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$bairroID,"cidade","Cidade","int",0,0,22,1,$cidadeID,0,"");
	
	// Adicionando Menu
	addMenu($conn,"Bairro","files/cadastro/".$bairroID."/".PREFIXO."bairro.html",'',$menu_geral,0,'geral-bairro',$bairroID,'cadastro');
	
	// Endereço
	$enderecoID = criarEntidade(
		$conn,
		"ecommerce_endereco",
		"Endereço",
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);	
	criarAtributo($conn,$enderecoID,"bairro","Bairro","varchar","35",0,22,1,$bairroID,0,"");
	criarAtributo($conn,$enderecoID,"logradouro","Logradouro","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$enderecoID,"numero","Número","varchar","5",0,3,1,0,0,"");
	criarAtributo($conn,$enderecoID,"complemento","Complemento","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$enderecoID,"cep","CEP","varchar","10",0,9,1,0,0,"");
	criarAtributo($conn,$entidadeID,"cidade","Cidade","int",0,1,16,0,$cidadeID,0,'',1,0,'');

	// Adicionando Menu
	addMenu($conn,"Endereço","files/cadastro/".$enderecoID."/".PREFIXO."endereco.html",'',$menu_geral,0,'geral-endereco',$enderecoID,'cadastro');

	// Cria Relacionamento
	criarRelacionamento($conn,2,$entidadeID,$enderecoID,"Endereço",0);