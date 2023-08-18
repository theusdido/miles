<?php

	// Retorna os dados da requisição em JSON
	header("Content-Type: application/json", true);
	$client 	= new SoapClient("https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl");
	$Retorno 	= $client->consultaCEP(array('cep' => $dados['cep']));
	echo json_encode($Retorno->return);