<?php
	$codigo = tdc::r("codigo");
	$pedido = tdc::p("td_ecommerce_pedido");
	$pedido->codigo = $codigo;
	if (is_numeric_natural($codigo)){
		$dsPedido = tdc::d("td_ecommerce_pedido",tdc::f("codigo","=",$codigo));
		if (sizeof($dsPedido) > 0){
			$pedido = $dsPedido[0];
			$pedido->isUpdate();			
		}
	}	
	
	$itens = array();
	foreach($dados as $d){
		foreach($d as $k => $v){
			if ($k == 'itens'){
				$itens = $v;
			}else{
				$pedido->{$k} = $v;
			}
		}
	}
	
	if ($pedido->armazenar()){
		foreach($itens as $i){
			$item = tdc::p("td_ecommerce_pedidoitem");
			$item->td_pedido = $pedido->id;
			foreach($i as $k => $v){
				$item->{$k} = $v;
			}
			$item->armazenar();
		}
	}