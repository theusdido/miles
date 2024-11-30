<?php
	$codigo = tdc::r("codigo"); 
	if (is_numeric_natural($codigo)){
		$pedido = tdc::da("td_ecommerce_pedido",tdc::f("codigo","=",$codigo))[0];
		$retorno["dados"] = $pedido;
		$retorno["dados"]["itens"] = tdc::da("td_ecommerce_pedidoitem",tdc::f("td_pedido","=",$pedido["id"]));
	}