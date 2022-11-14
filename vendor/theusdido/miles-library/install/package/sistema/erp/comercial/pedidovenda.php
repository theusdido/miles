<?php

	// Setando variáveis
	$entidadeNome 			= "erp_comercial_pedidovenda";
	$entidadeDescricao 		= "Pedido de Venda";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	
	// Criando Atributos
	$pedidocliente 			= criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,0,22,1,installDependencia("erp_pessoa",'package/sistema/erp/pessoa/pessoa'),0,"");
	$pedidodatahora 		= criarAtributo($conn,$entidadeID,"datahora","Data/Hora","datetime",0,0,23,1,0,0,"");	
	$pedidostatus 			= criarAtributo($conn,$entidadeID,"status","Status","tinyint",0,0,4,1,installDependencia("erp_comercial_statuspedidovenda",'package/sistema/erp/comercial/statuspedidovenda'),0,"");
	$pedidoformapagamento 	= criarAtributo($conn,$entidadeID,"formapagamento","Forma de Pagamento","int",0,1,30,1,installDependencia("erp_financeiro_formapagamento",'package/sistema/erp/financeiro/formapagamento'),0,"");

	// Criando Acesso
	$menu_comercial = addMenu($conn,'Comercial','#','',0,0,'comercial');
	
	// Itens do Carrinho
	$itenspedidoID = criarEntidade(
		$conn,
		"erp_comercial_itenspedidovenda",
		"Itens do Pedido de Venda",
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	$itenspedidopedido 		= criarAtributo($conn,$itenspedidoID,"pedido","Pedido","int",0,0,16,0,$entidadeID,0,"");
	$itenspedidoproduto 	= criarAtributo($conn,$itenspedidoID,"produto","Produto","int",0,0,22,1,installDependencia("erp_produto",'package/sistema/erp/produto/produto'),0,"");
	$itenspedidoqtde 		= criarAtributo($conn,$itenspedidoID,"qtde","Quantidade","tinyint",0,0,26,1,0,0,"");
	$itenspedidodescricao 	= criarAtributo($conn,$itenspedidoID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$itenspedidovalor 		= criarAtributo($conn,$itenspedidoID,"valor","Valor","float",0,0,13,1,0,0,"");

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_comercial,7,'comercial-' . $entidadeNome,$entidadeID, 'cadastro');

	// Cria Relacionamento
	criarRelacionamento($conn,2,$entidadeID,$itenspedidoID,"Itens",$itenspedidopedido);