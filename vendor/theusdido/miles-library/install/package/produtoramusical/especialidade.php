<?php
	// Cria Entidade
	$entidade 	= new Entity("especialidade","Especialidade");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
	);