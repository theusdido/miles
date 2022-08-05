<<<<<<< HEAD
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
=======
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
>>>>>>> dfd2109f (#qeru - iniciando fase de teste)
	}