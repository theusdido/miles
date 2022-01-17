<?php
	$codigo 	= tdc::r("codigo"); 
	$produto 	= tdc::p("td_ecommerce_produto");
	if (is_numeric_natural($codigo)){
		$dsProduto = tdc::d("td_ecommerce_produto",tdc::f("codigo","=",$codigo));
		if (sizeof($dsProduto) > 0){
			$produto = $dsProduto[0];
			$produto->isUpdate();
			$produto->codigo = $codigo;
		}
	}

	foreach($dados as $d){
		foreach($d as $k => $v){
			$produto->{$k} = $v;
		}
	}
	$produto->armazenar();