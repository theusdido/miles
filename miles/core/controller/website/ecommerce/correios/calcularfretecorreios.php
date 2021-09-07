<?php
	/* CORREIOS */
	$peso = $comprimento = $altura = $largura = $diametro = 0;
	while ($linhaProduto =  $queryProduto->fetch()){
		$peso 			+= $linhaProduto["peso"];
		$comprimento 	+= $linhaProduto["comprimento"];
		$altura			+= $linhaProduto["altura"];
		$largura		+= $linhaProduto["largura"];
		$diametro		+= $linhaProduto["diametro"];
	}
	$parms = new stdClass;
	$parms->nCdEmpresa 				= "";
	$parms->sDsSenha				= "";
	$parms->nCdServico				= "40010";
	$parms->sCepOrigem				= str_replace(array(".","-"),array(""),"88804770");
	$parms->sCepDestino				= str_replace(array(".","-"),array(""),$cepUsuario);
	$parms->nVlPeso					= (($peso == 0 || $peso == "" || $peso < 0.3 || $peso > 30)?0.3:$peso);
	$parms->nCdFormato				= 1;
	$parms->nVlComprimento			= (($comprimento == 0 || $comprimento == "" || $comprimento < 16)?16:$comprimento);
	$parms->nVlAltura				= $altura;
	$parms->nVlLargura				= (($largura == 0 || $largura == "" || $largura < 11)?11:$largura);
	$parms->nVlDiametro				= $diametro;
	$parms->sCdMaoPropria			= "n";
	$parms->nVlValorDeclarado		= 0;
	$parms->sCdAvisoRecebimento		= "n";

	$soap = new SoapClient("http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?wsdl");			
	$retorno = $soap->CalcPrecoPrazo($parms);
	if ((int)$retorno->CalcPrecoPrazoResult->Servicos->cServico->Erro == 0){						
		$taxaentregaSEDEX = $retorno->CalcPrecoPrazoResult->Servicos->cServico->Valor;
		$prazoentregaSEDEX = $retorno->CalcPrecoPrazoResult->Servicos->cServico->PrazoEntrega;

		/* CORREIOS */
		echo '<input type="hidden" id="taxaok" name="taxaok" value="1" />';
		$exibircorrerios = true;																	
	}else{
		echo '<div class="alert alert-danger"><b>Erro: </b> - '.$retorno->CalcPrecoPrazoResult->Servicos->cServico->MsgErro.'</div>';
		/* CORREIOS */
		echo '<input type="hidden" id="taxaok" name="taxaok" value="0" />';
		$exibircorrerios =false;						
		exit;
	}