<?php
	// Cria Entidade
	$entidade 	= new Entity("plano","Plano de Mixagem");

	// Atributos
	$descricao	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
	);

    $valor     = $entidade->addAttr(
        array("nome" => "valor" , "descricao" => "Valor" , "tipohtml" => "monetario")
    );    