<?php
	
	criarRelacionamento(
		$conn,
		5,
		getEntidadeId("erp_imobiliaria_imovel",$conn),
		getEntidadeId("erp_geral_foto",$conn),		
		"Foto",
		0
	);