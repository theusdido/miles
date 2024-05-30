<?php

	switch(tdClass::Read("op")){
		case "getSessionIdPagSeguro";

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
			echo $sessionID;
		break;
		case "pagar_com_cartaocredito":
			$valor = $qtde = 0;

			// Dados do Comprador
			$nome = $_POST["titular"];
			$cpf = str_replace(array(".","-","/"),array(""),$_POST["cpf"]);
			$hash = $_POST["hash"];			
			$datanascimento = $_POST["datanascimento"];
			$telefone = explode(" ",$_POST["telefone"]);
			$codAreaTelefone = str_replace(array("(",")"," "),array(""),$telefone[0]);
			$numeroTelefone = str_replace(array("-"," "),array(""),$telefone[1]);
			$logradouro = "Rua Walter da Silva Medeiros";
			$numero = "85";
			$complemento = "";
			$bairro = "Jardim Angélica";
			$cep = "88804770";
			$cidade = "Criciúma";
			$uf = "SC";
			$email = "edilson@sandbox.pagseguro.com.br";

			// Dados do Pagamento
			$token = $_POST["token"];
			$titular = $_POST["titular"];
			$carrinhoitens = json_decode('[{"id":1,"descricao":"Pizza","valor":10,"qtde":1}]');
			$itens = array();
			$i = 1;
			foreach ($carrinhoitens as $item){
				$qtde++;
				$valor += $item->valor;
				$itens["itemId" . $i] = $item->id;
				$itens["itemDescription" . $i] = utf8_decode($item->descricao);
				$itens["itemAmount" . $i] = number_format($item->valor,2);
				$itens["itemQuantity" . $i] = $item->qtde;
				$i++;
			}
			$dados =  array(
				'email' => "theusdido@hotmail.com",
				'token' => "F0908AA5BC894935A8630A1153655E59",
				'paymentMode' => 'default',
				'paymentMethod' => 'creditCard',
				'receiverEmail' => "theusdido@hotmail.com",
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
				'creditCardToken' => $token,
				'installmentQuantity' => $qtde,
				'installmentValue' => number_format($valor,2),
				'noInterestInstallmentQuantity' => 2,
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
					echo '[{"status":"1","msg":"<div class=\'alert alert-warning\' role=\'alert\'><b>Transação Realizada com Sucesso! </b> Estamos aguardando a confirmação do pagamento junto com a operadora do cartão. Assim que tivermos uma resposta da operadora lhe enviaremos um e-mail.</div>"}]';
				break;
				case 2:
					echo '[{"status":"2","msg":"<div class=\'alert alert-warning\' role=\'alert\'><b>Transação Realizada com Sucesso! </b> Estamos analisando a situação do seu cartão de crédito junto com a operadora. Assim que tivermos uma resposta da operadora lhe enviaremos um e-mail.</div>"}]';
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
		break;
	}