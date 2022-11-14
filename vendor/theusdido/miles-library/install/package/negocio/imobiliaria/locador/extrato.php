 <?php
	// Cria Entidade
	$entidade 	= new Entity("imobiliaria_locador_extrato","Extrato");
	
	// Atributos
	$proprietario	= $entidade->addAttr(
		array("nome" => "proprietario" , "descricao" => "Proprietario" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia("erp_pessoa",'package/sistema/erp/pessoa/pessoa'))
	);

	$referencia	    = $entidade->addAttr(
		array("nome" => "referencia" , "descricao" => "Referência" , "tipo" => 'char' , "tamanho" => 6)
	);

	$documento	    = $entidade->addAttr(
		array("nome" => "documento" , "descricao" => "Documento" , "tipohtml" => 3 , "tamanho" => 50)
	);

	$valor     = $entidade->addAttr(
		array("nome" => "valor" , "descricao" => "Valor" , "tipohtml" => 3, "tamanho" => 25)
	);

	$tipo_pagamento	= $entidade->addAttr(
		array("nome" => "tipo_pagamento" , "descricao" => "Tipo de Pagamento" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia("erp_financeiro_formapagamento",'package/sistema/erp/financeiro/formapagamento'))
	);

	$is_negativo	= $entidade->addAttr(
		array("nome" => "is_negativo" , "descricao" => "Negativo ?" , "tipohtml" => "checkbox")
	);

	$token	= $entidade->addAttr(
		array("nome" => "token" , "descricao" => "Token" , "tipo" => "varchar")
	);

	$observacao	    = $entidade->addAttr(
		array("nome" => "observacao" , "descricao" => "Observação")
	);

    installDependencia('imobiliaria_locador_extratocontrato','package/negocio');