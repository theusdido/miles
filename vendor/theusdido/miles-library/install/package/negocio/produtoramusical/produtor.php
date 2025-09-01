<?php
	// Cria Entidade
	$entidade 	= new Entity("negocio_produtoramusical_produtor","Produtor");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);