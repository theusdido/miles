<?php
	require 'servicos/pagseguro/credenciais.php';

	$pedido					= json_decode(tdClass::Read("dados"));
	$valor = $qtde = 1;

	// Dados do Comprador
	$nome 					= $pedido->nome;
	$cpf 					= str_replace(array(".","-","/"),"",$pedido->cpf);	
	$datanascimento 		= $pedido->datanascimento;
	$telefone 				= str_replace(array("(",")"," ","-"),array(""),$pedido->telefone);
	$codAreaTelefone 		= substr($telefone,0,2);
	$numeroTelefone 		= substr($telefone,2,9);
	$email 					= $pedido->email;

	if (isset($pedido->endereco)){
		$endereco 				= json_decode($pedido->endereco);
		$logradouro 			= $endereco->logradouro;
		$numero 				= isset($enderecojson->numero)?$enderecojson->numero:'S/N';
		$complemento 			= isset($enderecojson->complemento)?$enderecojson->complemento:'';
		$bairro 				= $endereco->bairro;
		$cep 					= $endereco->cep;
		$cidade 				= isset($endereco->cidade)?$endereco->cidade:1;
		$uf 					= isset($endereco->uf)?$endereco->uf:'SC';
	}

	// Dados do Pagamento
	$hash 						= $pedido->hash;
	$token 						= $pedido->tokencartao;
	$titular 					= $pedido->titular;
	$carrinhoitens 				= json_decode('[{"id":1,"descricao":"Pizza","valor":10,"qtde":1}]');
	$itens 						= array();
	$i 							= 1;
	/*
	foreach ($carrinhoitens as $item){
		$qtde++;
		$valor += $item->valor;
		$itens["itemId" . $i] = $item->id;
		$itens["itemDescription" . $i] = utf8_decode($item->descricao);
		$itens["itemAmount" . $i] = number_format($item->valor,2);
		$itens["itemQuantity" . $i] = $item->qtde;
		$i++;
	}
	*/
	$itens["itemId1"] = 1;
	$itens["itemDescription1"] = utf8_decode("TESTE ...");
	$itens["itemAmount1"] = number_format($valor,2);
	$itens["itemQuantity1"] = 1;
	
	$dados =  array(
		'email' => $credenciais["email"],
		'token' => $credenciais["token"],
		'paymentMode' => 'default',
		'paymentMethod' => 'creditCard',
		'receiverEmail' => $credenciais["email"],
		'currency' => 'BRL',
		'extraAmount' => '0.00',				
		'notificationURL' => "",
		'reference' => 1,
		'senderName' => $nome,
		'senderCPF' => $cpf,
		'senderAreaCode' => $codAreaTelefone,
		'senderPhone' => $numeroTelefone,
		'senderEmail' => $email,
		'senderHash' => $hash,
		'shippingAddressRequired' => false,
		/*
		'shippingAddressStreet' => $logradouro,
		'shippingAddressNumber' => $numero,
		'shippingAddressComplement' => $complemento,
		'shippingAddressDistrict' => $bairro,
		'shippingAddressPostalCode' => $cep,
		'shippingAddressCity' => $cidade,
		'shippingAddressState' => $uf,
		'shippingAddressCountry' => 'BRA',
		'shippingType' => '',
		'shippingCost' => null,
		*/
		'creditCardToken' => $token,
		'installmentQuantity' => 1, #Quantidade do Parcelamento
		'installmentValue' => number_format($valor,2),
		#'noInterestInstallmentQuantity' => 1, #Quantidade de parcelas sem juros oferecidas ao cliente.
		'creditCardHolderName' => $titular,
		'creditCardHolderCPF' => $cpf,
		'creditCardHolderBirthDate' => $datanascimento,
		'creditCardHolderAreaCode' => $codAreaTelefone,
		'creditCardHolderPhone' => $numeroTelefone,
		'billingAddressStreet' => $logradouro,
		'billingAddressNumber' => $numero,
		'billingAddressComplement' => $complemento,
		'billingAddressDistrict' => $bairro,
		'billingAddressPostalCode' => $cep,
		'billingAddressCity' => $cidade,
		'billingAddressState' => $uf,
		'billingAddressCountry' => 'BRA',
		'timeout' =>25
	);

	// XML de Envio
	$xml = array_merge($itens,$dados);
	$postFileds = $postFields = ($xml ? http_build_query($xml, '', '&') : "");
	$methodOptions = array(
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postFileds,
	);

   $options = array(
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1",
			strlen($postFields)
		),
		CURLOPT_URL => "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/",
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
	$respXML = new SimpleXMLElement($resp);
	switch((int)$respXML->status){
		case 1:
			$retorno["transacao"] = '{"status":1,"msg":"<div class=\"alert alert-warning\" role=\"alert\"><b>Transação Realizada com Sucesso! </b> Estamos aguardando a confirmação do pagamento junto com a operadora do cartão. Assim que tivermos uma resposta da operadora lhe enviaremos um e-mail.</div>\}';
		break;
		case 2:
			$retorno["transacao"] = '{"status":2,"msg":"<div class=\"alert alert-warning\" role=\'alert\"><b>Transação Realizada com Sucesso! </b> Estamos analisando a situação do seu cartão de crédito junto com a operadora. Assim que tivermos uma resposta da operadora lhe enviaremos um e-mail.</div>"}';
		break;
		case 3:
			$retorno["transacao"] = '{"status":3,"msg":"<div class=\"alert alert-success\" role=\"alert\"><b>Transação Realizada com Sucesso! </b> Obrigado, seu pedido foi finalizado com sucesso.</div>"}';
		break;
		case 4:
			$retorno["transacao"] = 'Disponível';
		break;
		default:
			$error =  '	{"status":"0","msg":"';
			$error .= '		<div class="alert alert-danger" role="alert">';
			$error .= '			<b> Foram encontrados os seguintes Erros: </b><br/>';
			$error .= '			<ul>';
				foreach ($respXML as $erro){
					$error .= '		<li> [<b>'.$erro->code.'</b>] - '.$erro->message.'</li>';
				}
			$error .= '			</ul';
			$error .= '		</div>';
			$error .= '	"}';
			$retorno["transacao"] = $error;
	}