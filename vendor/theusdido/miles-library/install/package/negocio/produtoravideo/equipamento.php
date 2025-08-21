<?php
	// Cria Entidade
	$entidade 	= new Entity("negocio_produtorvideo_equipamento","Equipamento");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);