<?php
	// Cria Entidade
	$entidade 	= new Entity("faixatipo","Tipo de Faixa");

	// Atributos
	$descricao	  = $entidade->addAttr(
		array("nome" => "faixa" , "descricao" => "Faixa", "is_display" => true, "is_exibirgradedados" => true)
	);