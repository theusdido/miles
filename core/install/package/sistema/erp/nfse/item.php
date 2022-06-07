<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_item","Item");

	// Atributos
	$nfse	= $entidade->addAttr(
		array("nome" => "nfse" , "descricao" => "NFSE" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_contabil_nfse'))
	);
	$itemseq	= $entidade->addAttr(
		array("nome" => "itemseq" , "descricao" => "itemseq" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemcod	= $entidade->addAttr(
		array("nome" => "itemcod" , "descricao" => "itemcod" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemdesc	= $entidade->addAttr(
		array("nome" => "itemdesc" , "descricao" => "itemdesc" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemqtde	= $entidade->addAttr(
		array("nome" => "itemqtde" , "descricao" => "itemqtde" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemvunit	= $entidade->addAttr(
		array("nome" => "itemvunit" , "descricao" => "itemvunit" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemumed	= $entidade->addAttr(
		array("nome" => "itemumed" , "descricao" => "itemumed" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemvlded	= $entidade->addAttr(
		array("nome" => "itemvlded" , "descricao" => "itemvlded" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemtributavel	= $entidade->addAttr(
		array("nome" => "itemtributavel" , "descricao" => "itemtributavel" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemccnae	= $entidade->addAttr(
		array("nome" => "itemccnae" , "descricao" => "itemccnae" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemservmunic	= $entidade->addAttr(
		array("nome" => "itemservmunic" , "descricao" => "itemservmunic" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemnalvara	= $entidade->addAttr(
		array("nome" => "itemnalvara" , "descricao" => "itemnalvara" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemviss	= $entidade->addAttr(
		array("nome" => "itemviss" , "descricao" => "itemviss" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemvdesconto	= $entidade->addAttr(
		array("nome" => "itemvdesconto" , "descricao" => "itemvdesconto" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemaliquota	= $entidade->addAttr(
		array("nome" => "itemaliquota" , "descricao" => "itemaliquota" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);
	$itemvlrtotal	= $entidade->addAttr(
		array("nome" => "itemvlrtotal" , "descricao" => "itemvlrtotal" , "tipo" => "varchar" , "tamanho" => 25 , "tipohtml" => 3)
	);