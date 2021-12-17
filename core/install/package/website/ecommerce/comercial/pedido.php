<?php

	// Status do Pedido
	$statuspedidoID = criarEntidade(
		$conn,
		"ecommerce_statuspedido",
		"Status do Pedido",
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
	$descricao = criarAtributo($conn,$statuspedidoID,"descricao","Descrição","varchar",50,0,3,1,0,0,"");
	$significado = criarAtributo($conn,$statuspedidoID,"significado","Significado","varchar",1000,0,3,1,0,0,"");

	// Setando variáveis
	$entidadeNome = "ecommerce_pedido";
	$entidadeDescricao = "Pedido";
	
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
	$pedido_cliente 			= criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,0,22,1,getEntidadeId("ecommerce_cliente",$conn),0,"");
	$pedido_datahoraenvio 		= criarAtributo($conn,$entidadeID,"datahoraenvio","Data/Hora de Envio","datetime",0,0,23,1,0,0,"");
	$pedido_datahoraretorno 	= criarAtributo($conn,$entidadeID,"datahoraretorno","Data/Hora de Retorno","datetime",0,0,23,0,0,0,"");
	$pedido_carrinho 			= criarAtributo($conn,$entidadeID,"carrinhocompras","Carrinho de Compra","int",0,0,22,0,getEntidadeId("ecommerce_carrinhocompras",$conn),0,"");
	$pedido_status 				= criarAtributo($conn,$entidadeID,"status","Status","tinyint",0,0,4,1,getEntidadeId("ecommerce_statuspedido",$conn),0,"");
	$pedido_metodopagamento 	= criarAtributo($conn,$entidadeID,"metodopagamento","Método de Pagamento","int",0,1,4,0,getEntidadeId("ecommerce_metodopagamento",$conn),0,"");
	$pedido_qtdetotaldeitens 	= criarAtributo($conn,$entidadeID,"qtdetotaldeitens","Qtde Total de Itens","int",0,1,3,0,0,0,"");
	$pedido_valortotal 			= criarAtributo($conn,$entidadeID,"valortotal","Valor Total","float",0,1,13,1,0,0,"");
	$pedido_representante 		= criarAtributo($conn,$entidadeID,"representante","Representante","int",0,1,22,0,getEntidadeId("ecommerce_representante",$conn),0,"",0,0);
	$pedido_isrepresentante 	= criarAtributo($conn,$entidadeID,"isrepresentante","Representante ?","tinyint",0,1,7,0,0,0,'',1,0);
    $pedido_valortfrete 		= criarAtributo($conn,$entidadeID,"valorfrete","Valor Frete","float",0,1,13,1,0,0,"");
	$pedido_isfinalizado		= criarAtributo($conn,$entidadeID,"isfinalizado","Finalizado ?","tinyint",0,1,7,0,0,0,'',1,0);

	criarAba($conn,$entidadeID,"Capa", implode(",",array(
		$pedido_cliente,$pedido_datahoraenvio,$pedido_datahoraretorno,$pedido_carrinho,$pedido_status,$pedido_metodopagamento,$pedido_qtdetotaldeitens,
		$pedido_valortotal,$pedido_representante,$pedido_representante
	)));

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');
	
	// Itens do Carrinho
	$itenspedidoID = criarEntidade(
		$conn,
		"ecommerce_pedidoitem",
		"Itens do Pedido",
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
	$itenspedido_pedido 	= criarAtributo($conn,$itenspedidoID,"pedido","Pedido","int",0,0,16,0,$entidadeID,0,"");
	$itenspedido_produto 	= criarAtributo($conn,$itenspedidoID,"produto","Produto","int",0,0,22,1,getEntidadeId("ecommerce_produto",$conn),0,"");
	$itenspedido_qtde 		= criarAtributo($conn,$itenspedidoID,"qtde","Quantidade","tinyint",0,0,26,1,0,0,"");
	$itenspedido_descricao 	= criarAtributo($conn,$itenspedidoID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$itenspedido_valor 		= criarAtributo($conn,$itenspedidoID,"valor","Valor","float",0,0,13,1,0,0,"");	

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,7,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');

	// Cria Relacionamento
	criarRelacionamento($conn,2,$entidadeID,$itenspedidoID,"Itens",$itenspedido_pedido);
	
	// Métodos de Pagamentos
	$metodopagamentoID = criarEntidade(
		$conn,
		"ecommerce_metodopagamento",
		"Método de Pagamento",
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
	$descricao = criarAtributo($conn,$metodopagamentoID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");

	// Adicionando Menu Método Pagamento
	addMenu($conn,"Método de Pagamento","files/cadastro/".$metodopagamentoID."/".PREFIXO."metodopagamento.html",'',$menu_webiste,7,'ecommerce-metodopagamento',$metodopagamentoID,'cadastro');

	// Adicionando Menu Status Pedido
	addMenu($conn,"Status do Pedido","files/cadastro/".$statuspedidoID."/".PREFIXO."statuspedido.html",'',$menu_webiste,8,'ecommerce-statuspedido',$statuspedidoID,'cadsatro');