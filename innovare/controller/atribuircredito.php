<?php
	/*
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_relacaocredores"))->carregar($sql);
	foreach($dataset as $credor){
		$credito = tdClass::Criar("persistent",array("td_credito"));
		$credito->contexto->valor = $credor->valor;
		$credito->contexto->td_moeda = $credor->td_moeda;
		$credito->contexto->td_classificacao = $credor->td_classificacao;
		$credito->contexto->td_natureza = $credor->td_natureza;
		$credito->contexto->td_credor = $credor->id;
		$credito->contexto->armazenar();
	}
	Transacao::fechar();
	echo 'Fim';
	*/
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("td_credor","=",3727);
	$dataset = tdClass::Criar("repositorio",array("td_habilitacaodivergencia2"))->carregar($sql);
	foreach($dataset as $habdiv2){
		$sql_credor = tdClass::Criar("sqlcriterio");
		$sql_credor->addFiltro("td_credor","=",$habdiv2->td_credor);
		$ds_credor = tdClass::Criar("repositorio",array("td_credito"))->carregar($sql_credor);
		if ($ds_credor){
			$parecer = tdClass::Criar("persistent",array("td_parecer"));
			$parecer->contexto->valor = $habdiv2->parecervalor;
			$parecer->contexto->td_moeda = $habdiv2->td_parecermoeda;
			$parecer->contexto->td_classificacao = $habdiv2->td_parecerclassificacao;
			$parecer->contexto->td_credito = $ds_credor[0]->id;
			$parecer->contexto->td_habilitacaodivergencia = $habdiv2->id;
			$parecer->contexto->empresa = 1;
			$parecer->contexto->projeto = 1;
			$parecer->contexto->armazenar();			
		}
	}
	Transacao::fechar();
	echo 'Fim';	