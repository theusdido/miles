<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_intermediario","Intermediário");

	// Atributos
	$nfse	= $entidade->addAttr(
		array("nome" => "nfse" , "descricao" => "NFSE" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_nfse_nota'))
	);
	$intermrazaosocial	= $entidade->addAttr(
		array("nome" => "intermrazaosocial" , "descricao" => "Razão Social" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$intermcnpj	= $entidade->addAttr(
		array("nome" => "intermcnpj" , "descricao" => "CNPJ" , "tipo" => "varchar" , "tamanho" => 25)
	);	
	$intermcpf	= $entidade->addAttr(
		array("nome" => "intermcpf" , "descricao" => "CPF" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$intermim	= $entidade->addAttr(
		array("nome" => "intermim" , "descricao" => "intermim" , "tipo" => "varchar" , "tamanho" => 25)
	);