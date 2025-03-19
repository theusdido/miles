<?php
	// Cria Entidade
	$entidade 	= new Entity("website_curso_instrutor","Instrutor");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);