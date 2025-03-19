<?php
	// Cria Entidade
	$entidade 	= new Entity("website_blog_coluna","Coluna");

	// Atributos
	$descricao	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
	);

	$texto	  = $entidade->addAttr(
		array("nome" => "texto" , "descricao" => "Texto", "tiphtml" => 'areatexto')
	);