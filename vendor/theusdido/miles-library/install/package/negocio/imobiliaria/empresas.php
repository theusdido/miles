<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_empresas";
	$entidadeDescricao = "Empresas";

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
	$razaosocial = criarAtributo($conn,$entidadeID,"razaosocial","Razão Social","varchar",200,0,3,1);
	$cnpj = criarAtributo($conn,$entidadeID,"cnpj","CNPJ","int",0,1,15);
	$inscricaoestadual = criarAtributo($conn,$entidadeID,"inscricaoestadual","Inscrição Estadual","varchar",40,1,3);
	$inscricaomunicipal = criarAtributo($conn,$entidadeID,"inscricaomunicipal","Inscrição Municipal ","varchar",40,1,3);
	$telefone = criarAtributo($conn,$entidadeID,"telefone","Telefone","varchar",200,1,8);
	$email = criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",200,1,12);
	$site = criarAtributo($conn,$entidadeID,"site","Site","varchar",300,1,3);
	
	// Abas
	criarAba($conn,$entidadeID,"Capa",
		array(
			$razaosocial,$cnpj,$inscricaoestadual,$inscricaomunicipal,$telefone,$email,$site
		)
	);	
	
	/* *** ENDEREÇO *** */
	$estado = criarAtributo($conn,$entidadeID,"estado","Estado","int",0,0,4,0,getEntidadeId("imobiliaria_estado",$conn));
	$cidade = criarAtributo($conn,$entidadeID,"cidade","Cidade","int",0,0,4,0,getEntidadeId("imobiliaria_cidade",$conn));
	$bairro = criarAtributo($conn,$entidadeID,"bairro","Bairro","varchar",200,0,3);
	$logradouro = criarAtributo($conn,$entidadeID,"logradouro","Logradouro","varchar",200,0,3);
	$numero = criarAtributo($conn,$entidadeID,"numero","Número","varchar",5,1,3);
	$complemento = criarAtributo($conn,$entidadeID,"complemento","Complemento","varchar",50,1,3);
	$cep = criarAtributo($conn,$entidadeID,"cep","CEP","varchar",9,1,9);

	// Abas
	criarAba($conn,$entidadeID,"Endereço",
		array(
			$estado,$cidade,$bairro,$logradouro,$numero,$complemento,$cep
		)
	);	

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');
	
	
	
	