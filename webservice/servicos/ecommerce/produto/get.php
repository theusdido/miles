<?php
	$id			= tdc::r('id');
	$codigo 	= tdc::r("codigo");
	$sku		= tdc::r('sku');
	$criterio 	= tdc::f();

	if ($id != '') $criterio->addFiltro('id','=',$id);
	if ($codigo != '') $criterio->addFiltro('codigo','=',$codigo);
	if ($sku != '') $criterio->addFiltro('sku','=',$sku);

	try{
		$retorno["dados"] 	= tdc::da("td_ecommerce_produto",$criterio);
	}catch(Throwable $t){
		$retorno['dados']	= [];
	}