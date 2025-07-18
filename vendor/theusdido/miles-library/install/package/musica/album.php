<?php
	// Cria Entidade
	$entidade 	= new Entity("album","Álbum");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

	$datalancamento	= $entidade->addAttr(
		array("nome" => "datalancamento" , "descricao" => "Data de Lançamento" , "tipohtml" => "data")
	);