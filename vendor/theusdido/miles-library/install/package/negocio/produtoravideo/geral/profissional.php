<?php
	// Cria Entidade
	$entidade 	= new Entity("negocio_produtorvideo_profissional","Profissional");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

	$foto = $entidade->addAttr(
		array("nome" => "foto" , "descricao" => "Foto", "tipohtml" => "arquivo_caminho")
	);	