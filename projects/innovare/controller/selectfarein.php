<?php
	$select_processo = tdClass::Criar("select");
	$select_processo->class = "form-control";
	$select_processo->id = "busca_farein";

	$opSelect = tdClass::Criar("option");
	$opSelect->add('SELECIONE');
	$select_processo->add($opSelect);

	$filtro = tdc::o("sqlcriterio");
	$filtro->setPropriedade("order","id DESC");
	foreach(tdc::d("td_processo",$filtro) as $processo){
		$groupProcessoRecuperacao = tdc::o("optgroup");
		$groupProcessoRecuperacao->label = "[ {$processo->id} ][ {$processo->numeroprocesso} ] {$processo->descricao}";

		// Recuperanda
		if ($processo->tipoprocesso == 16){
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("processo","=",$processo->id);
			$dataset = tdClass::Criar("repositorio",array("td_recuperanda"))->carregar($sql);
			foreach ($dataset as $dado){
				$op = tdClass::Criar("option");
				$op->value = $dado->id . "^" . $dado->processo . "^RE";
				$op->add($dado->id . "-"  . $dado->razaosocial . " [ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj). " ] ");
				$groupProcessoRecuperacao->add($op);
			}
		}
		
		// Falida
		if ($processo->tipoprocesso == 19){
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("processo","=",$processo->id);
			$dataset = tdClass::Criar("repositorio",array("td_falencia"))->carregar($sql);
			foreach ($dataset as $dado){
				$op = tdClass::Criar("option");
				$op->value = $dado->id . "^" . $dado->processo . "^FA";
				$op->add($dado->id ."-". $dado->razaosocial . " [ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] ");
				$groupProcessoRecuperacao->add($op);
			}
		}

		// Insolvente
		if ($processo->tipoprocesso == 18){			
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("processo","=",$processo->id);
			$dataset = tdClass::Criar("repositorio",array("td_insolvente"))->carregar($sql);
			foreach ($dataset as $dado){
				$op = tdClass::Criar("option");
				$op->value = $dado->id . "^" . $dado->processo . "^IN";
				$op->add($dado->id . "-" . $dado->razaosocial ." [ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] ");
				$groupProcessoRecuperacao->add($op);
			}
		}

		$select_processo->add($groupProcessoRecuperacao);
	}

	$label = tdClass::Criar("label");
	$label->add("Recuperanda / Falida / Insolvente");
	$label->class = "control-label";
	
	$coluna = tdClass::Criar("div");
	$coluna->add($label,$select_processo);
	$coluna->mostrar();