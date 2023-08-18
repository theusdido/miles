<?php

	$is_producao 				= false;
	$vendedor_email				= '';
	$vendedor_token				= '';
	$vendedor_url_notification 	= '';
	
	// Dados e configuração do vendedor
	$sqlIDEntidadePagSeguro = "SELECT 1 FROM td_entidade WHERE nome = 'td_ecommerce_pagseguro' LIMIT 1";
	$queryIDEntidadePagSeguro = $conn->query($sqlIDEntidadePagSeguro);
	if ($queryIDEntidadePagSeguro->rowCount()>0){
		$sqlVendedor = "SELECT is_producao,email,token,url_notification FROM td_ecommerce_pagseguro WHERE id = 1";
		$queryVendedor = $conn->query($sqlVendedor);
		if ($linhaVendedor = $queryVendedor->fetch()){
			$is_producao 				= ($linhaVendedor["is_producao"]==1)?true:false;
			$vendedor_email				= $linhaVendedor["email"];
			$vendedor_token				= $linhaVendedor["token"];
			$vendedor_url_notification	= $linhaVendedor["url_notification"];
		}
	}
	
	// Credenciais	
	$credenciais =  array(
		"email" => trim($vendedor_email),
		"token" => trim($vendedor_token)
	);

	switch(tdc::r('op')){
		case 'session_id':
			include 'sessionid.php';
		break;
		case 'pagar_cartaocredito';
			include 'pagamento.php';
		break;
		case 'notificacao':
			include 'notificacao.php';
		break;
		case 'pagar':
			include 'pagar.php';
		break;
	}