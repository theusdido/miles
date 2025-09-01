<?php
	// Cria Entidade
	$entidade 	= new Entity("website_curso_assunto","Curso");

	// Atributos
	$titulo	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
	);