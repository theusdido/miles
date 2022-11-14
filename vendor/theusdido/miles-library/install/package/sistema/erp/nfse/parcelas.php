<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_parcelas","Parcelas");

	// Atributos
	$nfse	= $entidade->addAttr(
		array("nome" => "nfse" , "descricao" => "NFSE" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_nfse_nota'))
	);
	$prcsequencial	    = $entidade->addAttr(
		array("nome" => "prcsequencial" , "descricao" => "prcsequencial" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$prcvalor	        = $entidade->addAttr(
		array("nome" => "prcvalor" , "descricao" => "prcvalor" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$prcdtavencimento	= $entidade->addAttr(
		array("nome" => "prcdtavencimento" , "descricao" => "prcdtavencimento" , "tipo" => "varchar" , "tamanho" => 25)
	);    