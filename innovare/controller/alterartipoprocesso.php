<?php

	$conn = Transacao::Get();

	$processo = tdClass::Criar("persistent",array("td_processo",tdClass::Read("processo")))->contexto;
	$tipoprocessoorigem = tdClass::Read("tipoprocessoorigem");
	$tipoprocessodestino = tdClass::Read("tipoprocessodestino");

	// Atualiza o tipo de processo
	$processo->tipoprocesso = $tipoprocessodestino;
	$processo->armazenar();
		
	if ($tipoprocessoorigem == 16 and $tipoprocessodestino == 19){ // Recuperação para Falencia	
		$sql = tdClass::Criar("sqlcriterio");
		$sql->addFiltro("td_processo","=",$processo->id);
		$recuperandas = tdClass::Criar("repositorio",array("td_recuperanda"))->carregar($sql);		
		foreach($recuperandas as $r){
			
			$falida = tdClass::Criar("persistent",array("td_falencia"))->contexto;
			$idfalida = $falida->proximoID();
			$falida->id = $idfalida;
			$falida->td_processo = $processo->id;
			$falida->cnpj = $r->cnpj;
			$falida->razaosocial = utf8_encode($r->razaosocial);
			$falida->datasentenca = $r->datapedido;
			$falida->logradouro = utf8_encode($r->logradouro);
			$falida->numero = $r->numero;
			$falida->complemento = utf8_encode($r->complemento);
			$falida->bairro = utf8_encode($r->bairro);
			$falida->td_cidade = $r->cidade;
			$falida->td_estado = $r->estado;
			$falida->cep = $r->cep;
			$falida->armazenar();

			$sqllista = "UPDATE td_lista SET reg_pai = {$idfalida} WHERE entidade_pai = 16 AND entidade_filho = 20 AND reg_pai = {$r->id};";
			$conn->exec($sqllista);

			tdClass::Criar("persistent",array("td_recuperanda",$r->id))->contexto->deletar();

			$sqlfarein = "UPDATE td_relacaocredores SET farein = {$idfalida} WHERE farein = {$r->id};";
			$conn->exec($sqlfarein);
		}
	}else{
		echo 'Tipo de processo não encontrado.'; 
		exit;
	}
	Transacao::Fechar();