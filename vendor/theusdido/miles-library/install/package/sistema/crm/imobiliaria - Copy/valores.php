<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_valores";
	$entidadeDescricao = "Valores";

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
	$venda = criarAtributo($conn,$entidadeID,"venda","Preço de Venda","float",0,0,13);
	$locacao = criarAtributo($conn,$entidadeID,"locacao","Preço da Locação","float",0,0,13);
	$iptu = criarAtributo($conn,$entidadeID,"iptu","Preço do IPTU","float",0,0,13);
	$condominio = criarAtributo($conn,$entidadeID,"condominio","Preço do Condomínio","float",0,0,13); 

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');