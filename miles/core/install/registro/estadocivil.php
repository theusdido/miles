<?php
	$entidadeNome = PREFIXO . "erp_geral_estadocivil";
	inserirRegistro($conn,$entidadeNome,1, array("descricao"), array("'Solteiro(a)'"));
	inserirRegistro($conn,$entidadeNome,2, array("descricao"), array("'Casado(a)'"));
	inserirRegistro($conn,$entidadeNome,3, array("descricao"), array("'Divorciado(a)'"));
	inserirRegistro($conn,$entidadeNome,4, array("descricao"), array("'Viúvo(a)'"));