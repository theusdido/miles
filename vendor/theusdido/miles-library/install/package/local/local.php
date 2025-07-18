<?php
	// Cria Entidade
	$entidade 	= new Entity("local","Local");

	// Atributos
	$nome	= $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome" , "tipo" => "varchar" , "tamanho" => 200)
	);