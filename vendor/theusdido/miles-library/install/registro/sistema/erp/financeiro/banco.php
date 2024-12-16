<?php
	// Banco
	$entidadeNome = getSystemPREFIXO() . "erp_financeiro_banco";
	$campos			= array("descricao","codigo","sigla");

	// Registros
	inserirRegistro($conn,$entidadeNome,1,$campos,array("'Banco de Brasil'","'001'","'BB'"));
    inserirRegistro($conn,$entidadeNome,2,$campos,array("'Caixa Econômica Federeal'","'104'","'CEF'"));