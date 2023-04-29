<?php
	
	$cep_default					= '88800-001';
	$configuracoes_ecommerce		= tdc::ru('ecommerce_configuracoes');
	$cep_origem						= !$configuracoes_ecommerce->cep_origem_pedido ? $cep_default : $configuracoes_ecommerce->cep_origem_pedido;


	/* CORREIOS */
	$peso = $comprimento = $altura = $largura = $diametro = 0;

    $cep_destino                    = str_replace(array(' ','-','.'),'',tdc::r('cep_destino'));
	$parms                          = new stdClass;
	$parms->nCdEmpresa 				= "";
	$parms->sDsSenha				= "";
	$parms->nCdServico				= "04014"; # "40010"
	$parms->sCepOrigem				= str_replace(array(".","-"),array(""),$cep_origem==''?$cep_default:$cep_origem);
	$parms->sCepDestino				= str_replace(array(".","-"),array(""),$cep_destino);
	$parms->nVlPeso					= (($peso == 0 || $peso == "" || $peso < 0.3 || $peso > 30)?0.3:$peso);
	$parms->nCdFormato				= 1;
	$parms->nVlComprimento			= (($comprimento == 0 || $comprimento == "" || $comprimento < 16)?16:$comprimento);
	$parms->nVlAltura				= $altura <= 0 ? 1 : $altura;
	$parms->nVlLargura				= (($largura == 0 || $largura == "" || $largura < 11)?11:$largura);
	$parms->nVlDiametro				= $diametro;
	$parms->sCdMaoPropria			= "n";
	$parms->nVlValorDeclarado		= 0;
	$parms->sCdAvisoRecebimento		= "n";

	// Retorna os dados da requisição em JSON	
	try{
		$soap               = new SoapClient("http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?wsdl");
		$retorno["dados"]   = $soap->CalcPrecoPrazo($parms);
	}catch(Throwable $t){
		var_dump($t);
	}
