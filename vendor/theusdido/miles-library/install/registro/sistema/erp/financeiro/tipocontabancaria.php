<?php

	// Entidade
	$entidadeNome   = getSystemPREFIXO() . "erp_financeiro_tipocontabancaria";
	$campos			= array("descricao");

	// -- Registros
    inserirRegistro($conn,$entidadeNome,1,$campos,array("'Poupança'"));
    inserirRegistro($conn,$entidadeNome,2,$campos,array("'Corrente'"));
    inserirRegistro($conn,$entidadeNome,3,$campos,array("'Salário'"));