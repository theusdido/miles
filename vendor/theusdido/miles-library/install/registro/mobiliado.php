<?php
	$entidadeNome = PREFIXO . "imobiliaria_mobiliado";

	inserirRegistro($conn,$entidadeNome,1, array("descricao"), array("'Sem Mobília'"));
	inserirRegistro($conn,$entidadeNome,2, array("descricao"), array("'Semi-Mobiliado'"));
	inserirRegistro($conn,$entidadeNome,3, array("descricao"), array("'Com Mobília'"));