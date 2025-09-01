<?php
	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

    #require 'conexao.php';
	require 'assinatura.php';

	function inline($conteudo){
        $_conteudo = preg_replace('/>\s+</', '><', $conteudo); // Remove espaços entre tags
        $_conteudo = preg_replace('/\s+/', ' ', $_conteudo); // Reduz múltiplos espaços a um só
        $_conteudo = trim($_conteudo); // Remove espaços no início/fim
		return $_conteudo;                          
	}

	// function getProxId($entidade,$conn = null){
	// 	if ($conn == null) global $conn;
	// 	$entidadeName 	= $entidade;

	// 	$sql 	= 'SELECT IFNULL(MAX(id),0) + 1 FROM ' . $entidadeName;
	// 	$query 	= $conn->query($sql);
	// 	if (!$query){
	// 		if (IS_SHOW_ERROR_MESSAGE){
	// 			echo $sql;
	// 			var_dump($conn->errorInfo());
	// 		}
	// 	}
	// 	$prox = $query->fetch(PDO::FETCH_BOTH);
	// 	return $prox[0];
	// }	

	$where = '';
	if (isset($_GET['rpsnumero']) && $_GET['rpsnumero'] != ''){
		$where = 'AND a.rpsnumero = '.$_GET['rpsnumero'];
	}

    $sql = "
        SELECT *
        FROM td_erp_nfse_nota a
        LEFT JOIN td_erp_nfse_servico b ON b.nfse = a.id
        LEFT JOIN td_erp_nfse_item c ON c.nfse = a.id
        LEFT JOIN td_erp_nfse_tomador d ON d.nfse = a.id
		WHERE (inativo <> 1 OR inativo NOT NULL)
		$where;
    ";

    $dataset = $conn->query($sql);
    $resultset = $dataset->fetchAll(PDO::FETCH_ASSOC);	

	$lote_id     = getProxId('td_erp_nfse_lote',$conn);
	#$lote_id = 2012098;
	
	#var_dump($lote_id);
	#exit;

	$sql = "INSERT INTO td_erp_nfse_lote (id,data_envio) VALUES ($lote_id,NOW());";
    $dataset = $conn->exec($sql);

	
    $rps_id = 1;
	#$loda_id = 2012103;

    $fp = fopen('lote_rps.txt','w');
	fwrite($fp,inline('
		<?xml version="1.0"?>
		<EnviarLoteRpsEnvio xmlns="http://www.betha.com.br/e-nota-contribuinte-ws">
			<LoteRps Id="'.$lote_id.'" versao="2.02">
				<NumeroLote>'.$lote_id.'</NumeroLote>
				<Cnpj>83248021000158</Cnpj>
				<InscricaoMunicipal>1169</InscricaoMunicipal>
				<QuantidadeRps>'.sizeof($resultset).'</QuantidadeRps>
				<CodigoMunicipio>4204608</CodigoMunicipio>
				<ListaRps>	
	'));
    foreach($resultset as $key => $value){

        $rps_numero = $value['rpsnumero'];
        $rps_serie = $value['rpsserie'];
        $rps_tipo   = $value['rpstipo'];
        $data_emissao = $value['demis'];
        $data_competencia = $value['dcompetencia'];

        $regime_tributario = $value['regesptrib'];
        $iss_retido = $value['issretido'];
		

        $valor_servico = $value['valservicos'];
        $valor_deducoes = $value['valdeducoes'];
        $valor_pis = $value['valpis'];
        $valor_confins = $value['valcofins'];
        $valor_inss = $value['valinss'];
        $valor_ir = $value['valir'];
        $valor_csll = $value['valcsll'];
        
        $valor_iss = $value['valiss'];
        

        $valor_aliquota = $value['valaliqiss'];
        $valor_desconto_incondicionado = $value['valdescincond'];
        $valor_desconto_condicionado = $value['valdesccond'];
		$valor_base_calculo = $value['valbasecalculo'];
        
        $item_servico = str_replace(".","",$value['itelistserv']);
        $codigo_incidencia_municipio = $value['cmunincidencia'];
        
		$codigo_municipio = $value['cmun'];
		$codigo_municipio = '4204608';

        $natureza_operacao = $value['natop'];
		$discriminacao = $value['discriminacao'];
		

        $cpf_tomador = $value['tomacpf'];
        $cnpj_tomador = $value['tomacnpj'];

        if ($cpf_tomador == ''){
            $documento_tomador = '<Cnpj>'.$cnpj_tomador.'</Cnpj>';
        }else{
            $documento_tomador = '<Cpf>'.$cpf_tomador.'</Cpf>';
        }

        $razao_social_tomador  = $value['tomarazaosocial'];
        $endereco_tomador = $value['tomaendereco'];
        $numero_tomador = $value['tomanumero'];
		$numero_tomador = $numero_tomador == '' ? 'S/N' : $numero_tomador;
        $complemento_tomador = $value['tomacomplemento'];
        $bairro_tomador = $value['tomabairro'];
		
		#var_dump($bairro_tomador);
		#exit;
        $codigo_municipio_tomador = $value['tomacmun'];
		
                    
        $uf_tomador = $value['tomauf'];
        $cep_tomador = $value['tomacep'];
        $email_tomador = $value['tomaemail'];

		$responsavel_retencao = $value['respretencao'];
		$responsavel_retencao = '<ResponsavelRetencao>2</ResponsavelRetencao>';
		$responsavel_retencao = '';
		
		#$exigibilidade_iss = 1;
		#if (($natureza_operacao  == 1 || $natureza_operacao == 2) && $valor_iss == 0){
		#	$exigibilidade_iss = 2;
		#}

		#$codigo_incidencia_municipio = $codigo_municipio;
		#$natureza_operacao = 1;
		#$iss_retido = 1;
		#var_dump($item_servico);
		#var_dump($codigo_municipio);
		#$valor_iss = 0;
		#var_dump($iss_retido);
		#var_dump($natureza_operacao);
		#var_dump($valor_iss);
		#exit;		

		include_once 'layout_envio_v1.php';

		#var_dump($rps);
		#exit;

        fwrite($fp,inline($rps));
    }

	fwrite($fp,inline('
				</ListaRps>
			</LoteRps>
			'.ASSINATURA.'
		</EnviarLoteRpsEnvio>	
	'));

    fclose($fp);