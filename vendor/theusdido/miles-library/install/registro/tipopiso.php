<?php
	$entidadeNome = PREFIXO . "imobiliaria_tipopiso";

	inserirRegistro($conn,$entidadeNome,1, array("descricao"), array("'Madeira'"));
	inserirRegistro($conn,$entidadeNome,2, array("descricao"), array("'Cerâmica'"));