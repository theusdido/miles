<?php

	// Cria Entidade
	$entidade 	= new Entity("player_musica_geral_plano","Plano");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

    $valor = $entidade->addAttr(
		array("nome" => "valor" , "descricao" => "Valor" , "tipohtml" => "monetario")
	);