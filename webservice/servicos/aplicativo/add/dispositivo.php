<?php

	$aparelho 	= $dados["aparelho"];
	$usuario	= $dados["usuario"];
	$token 		= $dados["token"];

	$dataset 	= tdc::d("td_aplicativo_dispositivo",tdc::f("aparelho","=",$aparelho));
	if (sizeof($dataset) > 0){
		$conn = Transacao::Get();
		$conn->exec("UPDATE td_aplicativo_dispositivo SET inativo = true WHERE aparelho = '{$aparelho}';");
		#$retorno["status"] = "erro";
		#$retorno["errormessage"] = "Esse aparelho já está cadastrado.";
	}

	$dispositivo 			= tdc::p("td_aplicativo_dispositivo");
	$dispositivo->id 		= $dispositivo->proximoID();
	$dispositivo->usuario 	= $usuario;
	$dispositivo->token 	= $token;
	$dispositivo->aparelho 	= $aparelho;
	$dispositivo->inativo 	= 1;
	$dispositivo->armazenar();

	$retorno["dados"] 		= $dispositivo->getDataArray();	