<?php
	// Cria Entidade
	$entidade 	= new Entity("categoria","Categoria");

	// Atributos
	$descricao	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
	);