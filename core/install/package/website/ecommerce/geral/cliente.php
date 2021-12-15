<?php
	
	// Setando variáveis
	$entidadeNome = "ecommerce_cliente";
	$entidadeDescricao = "Cliente";
	
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

	Entity::setDescriptionField($conn,$entidadeID,$nome,true);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');
	
	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
	
	// Criar Aba
	$camposAba = array($nome,$genero,$datanascimento,$email,$senha,$telefone,$cpf);
	criarAba($conn,$entidadeID,"Capa",implode(",",$camposAba));

	/* *** ENDEREÇO *** */
	criarRelacionamento($conn,2,$entidadeID,installDependencia('ecommerce_endereco','package/website/ecommerce/geral/endereco'),"Endereço",0);