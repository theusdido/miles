<?php
	$id			= tdc::r('id');
	$codigo 	= tdc::r("codigo");
	$sku		= tdc::r('sku');
	$criterio 	= tdc::f();

	if ($id != '') $criterio->addFiltro('id','=',$id);
	if ($codigo != '') $criterio->addFiltro('codigo','=',$codigo);
	if ($sku != '') $criterio->addFiltro('sku','=',$sku);
	
	$product = tdc::da("td_ecommerce_produto",$criterio);

	try{
		$retorno["data"] 	= $product[0];
	}catch(Throwable $t){
		$retorno['data']	= [];
	}