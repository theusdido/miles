<?php

    $url 	= 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/order/' . $transaction_code . '?email=' . trim($vendedor_email) . '&token=' . trim($vendedor_token);
    $curl 	= curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $transaction = curl_exec($curl);
    exit;
    $urlPagseguroOrderPay       = "https://ws".($is_producao?"":".sandbox").".pagseguro.uol.com.br/v2/transactions/order/";
	$dados 						=  array(
		'email' 						=> trim($vendedor_email),
		'token' 						=> trim($vendedor_token),
		'paymentMethod' 				=> 'creditCard',
		'receiverEmail' 				=> trim($vendedor_email),
		'currency' 						=> 'BRL',
		'paymentMode' 					=> 'default',
		'notificationURL' 				=> trim($vendedor_url_notification),
		'reference' 					=> $movimentacao->id,

		'senderHash' 					=> $hash,

		'creditCardToken' 				=> $token,
		'installmentQuantity' 			=> $qtde,
		'installmentValue' 				=> $valor,
		'creditCardHolderName' 			=> $nome,
		'creditCardHolderCPF'			=> $cpf,
		'creditCardHolderBirthDate' 	=> $datanascimento,
		'creditCardHolderAreaCode' 		=> $codAreaTelefone,
		'creditCardHolderPhone' 		=> $numeroTelefone,
		'billingAddressStreet' 			=> $logradouro,
		'billingAddressNumber' 			=> $numero,
		'billingAddressComplement' 		=> $complemento,
		'billingAddressDistrict' 		=> $bairro,
		'billingAddressPostalCode' 		=> $cep,
		'billingAddressCity' 			=> $cidade,
		'billingAddressState' 			=> $uf,
		'billingAddressCountry' 		=> 'BRA',

        'status_to'                     => 3
	);

    #echo json_encode($dados);
    #exit;

	// XML de Envio
	$xml                = $dados;
	$postFields         = ($xml ? http_build_query($xml, '', '&') : "");
	$methodOptions      = array(
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postFields
	);

	$options = array(
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1",
			strlen($postFields)
		),
		CURLOPT_URL                 => $urlPagseguroOrderPay,
		CURLOPT_RETURNTRANSFER      => true,
		CURLOPT_HEADER              => false,
		CURLOPT_SSL_VERIFYPEER      => false,
		CURLOPT_SSL_VERIFYHOST      => false,
		CURLOPT_CONNECTTIMEOUT      => 20
	);

	$options 		= ($options + $methodOptions);
	$curl 			= curl_init();
	curl_setopt_array($curl, $options);
	$resp 			= curl_exec($curl);
	$info 			= curl_getinfo($curl);
	$error 			= curl_errno($curl);
	$errorMessage 	= curl_error($curl);
	curl_close($curl);

	$respXML = new SimpleXMLElement($resp);