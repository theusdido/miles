<?php
	// Cria Entidade
	$entidade 	= new Entity("generomusical","Gênero Musical");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
	);