<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_pedido";
	$entidadeDescricao 	= "Pedido";
	
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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);
	
	// Criando Atributos
	$pedido_cliente 			= criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,0,22,1,installDependencia("ecommerce_cliente","package/website/ecommerce/geral/cliente"),0,"");
	$pedido_datahoraenvio 		= criarAtributo($conn,$entidadeID,"datahoraenvio","Data/Hora de Envio","datetime",0,0,23,1,0,0,"");
	$pedido_datahoraretorno 	= criarAtributo($conn,$entidadeID,"datahoraretorno","Data/Hora de Retorno","datetime",0,1,23,0,0,0,"");
	$pedido_carrinho 			= criarAtributo($conn,$entidadeID,"carrinhocompras","Carrinho de Compra","int",0,1,22,0,installDependencia("ecommerce_carrinhocompras","package/website/ecommerce/geral/carrinhocompras"),0,"");
	$pedido_status 				= criarAtributo($conn,$entidadeID,"status","Status","tinyint",0,0,4,1,installDependencia("ecommerce_statuspedido","package/website/ecommerce/comercial/statuspedido"),0,"");
	$pedido_metodopagamento 	= criarAtributo($conn,$entidadeID,"metodopagamento","Método de Pagamento","int",0,0,4,0,installDependencia("ecommerce_metodopagamento","package/website/ecommerce/comercial/metodopagamento"),0,"");
	$pedido_qtdetotalitens 		= criarAtributo($conn,$entidadeID,"qtdetotalitens","Qtde Total de Itens","int",0,1,3,0,0,0,"");
	$pedido_valortotal 			= criarAtributo($conn,$entidadeID,"valortotal","Valor Total","float",0,1,13,1,0,0,"");
	$pedido_representante 		= criarAtributo($conn,$entidadeID,"representante","Representante","int",0,1,22,0,installDependencia("ecommerce_representante","package/website/ecommerce/representante/representante"),0,"",0,0);
	$pedido_isrepresentante 	= criarAtributo($conn,$entidadeID,"isrepresentante","Representante ?","tinyint",0,1,7,0,0,0,'',1,0);
    $pedido_valortfrete 		= criarAtributo($conn,$entidadeID,"valorfrete","Valor Frete","float",0,1,13,1,0,0,"");
	$pedido_isfinalizado		= criarAtributo($conn,$entidadeID,"isfinalizado","Finalizado ?","tinyint",0,1,7,0,0,0,'',1,0);
	$pedido_modalidade 			= criarAtributo($conn,$entidadeID,"modalidade","Modalidade","int",0,1,4,0,installDependencia("ecommerce_modalidade","package/website/ecommerce/geral/modalidade"),0,"",0,0);

	criarAba($conn,$entidadeID,"Capa", implode(",",array(
		$pedido_cliente,
		$pedido_datahoraenvio,
		$pedido_datahoraretorno,
		$pedido_carrinho,
		$pedido_status,
		$pedido_metodopagamento,
		$pedido_modalidade,
		$pedido_qtdetotalitens,
		$pedido_valortotal,
		$pedido_valortfrete,
		$pedido_isfinalizado
	)));

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,7,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	$itenspedido_pedido 		= criarAtributo($conn,$itenspedidoID,"pedido","Pedido","int",0,1,16,0,$entidadeID,0,"");
	$itenspedido_carrinhoitem	= criarAtributo($conn,$itenspedidoID,"carrinhoitem","Item do Carrinho","int",0,1,16,0,getEntidadeId("ecommerce_carrinhoitem",$conn),0,"");
	$itenspedido_produto 		= criarAtributo($conn,$itenspedidoID,"produto","Produto","int",0,0,22,1,getEntidadeId("ecommerce_produto",$conn),0,"");
	$itenspedido_descricao 		= criarAtributo($conn,$itenspedidoID,"descricao","Descrição","varchar",200,1,16,0,0,0,"");
	$itenspedido_qtde 			= criarAtributo($conn,$itenspedidoID,"qtde","Quantidade","tinyint",0,0,26,1,0,0,"");
	$itenspedido_valor 			= criarAtributo($conn,$itenspedidoID,"valor","Valor","float",0,0,13,1,0,0,"");
	$itenspedido_valortotal 	= criarAtributo($conn,$itenspedidoID,"valortotal","Valor Total","float",0,1,13,1,0,0,"",1,0,'',false);
	$itenspedido_qtdetotal		= criarAtributo($conn,$itenspedidoID,"qtdetotaldeitens","Quantidade Total","tinyint",0,1,16,0,0,0,"",1,0,'',false);
	$itenspedido_produtonome	= criarAtributo($conn,$itenspedidoID,"produtonome","Produto","varchar",200,1,16,0,0,0,"",1,0,'',false);
	$itenspedido_referencia		= criarAtributo($conn,$itenspedidoID,"referencia","Referência","varchar",200,1,16,0,0,0,"",1,0,'',false);
	$itenspedido_tamanho_desc 	= criarAtributo($conn,$itenspedidoID,"tamanho_desc","Tamanho","varchar",200,1,16,0,0,0,"",1,0,'',false);
	$itenspedido_produtotamanho	= criarAtributo($conn,$itenspedidoID,"tamanho","Tamanho","int",0,1,4,0,getEntidadeId("ecommerce_produtotamanho",$conn),0,"");		
	$itenspedido_cor_desc 		= criarAtributo($conn,$itenspedidoID,"cor_desc","Cor","varchar",200,1,16,0,0,0,"",0,0,'',false);
	$itenspedido_produtocor		= criarAtributo($conn,$itenspedidoID,"cor","Cor","int",0,1,4,0,getEntidadeId("ecommerce_produtocor",$conn),0,"");
	$itenspedido_variacao 		= criarAtributo($conn,$itenspedidoID,"variacao","Variação","varchar",200,1,16,0,0,0,"",1,0,'',false);

	// Cria Relacionamento
	criarRelacionamento($conn,2,$entidadeID,$itenspedidoID,"Itens",$itenspedido_pedido);