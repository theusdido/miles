<?php
	// Setando variáveis
	$entidadeNome 			= "elementocusto";
	$entidadeDescricao 		= "Elemento de Custo";
	
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
	$descricao 		= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar","200",0,3,1,0,0,"");
	$centrocusto 	= criarAtributo($conn,$entidadeID,"centrocusto","Centro de Custo","int",0,0,22,1,installDependencia("erp_contabil_centrocusto",'package/sistema/erp/contabil/centrocusto'),0,"",0,0);
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);