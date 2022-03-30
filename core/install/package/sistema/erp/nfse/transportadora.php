<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_transportadora","Transportadora");

	// Atributos
	$nfse	= $entidade->addAttr(
		array("nome" => "nfse" , "descricao" => "NFSE" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_nfse_nota'))
	);
	$pessoa	= $entidade->addAttr(
		array("nome" => "pessoa" , "descricao" => "Pessoa" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('erp_geral_pessoa','package/sistema/erp/pessoa/pessoa'))
	);
	$tranome	= $entidade->addAttr(
		array("nome" => "tranome" , "descricao" => "tranome" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tracpfcnpj	= $entidade->addAttr(
		array("nome" => "tracpfcnpj" , "descricao" => "tracpfcnpj" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$traie	= $entidade->addAttr(
		array("nome" => "traie" , "descricao" => "traie" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$traplaca	= $entidade->addAttr(
		array("nome" => "traplaca" , "descricao" => "traplaca" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$traend	= $entidade->addAttr(
		array("nome" => "traend" , "descricao" => "traend" , "tipo" => "varchar" , "tamanho" => 200)
	);				
	$tramun	= $entidade->addAttr(
		array("nome" => "tramun" , "descricao" => "tramun" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$trauf	= $entidade->addAttr(
		array("nome" => "trauf" , "descricao" => "trauf" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$trapais	= $entidade->addAttr(
		array("nome" => "trapais" , "descricao" => "trapais" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tratipofrete	= $entidade->addAttr(
		array("nome" => "tratipofrete" , "descricao" => "tratipofrete" , "tipo" => "varchar" , "tamanho" => 200)
	);