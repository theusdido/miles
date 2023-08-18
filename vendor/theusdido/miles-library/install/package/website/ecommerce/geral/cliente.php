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

	// Capa
	$codigo				= criarAtributo($conn,$entidadeID,"codigo","Código","varchar",15,1,3,0,0,0,"");
	$tipopessoa			= criarAtributo($conn,$entidadeID,"tipopessoa",array('Tipo Pessoa','Pessoa Física','Pessoa Jurídica'),"tinyint",0,0,7);
	$nome 				= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3,1,0,0,"");
	$email 				= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",200,1,12,0,0,0,"");
	$senha 				= criarAtributo($conn,$entidadeID,"senha","Senha","varchar",128,1,6,0,0,0,"");
	$telefone 			= criarAtributo($conn,$entidadeID,"telefone","Telefone","varchar",35,1,8,0,0,0,"");
	$fotoperfil 		= criarAtributo($conn,$entidadeID,"fotoperfil","Foto (Perfil)","mediumblob",0,1,19);	
	$pontos 	    	= criarAtributo($conn,$entidadeID,"pontos","Pontos","float",0,1,26,0,0,0,"");
	$observacao			= criarAtributo($conn,$entidadeID,"observacao","Observação","text",0,1,14,0);

	// Pessoa Física
	$cpf 				= criarAtributo($conn,$entidadeID,"cpf","CPF","varchar",35,1,10,1,0,0,"",0,0);
	$genero 			= criarAtributo($conn,$entidadeID,"genero","Gênero","tinyint",0,1,7,0);
	$datanascimento 	= criarAtributo($conn,$entidadeID,"datanascimento","Data de Nascimento","date",0,1,11,0);

	// Situação Tributária 
	$situacao_tributaria_entidade = installDependencia('erp_contabil_situacaotributaria','package/sistema/erp/contabil/situacaotributaria');

	// Pessoa Jurídica
	$nomefantasia		= criarAtributo($conn,$entidadeID,"nomefantasia","Nome Fantasia","varchar",200,1,3,0,0,0,"");
	$cnpj 				= criarAtributo($conn,$entidadeID,"cnpj","CNPJ","varchar",35,1,15,1,0,0,"",0,0);
	$inscricaoestadual  = criarAtributo($conn,$entidadeID,"inscricaoestadual","Inscrição Estadual","varchar",35,1,3,0,0,0,"",0,0);
	$situacaotributaria = criarAtributo($conn,$entidadeID,"situacaotributaria","Situação Tributária","tinyint",0,1,4,0,$situacao_tributaria_entidade,0,"",0,0);
	$telefoneextra		= criarAtributo($conn,$entidadeID,"telefoneextra","Telefone Extra","varchar",35,1,8,0,0,0,"",0,0);

	Entity::setDescriptionField($conn,$entidadeID,$nome,true);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');

	// Aba Capa
	$camposAba = array($codigo,$nome,$email,$telefone,$fotoperfil,$observacao);
	criarAba($conn,$entidadeID,"Capa",implode(",",$camposAba));

	// Aba Física
	$camposAba = array($cpf,$genero,$datanascimento);
	criarAba($conn,$entidadeID,"Física",implode(",",$camposAba));

	// Aba Jurídica
	$camposAba = array($nomefantasia,$cnpj,$inscricaoestadual,$situacaotributaria,$telefoneextra);
	criarAba($conn,$entidadeID,"Jurídica",implode(",",$camposAba));

	/* *** ENDEREÇO *** */
	criarRelacionamento($conn,6,$entidadeID,installDependencia('ecommerce_endereco','package/website/ecommerce/endereco/endereco'),"Endereço",0);