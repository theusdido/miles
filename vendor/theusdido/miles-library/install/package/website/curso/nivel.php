<?php
	// Cria Entidade
	$entidade 	= new Entity("website_curso_nivel","Nível");

	// Atributos
	$descricao	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
	);