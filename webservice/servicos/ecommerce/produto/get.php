<?php
	$codigo = tdc::r("codigo"); 
	if (is_numeric_natural($codigo)){
		try{
			$produto = tdc::da("td_ecommerce_produto",tdc::f("codigo","=",$codigo));
			if (sizeof($produto) > 0){
				$retorno["dados"] = $produto[0];
			}else{
				$retorno["dados"] = [];
			}
		}catch(Throwable $t){
			
		}
	}