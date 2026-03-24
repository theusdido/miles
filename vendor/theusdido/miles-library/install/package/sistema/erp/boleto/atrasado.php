<?php
	// Cria Entidade
	$entidade 	= new Entity("boleto_atrasado","Boleto Atrasado");

	// Atributos
	$contrato	= $entidade->addAttr(
		array("nome" => "contrato" , "descricao" => "Contrato" , "tipohtml" => "numero_inteiro")
	);

	$referencia	= $entidade->addAttr(
		array("nome" => "referencia" , "descricao" => "Referencia" , "tipohtml" => "numero_inteiro")
	);

	$codigo	= $entidade->addAttr(
		array("nome" => "codigo" , "descricao" => "Código" , "tipohtml" => "numero_inteiro")
	);

	$nome	    = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome" , "tipohtml" => "texto")
	);

	$documento	    = $entidade->addAttr(
		array("nome" => "documento" , "descricao" => "Documento" , "tipo" => "varchar" , "tamanho" => 50)
	);	

	$email	  = $entidade->addAttr(
		array("nome" => "email" , "descricao" => "E-Mail", "tipohtml" => "email")
	);
	
	$fi	= $entidade->addAttr(
		array("nome" => "fi" , "descricao" => "FI" , "tipo" => "char" , "tamanho" => 1)
	);	