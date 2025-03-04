<?php
	$codigo = tdc::r("codigo"); 
	if (is_numeric_natural($codigo)){
		$produto = tdc::d("td_ecommerce_produto",tdc::f("codigo","=",$codigo))[0];
		$produto->deletar();
	}