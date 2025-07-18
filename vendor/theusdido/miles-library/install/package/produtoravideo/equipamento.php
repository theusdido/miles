<?php
	// Cria Entidade
	$entidade 	= new Entity("equipamento","Equipamento");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

	$foto = $entidade->addAttr(
		array("nome" => "foto" , "descricao" => "Foto", "tipohtml" => "arquivo_caminho")
	);

	$detalhamento = $entidade->addAttr(
		array("nome" => "detalhamento" , "descricao" => "Detalhamento", "tipohtml" => "ckeditor")
	);

	$valor     = $entidade->addAttr(
		array("nome" => "valor" , "descricao" => "Valor" , "tipohtml" => "monetario")
	);