<?php
	$entidadeNome = PREFIXO . "erp_geral_genero";
	inserirRegistro($conn,$entidadeNome,1, array("descricao"), array("'Masculino'"));
	inserirRegistro($conn,$entidadeNome,2, array("descricao"), array("'Feminino'"));