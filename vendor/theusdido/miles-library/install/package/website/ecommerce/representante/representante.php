<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_representante";
	$entidadeDescricao 	= "Representante";
	
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
	
	// Criando Atributos
	$usuario 			= criarAtributo($conn,$entidadeID,"usuario","Usuário","int",0,0,4,0,installDependencia("usuario","system/usuario"),0,"");
	$nome 				= criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$genero 			= criarAtributo($conn,$entidadeID,"genero",array("Gênero","Masculino","Feminino"),"char",0,0,7,1,0,0,"");
	$datanascimento 	= criarAtributo($conn,$entidadeID,"datanascimento","Data de Nascimento","date",0,0,11,0,0,0,"");
	$email 				= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",120,0,3,1,0,0,"");
	$cpf 				= criarAtributo($conn,$entidadeID,"cpf","CPF","varchar",35,0,10,1,0,0,"");
	$telefone 			= criarAtributo($conn,$entidadeID,"telefone","Telefone","varchar","35",1,8,0,0,0,"");

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');

	// Cria Relacionamento
	criarRelacionamento($conn,2,$entidadeID,installDependencia('ecommerce_endereco','package/website/ecommerce/endereco/endereco'),"Endereço",0);
