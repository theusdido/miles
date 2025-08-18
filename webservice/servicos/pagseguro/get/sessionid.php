<?php

	$_pagseguro 				= tdc::ru("td_ecommerce_pagseguro");

	/* ** Busca o SESSION ID do PagSeguro ** */	
	// Credenciais
	$credenciais =  array(
		"email" => $_pagseguro->email,
		"token" => $_pagseguro->token
	);

	$postFileds = $postFields = ($credenciais ? http_build_query($credenciais, '', '&') : "");
	$methodOptions = array(
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postFileds,
	);

	$url_pagseguro_api = 	"https://ws.sandbox.pagseguro.uol.com.br/v2/sessions";
	#$url_pagseguro_api = 'https://secure.sandbox.api.pagseguro.com';
	#$url_pagseguro_api = 'https://sandbox.api.pagseguro.com/';
	#$url_pagseguro_api = 'https://ws.sandbox.pagbank.com.br';
	#$url_pagseguro_api = 'https://ws.pagbank.com.br/v2/sessions';
	#$url_pagseguro_api = 	"https://ws.pagseguro.uol.com.br/v2/sessions";
	

   	$options = array(
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1",
			strlen($postFields)
		),
		CURLOPT_URL => $url_pagseguro_api,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_CONNECTTIMEOUT => 25
	);	
	$options = ($options + $methodOptions);	
	$curl = curl_init();
	curl_setopt_array($curl, $options);
	$resp = curl_exec($curl);
	$info = curl_getinfo($curl);
	$error = curl_errno($curl);
	$errorMessage = curl_error($curl);	
	curl_close($curl);

	if ($resp == "Unauthorized"){
		echo '<div class="alert alert-danger text-center" role="alert"><b>Ops! </b>Conexão com o <b>PAGSEGURO</b> não autorizada.</div>';
		exit;
	}

	$respXML = new SimpleXMLElement($resp);
	#var_dump($respXML);
	$sessionID = $respXML[0]->id;
	$retorno["dados"] = (string)$sessionID;