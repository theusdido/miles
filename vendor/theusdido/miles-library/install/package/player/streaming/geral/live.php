<?php
	// Cria Entidade
	$entidade 	= new Entity("player_streaming_geral_live","Live");

	// Atributos
	$titulo	  = $entidade->addAttr(
		array("nome" => "titulo" , "descricao" => "Título", "is_display" => true, "is_exibirgradedados" => true)
	);

	$data	  = $entidade->addAttr(
		array("nome" => "data" , "descricao" => "Data", "tipohtml" => "data")
	);

	$hora	  = $entidade->addAttr(
		array("nome" => "hora" , "descricao" => "Hora", "tipohtml" => "hora")
	);