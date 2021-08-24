<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_movimentacaomensaltipoevento";
	$entidadeDescricao = "Tipo de Evento";
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
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,1,3,1);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','','','','Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".PREFIXO.$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');