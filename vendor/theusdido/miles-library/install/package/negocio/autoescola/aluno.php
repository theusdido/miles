<?php
	// Setando variáveis
	$entidadeNome = "erp_autoescola_aluno";
	$entidadeDescricao = "Aluno";

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
	$nome 				= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3);
	$cpf 				= criarAtributo($conn,$entidadeID,"cpf","CPF","varchar",14,0,10);
	$rg 				= criarAtributo($conn,$entidadeID,"rg","RG","varchar",50,0,3);
	$datanascimento 	= criarAtributo($conn,$entidadeID,"datanascimento","Data de Nascimento","date",0,1,11);
	$genero 			= criarAtributo($conn,$entidadeID,"genero","Gênero","tinyint",1,0,7);
	$estadocivil		= criarAtributo($conn,$entidadeID,"estadocivil","Estado Civíl","tinyint",0,0,4,getEntidadeId("estadocivil"));
	$telefone 			= criarAtributo($conn,$entidadeID,"telefone","Telefone","varchar","35",0,8,0,0,0,"");
	$email 				= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",120,0,12);
	$senha 				= criarAtributo($conn,$entidadeID,"senha","Senha","varchar",50,0,6,0,0,0,"");

	$logradouro			= criarAtributo($conn,$entidadeID,"logradouro","Logradouro","varchar",200,0,3);
	$numero				= criarAtributo($conn,$entidadeID,"numero","Número","varchar",5,1,3);
	$complemento		= criarAtributo($conn,$entidadeID,"complemento","Complemento","varchar",50,1,3);
	$bairro				= criarAtributo($conn,$entidadeID,"bairro","Bairro","varchar",30,0,3);
	$cidade				= criarAtributo($conn,$entidadeID,"cidade","Cidade","int",0,0,22);
	$cep				= criarAtributo($conn,$entidadeID,"cep","CEP","char",9,0,9);

	$comprovanteresidencia 	= criarAtributo($conn,$entidadeID,"comprovanteresidencia","Comprovante de Residencia","mediumblob",0,0,19);
	$documentocpf 			= criarAtributo($conn,$entidadeID,"documentocpf","CPF ( doc )","mediumblob",0,0,19);
	$documentorg 			= criarAtributo($conn,$entidadeID,"documentorg","RG ( doc )","mediumblob",0,0,19);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'CRM','#','',0,0,'crm');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'crm-' . $entidadeNome,$entidadeID,'cadastro');

	// Criar Aba
	criarAba($conn,$entidadeID,"Capa",implode(",",array($nome,$cpf,$rg,$datanascimento,$genero,$estadocivil,$telefone,$email,$senha)));
	criarAba($conn,$entidadeID,"Endereço",implode(",",array($logradouro,$numero,$complemento,$bairro,$cidade,$cep)));
	criarAba($conn,$entidadeID,"Documento",implode(",",array($comprovanteresidencia,$documentocpf,$documentorg)));