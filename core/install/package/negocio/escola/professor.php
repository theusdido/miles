<?php
	
	// Setando variáveis
	$entidadeNome = "erp_escola_professor";
	$entidadeDescricao = "Professor";

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
	$pessoa		= criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,0,21,1,installDependencia("erp_pessoa","package/sistema"));
	$matricula 	= criarAtributo($conn,$entidadeID,"matricula","Matricula","varchar",15,1,3,1,0,0,"");

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$pessoa,true);

	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');