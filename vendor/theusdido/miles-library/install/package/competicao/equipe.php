<?php
	// Cria Entidade
	$entidade 	= new Entity("competicao_gincana_equipe","Equipe");

	// Atributos
	$nome	= $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome" , "tipo" => "varchar" , "tamanho" => 50)
	);