<?php
	$entidadeNome = PREFIXO . "ecommerce_metodopagamento";

	inserirRegistro($conn,$entidadeNome,1,array("descricao"),array("'Cartão de Crédito'"));
	inserirRegistro($conn,$entidadeNome,2,array("descricao"),array("'Boleto'"));
	inserirRegistro($conn,$entidadeNome,3,array("descricao"),array("'Debito Online'"));
	inserirRegistro($conn,$entidadeNome,4,array("descricao"),array("'Saldo do PagSeguro'"));
	inserirRegistro($conn,$entidadeNome,5,array("descricao"),array("'Oi Paggo'"));
	inserirRegistro($conn,$entidadeNome,6,array("descricao"),array("'Depósito em Conta'"));