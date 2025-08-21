<?php

	$pedidoBlocoId = "pedido-home";
	$bloco_pedido = tdClass::Criar("bloco");
	$bloco_pedido->class = "col-md-12";
	$bloco_pedido->id = $pedidoBlocoId;

	$panel = tdClass::Criar("panel");
	$panel->head("Pedidos");
	$panel->tipo = "success";

	$divPedidoAberto = tdClass::Criar("div");
	$divPedidoAberto->id = "pedido-aberto-home";

	$divCarrinhoCompras = tdc::o("div");
	$divCarrinhoCompras->id = "carrinho-compras-home";

	# Abas
	$aba 				= tdClass::Criar("aba", ["tabs-ecommerce-pedidoshome"]);
	$aba->contexto 		= $pedidoBlocoId;
	$aba->addItem("Aberto",$divPedidoAberto,"","pd-aberto");
	#$aba->addItem("Finalizado","Pedidos Finalizados","","pd-finalizado");
	$aba->addItem("Carrinho",$divCarrinhoCompras,"","pd-carrinho");
	$panel->body($aba);

	$objGradeDados 			= tdClass::Criar("script");	
	$objGradeDados->src 	= URL_ECOMMERCE . "pedidohome/pedidohome.js";

	$bloco_pedido->add($panel,$objGradeDados);
	$bloco_pedido->mostrar();