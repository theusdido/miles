<?php
	// Cria Entidade
	$entidade 	= new Entity("imobiliaria_locador_extratoevento","Extrato Eventos");

	// Atributos
	$extratocontrato	= $entidade->addAttr(
		array("nome" => "extratocontrato" , "descricao" => "Extrato Contrato" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId("imobiliaria_locador_extratocontrato"))
	);

	$codigo	= $entidade->addAttr(
		array("nome" => "codigo" , "descricao" => "Código" , "tipo" => "varchar" , "tamanho" => 10)
	);

	$descricao	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição")
	);

	$valor     = $entidade->addAttr(
		array("nome" => "valor" , "descricao" => "Valor" , "tipohtml" => 3, "tamanho" => 25)
	);