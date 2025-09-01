<?php
	// Cria Entidade
	$entidade 	= new Entity("competicao_gincana_local","Local");

	// Atributos
	$nome	= $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome" , "tipo" => "varchar" , "tamanho" => 200)
	);