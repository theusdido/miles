<?php	
	criarRelacionamento(
		$conn,
		5,
		getEntidadeId("erp_imobiliaria_pessoa",$conn),
		getEntidadeId("erp_finaneiro_contabancaria",$conn),
		"Conta Bancária",
		0
	);