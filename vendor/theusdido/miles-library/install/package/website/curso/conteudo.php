<?php
	// Cria Entidade
	$entidade 	= new Entity("website_curso_conteudo","Conteúdo");

	// Atributos
	$titulo	  = $entidade->addAttr(
		array("nome" => "titulo" , "descricao" => "Título", "is_display" => true, "is_exibirgradedados" => true)
	);