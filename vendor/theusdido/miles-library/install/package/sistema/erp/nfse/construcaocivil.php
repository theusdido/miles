<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_construcaocivil","Construção Civil");

	// Atributos
	$nfse	= $entidade->addAttr(
		array("nome" => "nfse" , "descricao" => "NFSE" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_nfse_nota'))
	);
	$codobra	= $entidade->addAttr(
		array("nome" => "codobra" , "descricao" => "codobra" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$art	= $entidade->addAttr(
		array("nome" => "art" , "descricao" => "art" , "tipo" => "varchar" , "tamanho" => 25)
	);