<?php
	// Cria Entidade
	$entidade 	= new Entity("conteudo","Conteúdo");

	// Atributos
	$titulo	  = $entidade->addAttr(
		array("nome" => "titulo" , "descricao" => "Título", "is_display" => true, "is_exibirgradedados" => true)
	);