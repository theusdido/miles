<?php
	/* ** Busca o SESSION ID do PagSeguro ** */	
	// Credenciais	
	$credenciais =  array(
		"email" => trim($vendedor_email),
		"token" => trim($vendedor_token)
	);
	$postFileds = $postFields = ($credenciais ? http_build_query($credenciais, '', '&') : "");	
	$methodOptions = array(
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postFileds,
	);
   	$options = array(
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1",
			strlen($postFields)
		),
		CURLOPT_URL => "https://ws".($is_producao?"":".sandbox").".pagseguro.uol.com.br/v2/sessions",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_CONNECTTIMEOUT => 20
	);
	$options 		= ($options + $methodOptions);	
	$curl 			= curl_init();
	curl_setopt_array($curl, $options);
	$resp 			= curl_exec($curl);
	$info 			= curl_getinfo($curl);
	$error 			= curl_errno($curl);
	$errorMessage 	= curl_error($curl);
	curl_close($curl);
	if ($resp == "Unauthorized"){
		echo '<div class="alert alert-danger text-center" role="alert"><b>Ops! </b>Conexão com o <b>PAGSEGURO</b> não autorizada.</div>';
		exit;
	}
	$respXML 	= new SimpleXMLElement($resp);
	$session_id = (array)$respXML[0]->id;
	echo json_encode(array ( 'session_id' => $session_id[0] ));