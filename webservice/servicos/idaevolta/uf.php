<?php

	switch($op){
		case 'all':
			$dados = array();
			foreach(tdc::d("td_uf") as $uf){
				$cidades = tdc::da("td_cidade",tdc::f("td_uf","=",$uf->id));
				array_push($dados, array(
					"estado" => array("id" => $uf->id , "nome" => $uf->nome),
					"cidades" => $cidades
				));
			}
			$retorno["dados"] = $dados;
		break;
	}