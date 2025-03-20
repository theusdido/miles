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

    $capa = $entidade->addAttr(
		array("nome" => "capa" , "descricao" => "Capa", "tipohtml" => "arquivo_caminho", "is_obrigatorio" => 0)
	);

	$tema	  = $entidade->addAttr(
		array("nome" => "tema" , "descricao" => "Tema")
	);