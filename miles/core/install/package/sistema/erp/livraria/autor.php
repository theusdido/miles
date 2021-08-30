<?php
	// Setando variáveis
	$entidadeNome = "erp_livraria_autor";
	$entidadeDescricao = "Autor";

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
	$nome	 			= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3,1);	

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Livraria','#','','','','livraria');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".PREFIXO.$entidadeNome.".html",'',$menu_webiste,1,'livraria-' . $entidadeNome,$entidadeID, 'cadastro');