<?php
	// Cria Entidade
	$entidade 	= new Entity("player_musica_geral_publicacao","Publicação");

	// Atributos
	$descricao	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
	);