<?php
	set_time_limit(3600);
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("td_processo","=",1);
	$dataset = tdClass::Criar("repositorio",array("td_relacaocredores"))->carregar($sql);
	foreach ($dataset as $credor){
		$sql_d = tdClass::Criar("sqlcriterio");
		$sql_d->addFiltro("codigo","=",$credor->codigo);
		$sql_d->addFiltro("td_processo","=",1);		
		$dataset_d = tdClass::Criar("repositorio",array("td_relacaocredores"))->carregar($sql_d);
		if (sizeof($dataset_d) > 1){
			foreach ($dataset_d as $c){
				$processo = tdClass::Criar("persistent",array("td_processo",$c->td_processo));	
				echo "[ {$processo->contexto->numeroprocesso} ] - [ " . $c->codigo . " ] - [ " . utf8_encode($c->nome) . " ] - [ " . moneyToFloat($c->valor,true) . " ] <br />";
			}			
		}
	}
?>