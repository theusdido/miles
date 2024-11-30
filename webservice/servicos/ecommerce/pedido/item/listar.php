<?php
	$pedido = (int)$dados["pedido"];
	$itens = [];
	foreach(tdc::da("td_ecommerce_pedidoitem",tdc::f("td_pedido","=",$pedido)) as $item){
		array_push($itens,array(
			"id" 				=> completaString($item["id"],3,"0"),
			"produto" 			=> $item["descricao"],
			"qtdade" 			=> $item["qtde"],
			"valor" 			=> (is_numeric($item["valor"])?moneyToFloat((double)$item["valor"],true):"0,00"),
			"valortotal" 		=> (is_numeric($item["valortotal"])?moneyToFloat((double)$item["valortotal"],true):"0,00")
		));
	}
	$retorno["dados"] = $itens;