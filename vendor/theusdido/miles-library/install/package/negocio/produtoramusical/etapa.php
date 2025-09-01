<?php
	// Cria Entidade
	$entidade 	= new Entity("negocio_produtoramusical_etapa","Etapa");

	// Atributos
	$descricao	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
	);