<?php
	// Cria Entidade
	$entidade 	= new Entity("assunto","Assunto");

	// Atributos
	$titulo	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
	);