<?php
	
	criarRelacionamento(
		$conn,
		5,
		getEntidadeId("erp_imobiliaria_pessoa",$conn),
		getEntidadeId("erp_geral_referenciacomercial",$conn),		
		"Referencia Comercial",
		0
	);