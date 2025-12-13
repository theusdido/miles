<?php

	$conn 					= Transacao::Get();

	$processo 				= tdClass::Criar("persistent",array("td_processo",tdClass::Read("processo")))->contexto;
	$tipoprocessoorigem 	= tdClass::Read("tipoprocessoorigem") == '' ? $processo->tipoprocesso : tdc::r('tipoprocessoorigem');
	$tipoprocessodestino 	= tdClass::Read("tipoprocessodestino");

	if ($tipoprocessoorigem == 16 and $tipoprocessodestino == 19){ // Recuperação para Falencia
		$sql = tdClass::Criar("sqlcriterio");
		$sql->addFiltro("processo","=",$processo->id);
		$recuperandas = tdClass::Criar("repositorio",array("td_recuperanda"))->carregar($sql);		
		foreach($recuperandas as $r){
			
			$falida 				= tdClass::Criar("persistent",array("td_falencia"))->contexto;
			$idfalida 				= $falida->proximoID();
			$falida->id 			= $idfalida;
			$falida->processo 		= $processo->id;
			$falida->cnpj 			= $r->cnpj;
			$falida->razaosocial 	= tdc::utf8($r->razaosocial);
			$falida->datasentenca 	= $r->datapedido;
			$falida->logradouro 	= tdc::utf8($r->logradouro);
			$falida->numero 		= $r->numero;
			$falida->complemento 	= tdc::utf8($r->complemento);
			$falida->bairro 		= tdc::utf8($r->bairro);
			$falida->cidade 		= $r->cidade;
			$falida->estado 		= $r->estado;
			$falida->cep 			= $r->cep;
			$falida->armazenar();

			$sqllista = "UPDATE td_lista SET regpai = {$idfalida} WHERE entidadepai = 16 AND entidadefilho = 20 AND regpai = {$r->id};";
			$conn->exec($sqllista);

			tdClass::Criar("persistent",array("td_recuperanda",$r->id))->contexto->deletar();

			$sqlfarein = "UPDATE td_relacaocredores SET farein = {$idfalida} WHERE farein = {$r->id};";
			$conn->exec($sqlfarein);
		}

		// Atualiza o tipo de processo
		$processo->tipoprocesso = $tipoprocessodestino;
		$processo->armazenar();		
		echo 1;		
	}else{
		echo 'Tipo de processo não encontrado.'; 
		exit;
	}
	Transacao::Commit();