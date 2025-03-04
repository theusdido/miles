<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_manutencao";
	$entidadeDescricao = "Manutenção";

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
	$cliente = criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,0,4,0,getEntidadeId("crm_imobiliaria_cliente",$conn));
	$imovel = criarAtributo($conn,$entidadeID,"imovel","Imóvel","int",0,0,4,0,getEntidadeId("crm_imobiliaria_imovel",$conn));
	$tipomanutencao = criarAtributo($conn,$entidadeID,"tipomanutencao","Tipo de Manutenção","int",0,0,4,0,getEntidadeId("crm_imobiliaria_tipomanutencao",$conn));
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","text",0,0,21);
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');