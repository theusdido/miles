<?php
	$nome 		= $dados["nome"];
	$celular	= str_replace(array("(",")"," ","-","."),"",$dados["celular"]);

	$usuario = tdc::p("td_aplicativo_usuario");
	$usuario->nome 		= $nome;
	$usuario->celular 	= $celular;
	$usuario->inativo 	= true;
	$usuario->armazenar();
	$retorno["dados"] = $usuario->getDataArray();
	$codigovalidador 	= rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
	$codigovalidador 	= 7640;
	$retorno["dados"]["codigovalidador"] = $codigovalidador;

	// Envia o código para o celula
	$paramsenvio = "celular=" . $celular . "&codigo=" . $codigovalidador;	
	//getUrl("http://webservice.locativa.com.br/administradoracondominio/enviarteste.php?" . $paramsenvio);