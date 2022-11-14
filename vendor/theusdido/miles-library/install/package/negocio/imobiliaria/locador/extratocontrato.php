 <?php
	// Cria Entidade
	$entidade 	= new Entity("imobiliaria_locador_extratocontrato","Extrato Contrato");
	
	// Atributos
	$extrato	= $entidade->addAttr(
		array("nome" => "extrato" , "descricao" => "Extrato" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId("imobiliaria_locador_extrato"))
	);
	
	$contrato	= $entidade->addAttr(
		array("nome" => "contrato" , "descricao" => "Contrato" , "tipohtml" => "numero_inteiro")
	);
	
	$referencia	  = $entidade->addAttr(
		array("nome" => "referencia" , "descricao" => "Referência" , "tipo" => "char", "tamanho" => 6)
	);
	
	$pendente	    = $entidade->addAttr(
		array("nome" => "pendente" , "descricao" => "Pendente" , "tipo" => "char", "tamanho" => 1)
	);
	
	$valor     = $entidade->addAttr(
		array("nome" => "valor" , "descricao" => "Valor" , "tipohtml" => 3, "tamanho" => 25)
	);
	
	$debito     = $entidade->addAttr(
		array("nome" => "debito" , "descricao" => "Débito" , "tipohtml" => 3, "tamanho" => 25)
	);

	$credito     = $entidade->addAttr(
		array("nome" => "credito" , "descricao" => "Crédito" , "tipohtml" => 3, "tamanho" => 25)
	);

	$locatario	= $entidade->addAttr(
		array("nome" => "locatario" , "descricao" => "Locatário" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia("erp_pessoa",'package/sistema/erp/pessoa/pessoa'))
	);
	
	$endereco	= $entidade->addAttr(
		array("nome" => "endereco" , "descricao" => "Endereço" , "tipo" => "varchar" , "tamanho" => 200)
	);
	
	// Atributos
	$locatario_nome	= $entidade->addAttr(
		array("nome" => "locatario_nome" , "descricao" => "Nome do Locatário" , "tipo" => "varchar" , "tamanho" => 200)
	);

    criarRelacionamento($conn,2,getEntidadeId('imobiliaria_locador_extrato'),$entidade->getID(),'Contratos',$extrato);
	
   	installDependencia('imobiliaria_locador_extratoevento','package/negocio');