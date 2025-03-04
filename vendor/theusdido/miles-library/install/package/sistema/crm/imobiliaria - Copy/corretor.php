<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_corretor";
	$entidadeDescricao = "Corretor";

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
	$estadocivil = criarAtributo($conn,$entidadeID,"estadocivil","Estado Cívil","int",0,0,4,0,getEntidadeId("crm_imobiliaria_estadocivil",$conn));
	$cpf = criarAtributo($conn,$entidadeID,"cpf","CPF","varchar",14,0,10);
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3);
	$datanascimento = criarAtributo($conn,$entidadeID,"datanascimento","Data de Nascimento","date",0,0,11);
	$naturalidade = criarAtributo($conn,$entidadeID,"naturalidade","Naturalidade","int",0,0,4,0,getEntidadeId("crm_imobiliaria_naturalidade",$conn));
	$sexo = criarAtributo($conn,$entidadeID,"sexo","Sexo","int",0,0,4,0,getEntidadeId("crm_imobiliaria_sexo",$conn));
	$nacionalidade = criarAtributo($conn,$entidadeID,"nacionalidade","Nacionalidade","int",0,0,4,0,getEntidadeId("crm_imobiliaria_nacionalidade",$conn));
	$rg = criarAtributo($conn,$entidadeID,"rg","RG","varchar",35,0,1);
	$telefoneresidencial = criarAtributo($conn,$entidadeID,"telefoneresidencial","Telefone Residencial","varchar",16,0,8);
	$telefonecomercial = criarAtributo($conn,$entidadeID,"telefonecomercial","Telefone Comercial","varchar",16,0,8);
	$telefonecelular = criarAtributo($conn,$entidadeID,"telefonecelular","Telefone Celular","varchar",16,0,8);
	$emailcomercial = criarAtributo($conn,$entidadeID,"emailcomercial","E-Mail Comercial","varchar",16,0,12);
	$emailpessoal = criarAtributo($conn,$entidadeID,"emailpessoal","E-Mail Pessoal","varchar",16,0,12);
	$cresi = criarAtributo($conn,$entidadeID,"cresi","CRESI","varchar",15,0,2);
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');