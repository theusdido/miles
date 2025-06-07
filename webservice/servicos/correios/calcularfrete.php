<?php
	
	$cep_default					= '88800-001';
	$configuracoes_ecommerce		= tdc::ru('ecommerce_configuracoes');
	$cep_origem						= !$configuracoes_ecommerce->cep_origem_pedido ? $cep_default : $configuracoes_ecommerce->cep_origem_pedido;


	/* CORREIOS */
	$peso = $comprimento = $altura = $largura = $diametro = 0;

    $cep_destino                    = str_replace(array(' ','-','.'),'',tdc::r('cep_destino'));
	$parms                          = new stdClass;
	$parms->codServico				= "04014"; # "40010"
	$parms->cepOrigem				= str_replace(array(".","-"),array(""),$cep_origem==''?$cep_default:$cep_origem);
	$parms->cepDestino				= str_replace(array(".","-"),array(""),$cep_destino);
	$parms->peso					= (($peso == 0 || $peso == "" || $peso < 0.3 || $peso > 30)?0.3:$peso);
	$parms->codFormato				= 1;
	$parms->comprimento			= (($comprimento == 0 || $comprimento == "" || $comprimento < 16)?16:$comprimento);
	$parms->altura				= $altura <= 0 ? 1 : $altura;
	$parms->largura				= (($largura == 0 || $largura == "" || $largura < 11)?11:$largura);
	$parms->diametro				= $diametro;
	$parms->codMaoPropria			= "n";
	$parms->valorDeclarado		= 0;
	$parms->codAvisoRecebimento		= "n";

	$parms->codAdministrativo		= '';
	$parms->usuario					= '';

	$url_api_correios 				= 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';

	// Retorna os dados da requisição em JSON
	try{
		$soap               = new SoapClient($url_api_correios);
		$retorno["dados"]   = $soap->calculaTarifaServico($parms);
	}catch(Throwable $t){
		var_dump($t);
	}