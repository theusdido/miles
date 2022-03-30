<?php
	
	criarRelacionamento(
		$conn,
		5,
		getEntidadeId("imobiliaria_pessoa",$conn),
		getEntidadeId("erp_pessoa_referenciacomercial",$conn),		
		"Referencia Comercial",
		0
	);