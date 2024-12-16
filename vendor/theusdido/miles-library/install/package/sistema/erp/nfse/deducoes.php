<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_deducoes","Deduções");

	// Atributos
	$nfse	= $entidade->addAttr(
		array("nome" => "nfse" , "descricao" => "NFSE" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_nfse_nota'))
	);
	$dedseq	= $entidade->addAttr(
		array("nome" => "dedseq" , "descricao" => "dedseq" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$dedvalper	= $entidade->addAttr(
		array("nome" => "dedvalper" , "descricao" => "dedvalper" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$dedtipo	= $entidade->addAttr(
		array("nome" => "dedtipo" , "descricao" => "dedtipo" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$dedcnpjref	= $entidade->addAttr(
		array("nome" => "dedcnpjref" , "descricao" => "dedcnpjref" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$dedcpfref	= $entidade->addAttr(
		array("nome" => "dedcpfref" , "descricao" => "dedcpfref" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$dednnfref	= $entidade->addAttr(
		array("nome" => "dednnfref" , "descricao" => "dednnfref" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$dedvltotref	= $entidade->addAttr(
		array("nome" => "dedvltotref" , "descricao" => "dedvltotref" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$dedper	= $entidade->addAttr(
		array("nome" => "dedper" , "descricao" => "dedper" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$dedvalor	= $entidade->addAttr(
		array("nome" => "dedvalor" , "descricao" => "dedvalor" , "tipo" => "varchar" , "tamanho" => 25)
	);