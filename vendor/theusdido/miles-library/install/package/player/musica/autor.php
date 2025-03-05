<?php
	// Cria Entidade
	$entidade 	= new Entity("player_musica_autor","Autor");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);