<?php
	// Setando variáveis
	$entidadeNome 		= "erp_financeiro_operacaobancaria";
	$entidadeDescricao 	= "Operação Bancária";

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

	addAutoIncrement($entidadeID);

	// Criando Atributos
	$descricao 		= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",50,0,3,1);
	$codigo 		= criarAtributo($conn,$entidadeID,"codigo","Código","int",0,1,25);
	$banco			= criarAtributo($conn,$entidadeID,"banco","Banco","int",0,0,4,0,installDependencia("erp_financeiro_banco","package/sistema/erp/financeiro/banco"));
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Financeiro','#','',0,0,'financeiro-banco');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'','financeiro-banco',8,'financeiro-' . $entidadeNome,$entidadeID, 'cadastro');
