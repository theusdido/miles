<?php
	$entidadeNome = PREFIXO . "imobiliaria_tipoendereco";

	inserirRegistro($conn,$entidadeNome,1, array("descricao"), array("'Residêncial'"));
	inserirRegistro($conn,$entidadeNome,2, array("descricao"), array("'Comercial'"));
	inserirRegistro($conn,$entidadeNome,3, array("descricao"), array("'Cobrança'"));
	inserirRegistro($conn,$entidadeNome,4, array("descricao"), array("'Entrega'"));