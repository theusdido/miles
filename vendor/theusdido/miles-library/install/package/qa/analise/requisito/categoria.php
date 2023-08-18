<?php
	// Cria Entidade
	$entidade 	= new Entity("qa_requisito_categoria","Categoria");

	// Atributos
	$descricao	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição")
	);