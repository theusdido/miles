<?php
	
	// Setando variáveis
	$entidadeNome 		= "ecommerce_loja";
	$entidadeDescricao 	= "Loja";

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
	$razaosocial		= criarAtributo($conn,$entidadeID,"razaosocial","Razão Social","varchar",200,0,3,1,0,0,"");
	$email 				= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",120,0,3,1,0,0,"");
	$telefone 			= criarAtributo($conn,$entidadeID,"telefone","Telefone","varchar",35,1,8,0,0,0,"");
	$cnpj 				= criarAtributo($conn,$entidadeID,"cnpj","CNPJ","varchar",35,1,15,1,0,0,"",0,0);
	$logo 				= criarAtributo($conn,$entidadeID,"logo","Foto (Perfil)","mediumblob",0,1,19);

	Entity::setDescriptionField($conn,$entidadeID,$razaosocial,true);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');
	
	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
	
	// Criar Aba
	$camposAba = array($razaosocial,$email,$telefone,$cnpj);
	criarAba($conn,$entidadeID,"Capa",implode(",",$camposAba));

	/* *** ENDEREÇO *** */
	criarRelacionamento($conn,2,$entidadeID,installDependencia('ecommerce_endereco','package/website/ecommerce/endereco/endereco'),"Endereço",0);

	// Configuração de Entrega
	criarRelacionamento($conn,1,$entidadeID,installDependencia('ecommerce_lojaentrega','package/website/ecommerce/envio/lojaentrega'),"Entrega",0);
