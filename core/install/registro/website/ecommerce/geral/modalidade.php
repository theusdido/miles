<?php

	// Modalidade 
	$entidadeNome 	= getSystemPREFIXO() . "ecommerce_modalidade";
	$campos			= array("descricao");

	// Registros
	inserirRegistro($conn,$entidadeNome,1,  $campos, array("'Produto'"), true);
	inserirRegistro($conn,$entidadeNome,2,  $campos, array("'Serviço'"), true);