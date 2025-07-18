<?php
	// Cria Entidade
	$entidade 	= new Entity("direitoautoral","Direito Autoral");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

	$datarelease	  = $entidade->addAttr(
		array("nome" => "datarelease" , "descricao" => "Data do Release")
	);

	$datagravacao	  = $entidade->addAttr(
		array("nome" => "datagravacao" , "descricao" => "Data da Greavação")
	);