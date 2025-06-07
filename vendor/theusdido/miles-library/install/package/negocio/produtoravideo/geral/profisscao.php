<?php
	// Cria Entidade
	$entidade 	= new Entity("negocio_produtorvideo_profissao","Profissão");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

	$detalhamento = $entidade->addAttr(
		array("nome" => "detalhamento" , "descricao" => "Detalhamento", "tipohtml" => "ckeditor")
	);