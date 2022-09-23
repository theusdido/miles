<?php
	// Entidade
	$entidadeNome   = getSystemPREFIXO() . "erp_financeiro_formapagamento";
	$campos			= array("descricao");

	// -- Registros
    inserirRegistro($conn,$entidadeNome,1,$campos,array("'Cartão de Crédito'"));
    inserirRegistro($conn,$entidadeNome,2,$campos,array("'Cartão de Débito'"));
    inserirRegistro($conn,$entidadeNome,3,$campos,array("'Pix'"));
    inserirRegistro($conn,$entidadeNome,4,$campos,array("'Espécie'"));