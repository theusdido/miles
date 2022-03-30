<?php
	$entidadeNome = PREFIXO . "imobiliaria_tipoimovel";

	inserirRegistro($conn,$entidadeNome,1, array("descricao"), array("'Apartamento'"));
	inserirRegistro($conn,$entidadeNome,2, array("descricao"), array("'Casa'"));
	inserirRegistro($conn,$entidadeNome,3, array("descricao"), array("'Sala Comercial'"));
	inserirRegistro($conn,$entidadeNome,4, array("descricao"), array("'Pavilhão'"));
	inserirRegistro($conn,$entidadeNome,5, array("descricao"), array("'Kitnet'"));
	inserirRegistro($conn,$entidadeNome,6, array("descricao"), array("'Terreno'"));
	inserirRegistro($conn,$entidadeNome,7, array("descricao"), array("'Depósito'"));
	inserirRegistro($conn,$entidadeNome,8, array("descricao"), array("'Estacionamento'"));
	inserirRegistro($conn,$entidadeNome,9, array("descricao"), array("'Garagem'"));
	inserirRegistro($conn,$entidadeNome,10, array("descricao"), array("'Prédio'"));