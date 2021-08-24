<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_indicereajuste";
	$entidadeDescricao = "Índice de Reajuste";
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
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3);
	$anomesreferencia = criarAtributo($conn,$entidadeID,"anomesreferencia","Ano Mês de Referêcia","varchar",200,0,3);
	$periodicidade = criarAtributo($conn,$entidadeID,"periodicidade","Periodicidade","Int",0,0,4,0,getEntidadeId("erp_imobiliaria_periodicidade",$conn));
	$valorindice = criarAtributo($conn,$entidadeID,"valorindice","Valor do Índice","float",0,0,13);
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','','','','Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".PREFIXO.$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome);