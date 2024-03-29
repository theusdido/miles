<?php
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("credor","=",3727);
	$dataset = tdClass::Criar("repositorio",array("td_habilitacaodivergencia2"))->carregar($sql);
	foreach($dataset as $habdiv2){
		$sql_credor = tdClass::Criar("sqlcriterio");
		$sql_credor->addFiltro("credor","=",$habdiv2->credor);
		$ds_credor = tdClass::Criar("repositorio",array("td_credito"))->carregar($sql_credor);
		if ($ds_credor){
			$parecer = tdClass::Criar("persistent",array("td_parecer"));
			$parecer->contexto->valor = $habdiv2->parecervalor;
			$parecer->contexto->moeda = $habdiv2->parecermoeda;
			$parecer->contexto->classificacao = $habdiv2->parecerclassificacao;
			$parecer->contexto->credito = $ds_credor[0]->id;
			$parecer->contexto->habilitacaodivergencia = $habdiv2->id;
			$parecer->contexto->empresa = 1;
			$parecer->contexto->projeto = 1;
			$parecer->contexto->armazenar();			
		}
	}
	Transacao::fechar();
	echo 'Fim';	