<?php

	// Setando variáveis
	$entidadeNome = "erp_associacao_planomensalidade";
	$entidadeDescricao = "Plano de Mensalidade";

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
	$nome 			= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3,1,0,0,"");
	$valor 			= criarAtributo($conn,$entidadeID,"valor","Valor","float",0,1,13,1);
	$diapagamento 	= criarAtributo($conn,$entidadeID,"diapagamento","Dia do Pagamento","tinyint",0,1,25,1);

	// Criando Acesso
	$menu = addMenu($conn,'Associação','#','',0,0,'associacao');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'associacao-' . $entidadeNome,$entidadeID,'cadastro');
	addMenu($conn,"Mensalidade","index.php?controller=erp/associacao/mensalidade",'',$menu,2,'associacao-mensalidade',0,'personalizado');

	Entity::setDescriptionField($conn,$entidadeID,$nome,true);