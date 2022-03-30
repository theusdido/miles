<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_nota","Nota Fiscal de Serviço Eletrônica");

	// Atributos
	$rpsnumero	= $entidade->addAttr(
		array("nome" => "rpsnumero" , "descricao" => "RPS Número" , "tipohtml" => "numero_inteiro")
	);
	$rpsserie	= $entidade->addAttr(
		array("nome" => "rpsserie" , "descricao" => "RPS Série" , "tipo" => "char" , "tamanho" => 1 , "tipohtml" => 3)
	);
	$rpstipo	= $entidade->addAttr(
		array("nome" => "rpstipo" , "descricao" => "RPS Tipo" , "tipohtml" => "numero_inteiro")
	);
	$demis	= $entidade->addAttr(
		array("nome" => "demis" , "descricao" => "Data de Emissão" , "tipohtml" => "data")
	);
	$dcompetencia	= $entidade->addAttr(
		array("nome" => "dcompetencia" , "descricao" => "Data de Competência" , "tipohtml" => "data")
	);
	$natop	= $entidade->addAttr(
		array("nome" => "natop" , "descricao" => "Natureza da Operação" , "tipohtml" => "numero_inteiro")
	);
	$operacao	= $entidade->addAttr(
		array("nome" => "operacao" , "descricao" => "Operação" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$numprocesso	= $entidade->addAttr(
		array("nome" => "numprocesso" , "descricao" => "Número Processo" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$regesptrib	= $entidade->addAttr(
		array("nome" => "regesptrib" , "descricao" => "Registro de Expedição Tributária" , "tipohtml" => "numero_inteiro")
	);
	$optsn	= $entidade->addAttr(
		array("nome" => "optsn" , "descricao" => "Situação Tributária" , "tipohtml" => "numero_inteiro")
	);
	$inccult	= $entidade->addAttr(
		array("nome" => "inccult" , "descricao" => "inccult" , "tipohtml" => "numero_inteiro")
	);
	$status	= $entidade->addAttr(
		array("nome" => "status" , "descricao" => "Status" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$nfsoutrasinformacoes	= $entidade->addAttr(
		array("nome" => "nfsoutrasinformacoes" , "descricao" => "Outras Informações" , "tipo" => "text" , "tipohtml" => 3)
	);
	$situacao	= $entidade->addAttr(
		array("nome" => "situacao" , "descricao" => "Situação" , "tipo" => "char", "tamanho" => 1, "tipohtml" => 3)
	);