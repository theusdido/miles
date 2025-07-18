<?php
	// Cria Entidade
	$entidade 	= new Entity("profissao","Profissão");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

	$detalhamento = $entidade->addAttr(
		array("nome" => "detalhamento" , "descricao" => "Detalhamento", "tipohtml" => "ckeditor")
	);