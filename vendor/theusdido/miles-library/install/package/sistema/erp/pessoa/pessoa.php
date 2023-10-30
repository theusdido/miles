<?php
	// Setando variáveis
	$entidadeNome 		= "erp_geral_pessoa";
	$entidadeDescricao 	= "Pessoa";

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
	$nome 		= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3,1,0,0,"");	
	$tipopessoa = criarAtributo($conn,$entidadeID,"tipopessoa","Tipo","int",0,1,16,0,0,0,'',1,0);

	Entity::setDescriptionField($conn,$entidadeID,$nome,true);

	// Seta atributo generalização
	setAtributoGeneralizacao($conn,$entidadeID,$tipopessoa);

	// Dependencias
	$_dp_telefone 		= installDependencia("erp_geral_telefone",'geral/contato/telefone');
	$_dp_email			= installDependencia("erp_geral_email",'geral/contato/email');

	// Criando Acesso
	$menu_webiste 	= addMenu($conn,'Geral','#','',0,0,'geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'geral-' . $entidadeNome,$entidadeID,'cadastro');

	$aba = criarAba($conn,$entidadeID,"Capa",array($nome,$tipopessoa));

	// Criando Relacionamento
	criarRelacionamento($conn,10,$entidadeID,$_dp_telefone,"Telefone",0);
	criarRelacionamento($conn,10,$entidadeID,$_dp_email,"E-Mail",0);	