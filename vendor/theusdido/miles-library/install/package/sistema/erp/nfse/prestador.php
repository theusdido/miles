<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_prestador","Prestador");

	// Atributos
	$nfse	= $entidade->addAttr(
		array("nome" => "nfse" , "descricao" => "NFSE" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_nfse_nota'))
	);
	$pessoa	= $entidade->addAttr(
		array("nome" => "pessoa" , "descricao" => "Pessoa" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('erp_geral_pessoa','package/sistema/erp/pessoa/pessoa'))
	);

	$prestcnpj	= $entidade->addAttr(
		array("nome" => "prestcnpj" , "descricao" => "prestcnpj" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$prestrazaosocial	= $entidade->addAttr(
		array("nome" => "prestrazaosocial" , "descricao" => "prestrazaosocial" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$prestfantasia	= $entidade->addAttr(
		array("nome" => "prestfantasia" , "descricao" => "prestfantasia" , "tipo" => "varchar" , "tamanho" => 200)
	);    
	$prestim	= $entidade->addAttr(
		array("nome" => "prestim" , "descricao" => "prestim" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$prestie	= $entidade->addAttr(
		array("nome" => "prestie" , "descricao" => "prestie" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$prestcmc	= $entidade->addAttr(
		array("nome" => "prestcmc" , "descricao" => "prestcmc" , "tipo" => "varchar" , "tamanho" => 25)
	);    
	$prestendereco	= $entidade->addAttr(
		array("nome" => "prestendereco" , "descricao" => "prestendereco" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$presttplgr	= $entidade->addAttr(
		array("nome" => "presttplgr" , "descricao" => "presttplgr" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$prestnumero	= $entidade->addAttr(
		array("nome" => "prestnumero" , "descricao" => "prestnumero" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$prestcomplemento	= $entidade->addAttr(
		array("nome" => "prestcomplemento" , "descricao" => "prestcomplemento" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$prestbairro	= $entidade->addAttr(
		array("nome" => "prestbairro" , "descricao" => "prestbairro" , "tipo" => "varchar" , "tamanho" => 50)
	);
	$prestcmun	= $entidade->addAttr(
		array("nome" => "prestcmun" , "descricao" => "prestcmun" , "tipo" => "varchar" , "tamanho" => 50)
	);
	$prestuf	= $entidade->addAttr(
		array("nome" => "prestuf" , "descricao" => "prestuf" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$prestcep	= $entidade->addAttr(
		array("nome" => "prestcep" , "descricao" => "prestcep" , "tipo" => "varchar" , "tamanho" => 10)
	);
	$presttelefone	= $entidade->addAttr(
		array("nome" => "presttelefone" , "descricao" => "presttelefone" , "tipo" => "varchar" , "tamanho" => 50)
	);
	$prestemail	= $entidade->addAttr(
		array("nome" => "prestemail" , "descricao" => "prestemail" , "tipo" => "varchar" , "tamanho" => 200)
	);