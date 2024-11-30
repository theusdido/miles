<?php
	require 'servicos/pagseguro/credenciais.php';
	require_once PATH_MILES_LIBRARY . 'classes/ecommerce/endereco.class.php';

	// Dados do Pedido
	$valor_parc 				= tdc::r('valor');
	$qtde_parc 					= tdc::r('qtde');

	// Dados do Comprador
	$cliente_id				= tdc::r('cliente');
	$nome 					= tdc::r('nome');
	$cpf 					= str_replace(array(".","-","/"),"",tdc::r('cpf'));	
	$datanascimento 		= tdc::r('datanascimento');
	$telefone 				= str_replace(array("(",")"," ","-"),array(""),tdc::r('telefone'));
	$codAreaTelefone 		= substr($telefone,0,2);
	$numeroTelefone 		= substr($telefone,2,9);
	$email 					= tdc::r('email');

	// Atualiza data de nascimento
	$_cliente 					= tdc::p('td_ecommerce_cliente',$cliente_id);
	$_cliente->datanascimento	= dateToMysqlFormat($datanascimento,true);
	$_cliente->armazenar();

	// Dados do Endereço
	$endereco_class 		= new tdEcommerceEndereco();
	$endereco_class->setCliente($cliente_id);
	$endereco 				= (object)$endereco_class->getDados();
	$logradouro 			= $endereco->logradouro;
	$numero 				= isset($enderecojson->numero)?$enderecojson->numero:'S/N';
	$complemento 			= isset($enderecojson->complemento)?$enderecojson->complemento:'';
	$bairro 				= $endereco->bairrodesc == NULL ? 'Não Informado' : $endereco->bairrodesc;
	$cep 					= $endereco->cep;
	$cidade 				= isset($endereco->cidade)?$endereco->cidade:1;
	$uf 					= isset($endereco->uf)?$endereco->uf:'SC';

	// Dados do Pagamento
	$hash 						= tdc::r('hash');
	$token 						= tdc::r('token_cartao');
	$titular 					= $nome;

	// Carrinho de Compras
	$cart						= tdc::da('td_ecommerce_carrinhocompras',tdc::f('sessionid','=',tdc::r('cart_session')));
	$cart_items					= tdc::d('td_ecommerce_carrinhoitem',tdc::f('carrinho','=',$cart[0]['id']));
	$i 							= 1;

	// Itens do Carrinho de Compras
	foreach ($cart_items as $item){
		$itens["itemId" . $i] 			= $item->id;
		$itens["itemDescription" . $i] 	= utf8_encode($item->descricao);
		$itens["itemAmount" . $i] 		= number_format($item->valor,2,'.','');
		$itens["itemQuantity" . $i] 	= $item->qtde;
		$i++;
	}

	$dados =  array(
		'email' 					=> $credenciais["email"],
		'token' 					=> $credenciais["token"],
		'paymentMode' 				=> 'default',
		'paymentMethod' 			=> 'creditCard',
		'receiverEmail' 			=> $credenciais["email"],
		'currency' 					=> 'BRL',
		'extraAmount' 				=> '0.00',				
		'notificationURL' 			=> tdc::utf8($credenciais["notificacao_url"]),
		'reference' 				=> 1,
		'senderName' 				=> $nome,
		'senderCPF' 				=> $cpf,
		'senderAreaCode' 			=> $codAreaTelefone,
		'senderPhone' 				=> $numeroTelefone,
		'senderEmail' 				=> $email,
		'senderHash' 				=> $hash,
		'shippingAddressRequired' 	=> false,
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
		'creditCardToken' 				=> $token,
		'installmentQuantity' 			=> $qtde_parc, #Quantidade do Parcelamento
		'installmentValue' 				=> number_format($valor_parc,2,'.',''),
		#'noInterestInstallmentQuantity' => 1, #Quantidade de parcelas sem juros oferecidas ao cliente.
		'creditCardHolderName' 			=> $titular,
		'creditCardHolderCPF' 			=> $cpf,
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
		'timeout' 						=> 25
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
		CURLOPT_URL => 'https://ws.'.$credenciais["environment"].'pagseguro.uol.com.br/v2/transactions/',
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
			$retorno["status"] 		= 'success';
			$retorno['status_code']	= 1;
			$retorno['msg']			= 'Estamos aguardando a confirmação do pagamento junto com a operadora do cartão. Assim que tivermos uma resposta da operadora lhe enviaremos um e-mail.';
		break;
		case 2:
			$retorno["status"] 		= 'success';
			$retorno['status_code']	= 2;
			$retorno["status"] 		= 'Estamos analisando a situação do seu cartão de crédito junto com a operadora. Assim que tivermos uma resposta da operadora lhe enviaremos um e-mail.';
		break;
		case 3:
			$retorno["status"] 		= 'success';
			$retorno['status_code']	= 3;
			$retorno["status"] 		= 'Obrigado, seu pedido foi finalizado com sucesso.';
		break;
		case 4:
			$retorno["status"] 		= 'success';
			$retorno['status_code']	= 4;
			$retorno["status"] 		= 'Obrigado, seu pedido foi finalizado com sucesso e já está disponível.';
		break;
		default:
			$retorno["status"] 	= 'error';
			$retorno['msgs']	= $respXML;
	}