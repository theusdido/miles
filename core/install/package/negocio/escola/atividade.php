<?php
	
	// Setando variáveis
	$entidadeNome = "erp_escola_atividade";
	$entidadeDescricao = "Atividade";

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
	$descricao			= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",500,0,3,1,0,0,"");
    $instrucao			= criarAtributo($conn,$entidadeID,"instrucao","Instrução","text",0,1,21);
	$unidadecurricular 	= criarAtributo($conn,$entidadeID,"unidadecurricular","Unidade Curricular","int",0,0,16,1,installDependencia("erp_escola_unidadecurricular","package/sistema"));

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);
	
	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');

	// Relacionamento
	criarRelacionamento($conn,6,getEntidadeId("erp_escola_unidadecurricular"),$entidadeID,"Atividade",$unidadecurricular);