<?php

	// Dados do Comprador
	$nome					= tdc::r('nome');
	$email					= tdc::r('email');
	$cpf 					= str_replace(array(".","-","/"),array(""),tdc::r('cpf'));
	$cnpj 					= tdc::r('cnpj');	
	$datanascimento 		= tdc::r('datanascimento');
	$telefone 				= str_replace(array("(",")"," ","-"),"",tdc::r('telefone'));
	$codAreaTelefone 		= substr($telefone,0,2);
	$numeroTelefone 		= substr($telefone,2,9);

	$logradouro 			= 'Rua Walter da Silva Medeiros';
	$numero 				= '85';
	$complemento 			= '';
	$bairro 				= 'Jardim Angélica';
	$cep 					= str_replace("-","",'88804-770');
	$cidade 				= 'Criciúma';
	$uf 					= 'SC';
	$pais 					= 'BRA';

	// Dados do Pagamento com Cartão
	$hash 					= tdc::r('hash');
	$token 					= tdc::r('token');
	$qtde 					= tdc::r('qtde');
	$valor					= tdc::r('valor');

	// Dados Item
	$itens["itemId1"] 			= 1;
	$itens["itemDescription1"] 	= 'Crédito de R$ ' . number_format($valor,2,',','.');
	$itens["itemAmount1"] 		= number_format($valor,2);
	$itens["itemQuantity1"] 	= 1;

	// Movimentação	
	$movimentacao 					= tdc::p('td_carteiradigital_movimentacao');
	$movimentacao->loja      		= tdc::r('loja');
	$movimentacao->is_finalizada 	= true;
	$movimentacao->datahora			= date('Y-m-d H:i:s');
	$movimentacao->valor 			= $valor;
	$movimentacao->transacao		= 1;
	$movimentacao->armazenar();

	$dados 						=  array(
		'email' 						=> trim($vendedor_email),
		'token' 						=> trim($vendedor_token),
		'paymentMethod' 				=> 'creditCard',
		'receiverEmail' 				=> trim($vendedor_email),
		'currency' 						=> 'BRL',
		'paymentMode' 					=> 'default',
		'notificationURL' 				=> trim($vendedor_url_notification),
		'reference' 					=> $movimentacao->id,

		'senderEmail' 					=> $email,
		'senderName' 					=> $nome,
		'senderCPF' 					=> $cpf,
		'senderAreaCode' 				=> $codAreaTelefone,
		'senderPhone' 					=> $numeroTelefone,

		'senderHash' 					=> $hash,
		'shippingAddressStreet' 		=> $logradouro,
		'shippingAddressNumber' 		=> $numero,
		'shippingAddressComplement' 	=> $complemento,
		'shippingAddressDistrict' 		=> $bairro,
		'shippingAddressPostalCode' 	=> $cep,
		'shippingAddressCity' 			=> $cidade,
		'shippingAddressState' 			=> $uf,
		'shippingAddressCountry' 		=> 'BRA',

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
		'billingAddressCountry' 		=> 'BRA'
	);

	// URL de Transação do PagSeguro
	$urlPagseguroTransactions = "https://ws".($is_producao?"":".sandbox").".pagseguro.uol.com.br/v2/transactions/";

	// XML de Envio
	$xml = array_merge($itens,$dados);
	$postFields = ($xml ? http_build_query($xml, '', '&') : "");
	$methodOptions = array(
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postFields
	);

	$options = array(
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1",
			strlen($postFields)
		),
		CURLOPT_URL => $urlPagseguroTransactions,
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

	$respXML = new SimpleXMLElement($resp);
	switch((int)$respXML->status){
		case 1:
			echo '[{"status":"1","msg":"<div class=\'alert alert-success\' role=\'alert\'><b>Transação Realizada com Sucesso! </b> Estamos aguardando a confirmação do pagamento junto com a operadora do cartão. Assim que tivermos uma resposta da operadora lhe enviaremos um e-mail.</div>"}]';
		break;
		case 2:
			echo '[{"status":"2","msg":"<div class=\'alert alert-success\' role=\'alert\'><b>Transação Realizada com Sucesso! </b> Estamos analisando a situação do seu cartão de crédito junto com a operadora. Assim que tivermos uma resposta da operadora lhe enviaremos um e-mail.</div>"}]';
		break;
		case 3:
			echo '[{"status":"3","msg":"<div class=\'alert alert-success\' role=\'alert\'><b>Transação Realizada com Sucesso! </b> Obrigado, seu pedido foi finalizado com sucesso.</div>"}]';
		break;
		case 4:
			echo 'Disponível';
		break;
		default:
			echo '	{"status":"0","msg":";';
			echo '		<div class="alert alert-danger" role="alert">';
			echo '			<b> Foram encontrados os seguintes Erros: </b><br/>';
			echo '			<ul>';
				foreach ($respXML as $erro){
					echo '		<li> [<b>'.$erro->code.'</b>] - '.$erro->message.'</li>';
				}
			echo '			</ul';
			echo '		</div>';
			echo '	"}';
	}