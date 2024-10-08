<?php
	
	criarRelacionamento(
		$conn,
		5,
		getEntidadeId("imobiliaria_pessoa",$conn),
		getEntidadeId("erp_geral_telefone",$conn),		
		"Telefone",
		0
	);