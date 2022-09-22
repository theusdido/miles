<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_negociacaointeracao";
	$entidadeDescricao = "Negociação Interação";

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
	$negociacao = criarAtributo($conn,$entidadeID,"negociacao","Negociação","int",0,0,22,0,getEntidadeId("crm_imobiliaria_negociacao",$conn));
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3);
	$datahora = criarAtributo($conn,$entidadeID,"datahora","Data Hora","date",0,0,23);
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');