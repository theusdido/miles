<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);	

	if (tdClass::Read("opt") == "habdiv"){
		$protocolo = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$_GET["protocolo"]))->contexto;		
	}else if(tdClass::Read("opt") == "assembleia"){
		$protocolo = tdClass::Criar("persistent",array("td_arquivosassembleia",$_GET["protocolo"]))->contexto;
	}
	
	$credor = tdClass::Criar("persistent",array("td_relacaocredores",$protocolo->credor))->contexto;
	$processo = tdClass::Criar("persistent",array("td_processo",$protocolo->processo))->contexto;
	$farein = tdClass::Criar("persistent",array($processo->tipoprocesso==16?"td_recuperanda":"td_falencia",$credor->farein))->contexto;
	$conn = Transacao::Get();
	$dt = explode(" ",$protocolo->data);
	try{
		switch($processo->tipoprocesso){
			case 16: $dataexibicao = dateToMysqlFormat($farein->datapedido,true); break;
			case 19: $dataexibicao = dateToMysqlFormat($farein->datasentenca,true); break;
			default: $dataexibicao = "";
		}
	}catch(Exception $e){
		$dataexibicao = "";
	}
	$html = '
		<style>
			h1{
				font-size:52px;
				text-align:center;
				font-weight:bold;
				font-family:Tahoma, Arial;
			}
		</style>
		<img src="'.Session::Get("PATH_CURRENT_IMG").'capa-div-hab.jpg" />
		<br/><br/>
		<p>PROTOCOLO Nº: <b>'.$protocolo->numero.'</b></p>
		<p>DATA: <b>'.dateToMysqlFormat($dt[0],true).' '.$dt[1].'</b></p>	
		<br/>
		<p>NOME DA '.($processo->tipoprocesso==16?"RECUPERANDA":"FALIDA").': <b>'.utf8_encode(strtoupper($farein->razaosocial)).'</b></p>
		<p>Nº PROCESSO: <b>'.$processo->numeroprocesso.'</b></p>
		<p>DATA '.($processo->tipoprocesso==19?"DO PEDIDO":"DA SETENÇA").' DA RJ: <b>'.$dataexibicao.'</b></p>
		<br/>
		<p>CREDOR: <b>'.utf8_encode(strtoupper($credor->nome)).'</b></p>
		<p>CPF/CNPJ: <b>'.utf8_encode(strtoupper($credor->cnpj.$credor->cpf)).'</b></p>
		<p>EMAIL: <b>'.strtoupper($credor->email).'</b></p>
		<p>TELEFONE: <b></b></p>
		<br/>			
		<p>NOME DO REQUERENTE: <b>'.utf8_encode(strtoupper($protocolo->nomeremetente)).'</b></p>
		<p>EMAIL: <b>'.strtoupper($protocolo->emailremetente).'</b></p>
		<p>TELEFONE: <b>'.strtoupper($protocolo->telefoneremetente).'</b></p>
		<br/>	
		<!--<h1>RESUMO DO PEDIDO</h1>-->
	';
	
	include 'lib/mpdf/8.0/vendor/autoload.php';
	$mpdf = new \Mpdf\Mpdf();
	$mpdf->mirrorMargins = 1;
	$mpdf->SetProtection(array('print'), '', 'MyPassword');					
	//$stylesheet = file_get_contents('estilos.css');
	//$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($html);
	$filename = Session::Get("PATH_CURRENT_PROJECT") . 'arquivos/capa/capa-'.$protocolo->id . '.pdf';
	$mpdf->Output( $filename ,'F');	
	
	header('Location: ' . $filename);
	exit;