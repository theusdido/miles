<?php
	/* ** Busca o SESSION ID do PagSeguro ** */	
	// Credenciais
	$credenciais =  array(
		"email" => "theusdido@hotmail.com",
		"token" => "F0908AA5BC894935A8630A1153655E59"
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
		CURLOPT_URL => "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions",
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
	$sessionID = $respXML[0]->id;
	$retorno["dados"] = (string)$sessionID;