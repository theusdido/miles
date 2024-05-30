<?php

	foreach($_POST as $k => $v){
		Debug::Log($k . '=>' . $v);
	}

	$sku			= tdc::r('sku');
	$criterio 		= tdc::f();
	$dados			= json_decode(tdc::r('dados'));
	$produto 		= tdc::p("td_ecommerce_produto");
	$unidademedida	= tdc::p("td_ecommerce_unidademedida");
	$marca 			= tdc::r('marca');

	//var_dump($dados);
	//if (is_array($dados) && sizeof($dados)){
		if (is_numeric_natural($sku))
		{
			$dsProduto = tdc::d("td_ecommerce_produto",tdc::f("sku","=",$sku));
			if (sizeof($dsProduto) > 0){
				$produto 		= $dsProduto[0];
				$produto->sku 	= $sku;
				$produto->isUpdate();
			}
		}

		$registros = [];
		if ($dados == '' && count($_POST) > 0)
		{
			$registros = $_POST;
		}else{
			foreach($dados as $d){
				foreach($d as $k => $v)
					$registros[$k] = $v;
			}
		}

		foreach($registros as $k => $v){
			$produto->{$k} = $v;
		}

		if ($produto->armazenar()){
			$retorno['status'] 	= 1;
			$retorno['msg']		= 'Salvo com Sucesso';
		}else{
			$retorno['status'] 	= 0;
			$retorno['msg']		= 'Não foi possível salvar o registro';
		}
	// }else{
	// 	$retorno['status'] 	= 0;
	// 	$retorno['msg']		= 'Nenhum produto enviado';		
	// }