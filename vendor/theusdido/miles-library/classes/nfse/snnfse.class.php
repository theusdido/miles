<?php
    #require 'vendor/autoload.php';
    use \RobRichards\XMLSecLibs\XMLSecurityKey;
    use \RobRichards\XMLSecLibs\XMLSecurityDSig;
    #var_dump('/vendor/robrichards/xmlseclibs/src/XMLSecurityKey.php');
    #var_dump(file_exists('vendor/robrichards/xmlseclibs/xmlseclibs.php'));
    #exit;

    require 'vendor/robrichards/xmlseclibs/xmlseclibs.php';
    #require 'vendor/robrichards/xmlseclibs/src/XMLSecurityDSig.php';
    #require 'vendor/autoload.php';
    #$objDSig = new XMLSecurityDSig(NULL);
    #exit;

    #require 'vendor/robrichards/xmlseclibs/src/XMLSecurityKey.php';
    #require 'vendor/robrichards/xmlseclibs/src/XMLSecurityDSig.php';
    
    #require __DIR__ . '/vendor/robrichards/xmlseclibs/src/XMLSecurityKey.php';
    #require __DIR__ . '/vendor/robrichards/xmlseclibs/src/XMLSecurityDSig.php';    
    

    /*
        * Framework MILES
        * @license : Teia Tecnologia WEB.
        * @link http://www.teia.tec.br

        * Classe snNFSE
        * Data de Criacao: 07/12/2025
        * Author: @Theusdido
        * Sistema Nacional de NFS-e
    */
    class snNFSE {

        public string $signedXmlPath    = 'lote_dps.xml';
        public string $endpoint         = '';
        public ?string $wsdl            = null;
        public ?string $clientCert      = __DIR__ . '/chave_privada.pem';
        public string $publicCert       = __DIR__ . '/certificado_publico.pem';
        public ?string $clientCertPass  = 'goes1234';
        private array $lote_rps         = [];
        private int $rps_lote_id        = 0;
        private string $xmlns           = 'http://www.sped.fazenda.gov.br/nfse';
        private string $soapAction      = '';

        public function sendSignedLoteRps() : array {

            // leitura do XML assinado
            if (!file_exists($this->signedXmlPath)) {
                throw new RuntimeException("Arquivo XML assinado não encontrado: $this->signedXmlPath");
            }

            // 1. Carregue o XML da DPS que você gerou conforme o XSD
            $xmlContent = file_get_contents($this->signedXmlPath);
            $dom = new DOMDocument();
            $dom->loadXML($xmlContent);

            // 2. Assinar o XML (Você pode usar bibliotecas como robrichards/xmlseclibs para facilitar)
            // O manual exige que a tag <infDPS> seja assinada e referenciada pelo atributo ID.
            // A estrutura final deve conter a tag <Signature> dentro da DPS.

            // Exemplo de estrutura esperada conforme manual:
            // <DPS xmlns="...">
            //    <infDPS Id="ID...">...</infDPS>
            //    <Signature>...</Signature>
            // </DPS>

            $xmlAssinado = $dom->saveXML();            

            // Monta o payload JSON
            $dados = [
                // Estrutura depende do endpoint específico (ex: POST /nfse/{chave}/eventos)
                // Geralmente envolve envelopar o XML
                "dpsXmlGZipB64" => $this->prepararXmlNfse($xmlAssinado) 
                // Consulte o Swagger específico para o nome exato do campo no JSON
            ];

            $payloadJson = json_encode($dados);

            $endpoint = "https://sefin.nfse.gov.br/sefinnacional/nfse"; // endpoint
            #$endpoint = "https://sefin.nfse.gov.br/sefinnacional"; // endpoint
            #$endpoint = "https://sefin.producaorestrita.nfse.gov.br/API/SefinNacional/docs/index";
            
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Configuração do Certificado Digital (Autenticação Mútua)
            #curl_setopt($ch, CURLOPT_SSLCERT, $this->clientCert);
            #curl_setopt($ch, CURLOPT_SSLKEY, '/caminho/para/chave_privada.pem');
            // Se a chave privada tiver senha:
            // curl_setopt($ch, CURLOPT_KEYPASSWD, 'senha_da_chave');

            if ($this->clientCert) {
                // Se você tem PEM com chave (cert+key), use CURLOPT_SSLCERT
                // Se tiver PFX, use CURLOPT_SSLCERTTYPE = 'P12' e CURLOPT_SSLCERT = caminho.pfx (nem sempre funciona dependendo do build)
                $ext = pathinfo($this->clientCert, PATHINFO_EXTENSION);
                if (in_array(strtolower($ext), ['pfx','p12'])) {
                    curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12');
                    curl_setopt($ch, CURLOPT_SSLCERT, $this->clientCert);
                    if ($this->clientCertPass) curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->clientCertPass);
                } else {
                    // espera PEM (cert+key) ou cert separado
                    curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
                    curl_setopt($ch, CURLOPT_SSLCERT, $this->clientCert);
                    // se chave estiver separada:
                    // curl_setopt($ch, CURLOPT_SSLKEY, '/caminho/chave_privada.pem');
                    // curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $clientCertPass);
                }
            }

            // Cabeçalhos Obrigatórios
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'Content-Length: ' . strlen($payloadJson)
            ]);

            $response   = curl_exec($ch);
            $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $retorno['status'] = 'success';
            $retorno['curl_error'] = '';
            $retorno['http_code'] = $httpCode;
            $retorno['response'] = $response;
            if (curl_errno($ch)) {
                $retorno['status'] = 'error';
                $retorno['curl_error'] = curl_error($ch);
            }

            curl_close($ch);
            return $retorno;
        }        

        private function createLote(){
            $where = ' AND a.rpsnumero IN (' . implode(',',$this->lote_rps) . ') ';
            $sql = "
                SELECT *
                FROM td_erp_nfse_nota a
                LEFT JOIN td_erp_nfse_servico b ON b.nfse = a.id
                LEFT JOIN td_erp_nfse_item c ON c.nfse = a.id
                LEFT JOIN td_erp_nfse_tomador d ON d.nfse = a.id
                WHERE (a.inativo <> 1 OR a.inativo IS NULL)
                $where;
            ";

            global $conn;
            $dataset = $conn->query($sql);
            $resultset = $dataset->fetchAll(PDO::FETCH_ASSOC);	

            $this->rps_lote_id = getProxId('td_erp_nfse_lote',$conn);
            //var_dump($resultset);
            $rps_numero = $resultset[0]['rpsnumero'];
            #$dps_id = "DPS" . $this->rps_lote_id;
            // Mun(7) + Amb(1) + Tipo(1) + CNPJ(14) + Serie(5) + Num(15)
            $dps_id = "DPS" . "4204608" . "2" . "83248021000158" . str_pad("1", 5, "0", STR_PAD_LEFT) . str_pad($rps_numero, 15, "0", STR_PAD_LEFT);
            
	        $sql = "INSERT INTO td_erp_nfse_lote (id,data_envio) VALUES ($this->rps_lote_id,NOW());";
            $dataset = $conn->exec($sql);

            // InfoDPS
            // $infDPSXML = '<NFSe xmlns="http://www.sped.fazenda.gov.br/nfse" versao="1.00"><infNFSe Id="'.$dps_id.'"><DPS xmlns="http://www.sped.fazenda.gov.br/nfse"><infDPS Id="'.$dps_id.'" versao="1.00">';
            // foreach($resultset as $key => $value){
            //     $infDPSXML .= $this->inline($this->layoutRPSXML($value));
            // }
            // $infDPSXML .= '</infDPS></DPS></infNFSe></NFSe>';

            $infDPSXML = "<DPS xmlns='http://www.sped.fazenda.gov.br/nfse' versao='1.00'>";
            $infDPSXML .= "<infDPS Id='{$dps_id}'>";
            
            foreach($resultset as $key => $value){
                $infDPSXML .= $this->inline($this->layoutRPSXML($value));
            }
            $infDPSXML .= '</infDPS>';
            $infDPSXML .= '</DPS>';
            
            // Assina o XML e obtém a string da assinatura
            $assinaturaXML = $this->assinatura($dps_id, $this->inline($infDPSXML));

            // Grava o DPS em XML
            $fp = fopen($this->signedXmlPath,'w');
            fwrite($fp,$this->inline('<?xml version="1.0" encoding="UTF-8"?>'));
            #fwrite($fp,$this->inline('<DPS xmlns="http://www.sped.fazenda.gov.br/nfse" versao="1.00">'));
            #fwrite($fp,$this->inline($infDPSXML));
            fwrite($fp,$this->inline($assinaturaXML)); // Usa a string da assinatura
            #fwrite($fp,$this->inline('</DPS>'));
            fclose($fp);
        }

        private function assinatura($id, $xmlContent){

            $xml = new DOMDocument();
            $xml->loadXML($xmlContent);

            #$elemento = $xml->getElementsByTagName('NFSe')->item(0); 
            $elemento = $xml->getElementsByTagName('DPS')->item(0);
            $info_dps = $xml->getElementsByTagName('infDPS')->item(0);
            //$dps = 
            #$info_dps = $xml->getElementsByTagName('infDPS')->item(0); 

            // Cria o objeto de assinatura
            $objDSig = new XMLSecurityDSig(NULL);
            
            $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);            
            $objDSig->addReference(
                $info_dps,
                XMLSecurityDSig::SHA1,
                ['http://www.w3.org/2000/09/xmldsig#enveloped-signature', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315'],
                ['uri' => '# ' . $id, 'overwrite' => false] // ID correspondente no XML
            );

            // Carrega a chave privada do certificado A1
            $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, ['type' => 'private']);
            $objKey->loadKey($this->clientCert, true, false, 'goes1234');

            // Assina o XML
            $objDSig->sign($objKey);

            // Anexa o certificado público à assinatura
            $objDSig->add509Cert(file_get_contents($this->publicCert), true, false, ['subjectName' => false]);

            // Insere a assinatura no XML
            $objDSig->appendSignature($elemento);
            #$objDSig->appendSignature($dps);
            return $xml->saveXML($elemento); // Retorna a assinatura como string
        }

        public function send(){
            $response   = array();
            $msgs       = '';
            $status     = '';            
            try {    
                $this->createLote();
                $resp = $this->sendSignedLoteRps();                
                $resp_json = json_decode($resp['response'], true); // Decode as associative array for easier access
                
                switch($resp['http_code']){
                    case 400:                        
                        if (isset($resp_json['erros']) && is_array($resp_json['erros'])) {
                            foreach($resp_json['erros'] as $error){
                                $msgs .= "ERRO: " . $error['Codigo'] . " | " . $error['Descricao'] . " - " . (isset($error['Complemento']) ?? '') . PHP_EOL;
                            }
                        } else {
                            $msgs .= "ERRO HTTP 400: Resposta inesperada ou vazia. Raw response: " . ($resp['response'] ?? 'N/A') . PHP_EOL;
                        }
                        $status = 'error';
                    break;
                    case 200:
                        if (!empty($resp_json)) {
                            $msgs .= 'NFSe enviada com Sucesso!' . PHP_EOL;
                            $msgs .= 'Detalhes da Resposta: ' . json_encode($resp_json, JSON_PRETTY_PRINT) . PHP_EOL;
                            $status = 'success';
                        } else {
                            $msgs .= 'Alerta: NFSe enviada, mas a resposta está vazia.' . PHP_EOL;
                            $status = 'warning'; // Using 'warning' for partial success/empty but non-error response
                        }                                           
                    break;
                    default:
                        $msgs .= "ERRO HTTP {$resp['http_code']}: Resposta inesperada. Raw response: " . ($resp['response'] ?? 'N/A') . PHP_EOL;
                        $status = 'error';
                    break;
                }
                
                if (!empty($resp['curl_error'])) {
                    $msgs .= "ERRO cURL: " . $resp['curl_error'] . PHP_EOL;
                    $status = 'error';
                }
            } catch (Exception $e) {
                $status = 'error';
                $msgs .= "ERRO na execução: " . $e->getMessage() . PHP_EOL;
            } finally {
                $response['message'] = $msgs;
                $response['status'] = $status;

                $situacao_envio = ($status == 'success') ? 'E' : 'N';
                $sql_situacao = "UPDATE td_erp_nfse_nota SET situacao = '{$situacao_envio}' WHERE rpsnumero IN (" . implode(',',$this->lote_rps) . ");";
                global $conn;
                $conn->exec($sql_situacao);
            }   
            
            return $response;
        }

        public function addLoteRPS(array $lote_rps){
            $this->lote_rps = $lote_rps;
        }

        private function inline($conteudo){
            $_conteudo = preg_replace('/>\s+</', '><', $conteudo); // Remove espaços entre tags
            $_conteudo = preg_replace('/\s+/', ' ', $_conteudo); // Reduz múltiplos espaços a um só
            $_conteudo = trim($_conteudo); // Remove espaços no início/fim
		    return $_conteudo;                          
	    }

        private function layoutRPSXML($value){
            #$rps_numero = str_pad($value['rpsnumero'], 15, "0", STR_PAD_LEFT);
            $rps_numero = $value['rpsnumero'];
            #$rps_serie = $value['rpsserie'];
            $rps_serie = str_pad("1", 5, "0", STR_PAD_LEFT);
            
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
                $documento_tomador = '<CNPJ>'.$cnpj_tomador.'</CNPJ>';
            }else{
                $documento_tomador = '<CPF>'.$cpf_tomador.'</CPF>';
            }

            $razao_social_tomador  = $value['tomarazaosocial'];
            $endereco_tomador = $value['tomaendereco'];
            $numero_tomador = $value['tomanumero'];
            $numero_tomador = $numero_tomador == '' ? 'S/N' : $numero_tomador;
            $complemento_tomador = $value['tomacomplemento'] == '' ? '' : '<xCpl>'.$value['tomacomplemento'].'</xCpl>';
            $bairro_tomador = $value['tomabairro'];

            $codigo_municipio_tomador = $value['tomacmun'];
            
                        
            $uf_tomador = $value['tomauf'];
            $cep_tomador = $value['tomacep'];
            $email_tomador = $value['tomaemail'];

            $responsavel_retencao = $value['respretencao'];
            $responsavel_retencao = '<ResponsavelRetencao>2</ResponsavelRetencao>';
            $responsavel_retencao = '';
                    // <fone>4834372552</fone>
                    // <email>FINANCAS@GOESIMOVEIS.COM.BR</email>

            return '
                <tpAmb>1</tpAmb>
                <dhEmi>'.$data_emissao.'T00:00:00-03:00</dhEmi>
                <verAplic>1.0.0</verAplic>
                <serie>'.$rps_serie.'</serie>
                <nDPS>'.$rps_numero.'</nDPS>
                <dCompet>'.$data_competencia.'</dCompet>
                <tpEmit>'.$rps_tipo.'</tpEmit>
                <cLocEmi>4204608</cLocEmi>
                <prest>
                    <CNPJ>83248021000158</CNPJ>
                    <regTrib>
                        <opSimpNac>3</opSimpNac>
                        <regApTribSN>1</regApTribSN>
                        <regEspTrib>0</regEspTrib>
                    </regTrib>
                </prest>
                <toma>
                    '.$documento_tomador.'
                    <xNome>'.$razao_social_tomador.'</xNome>
                    <end>
                        <endNac>
                            <cMun>'.$codigo_municipio_tomador.'</cMun>
                            <CEP>'.$cep_tomador.'</CEP>
                        </endNac>
                        <xLgr>'.$endereco_tomador.'</xLgr>
                        <nro>'.$numero_tomador.'</nro>
                        '.$complemento_tomador.'
                        <xBairro>'.$bairro_tomador.'</xBairro>
                    </end>
                </toma>
                <serv>
                    <locPrest>
                        <cLocPrestacao>4204608</cLocPrestacao>
                    </locPrest>
                    <cServ>
                        <cTribNac>100501</cTribNac>
                        <xDescServ>PRESTACAO DE SERVICOS - COMISSAO NOVEMBRO/2025. CONTA: CEF - Caixa Econômica Federal Agência 0415 - conta 1433-9 GOES EMPREEND. IMOB., JA POSSUI CADASTRO NO CEPOM DE CURITIBA.</xDescServ>
                    </cServ>
                </serv>
                <valores>
                    <vServPrest>
                        <vServ>'.$valor_servico.'</vServ>
                    </vServPrest>
                    <trib>
                        <tribMun>
                            <tribISSQN>1</tribISSQN>
                            <tpRetISSQN>2</tpRetISSQN>
                        </tribMun>
                        <tribFed>
                            <piscofins>
                                <CST>01</CST>
                                <vBCPisCofins>'.$valor_base_calculo.'</vBCPisCofins>
                            </piscofins>
                            <vRetCP>0.00</vRetCP>
                            <vRetIRRF>0.00</vRetIRRF>
                            <vRetCSLL>0.00</vRetCSLL>                 
                        </tribFed> 
                        <totTrib>
                            <indTotTrib>0</indTotTrib>
                        </totTrib>
                    </trib>
                </valores>
            ';

                    // <vBC>'.$valor_base_calculo.'</vBC>
                    // <vISSQN>'.$valor_iss.'</vISSQN>
                    // <vLiq>'.$valor_aliquota.'</vLiq>            
        }

        public function consultaPorRPS($rpsnumero){
            $response   = array();
            $msgs       = '';
            $status     = '';
            $nfse       = array(
                "nfsenumero"    => '-',
                "rpsnumero"     => $rpsnumero,
                "rpsserie"      => '-',
                "rpstipo"       => '-',
                "situacao"      => 'N',
                "tomador"       => ''
            );

            try {

                $resp = $this->querySignedRps($rpsnumero);
                if (!empty($resp['bodyNodes'])) {
                    foreach ($resp['bodyNodes'] as $k => $v) {
                        $respXML = new SimpleXMLElement($v);
                        //var_dump($respXML);
                        

                        $is_exist_nfse = isset($respXML->CompNfse);
                        
                        $message_error = '';
                        $status = 'success';

                        if (!$is_exist_nfse){
                            $respNFSE = $respXML->return->ConsultarNfseRpsResposta->ListaMensagemRetorno;
                                                        
                            $message_retorno    = $respNFSE->MensagemRetorno;
                            $codigo_error       = $message_retorno->Codigo;
                            $descricao_error    = $message_retorno->Mensagem;
                            $message_error      = "Erro: $codigo_error - $descricao_error";
                            $status             = 'error';

                            $nfse = array(
                                "nfsenumero"    => '-',
                                "rpsnumero"     => $rpsnumero,
                                "rpsserie"      => '-',
                                "rpstipo"       => '-',
                                "situacao"      => 'N',
                                "tomador"       => ''
                            );
                        }else{
                            $nfse = array(
                                "nfsenumero"    => (string)$respXML->CompNfse->Nfse->InfNfse->Numero,
                                "rpsnumero"     => (string)$respXML->CompNfse->Nfse->InfNfse->IdentificacaoRps->Numero,
                                "rpsserie"      => (string)$respXML->CompNfse->Nfse->InfNfse->IdentificacaoRps->Serie,
                                "rpstipo"       => (string)$respXML->CompNfse->Nfse->InfNfse->IdentificacaoRps->Tipo,
                                "situacao"      => 'G',
                                "tomador"       => (string)$respXML->CompNfse->Nfse->InfNfse->TomadorServico->RazaoSocial
                            );
                        }

                        $msgs .= $message_error;
                    }
                } else {
                    $msgs .= $resp['raw'];
                    $status = 'error';
                }
            } catch (Exception $e) {
                $msgs .= "ERRO: " . $e->getMessage() . PHP_EOL;
                $status = 'error';
            }finally{
                $response['message']    = $msgs;
                $response['status']     = $status;
                $response['data']       = $nfse;
            }
            return $response;
        }

        private function querySignedRps(
            $rpsnumero
        ) : array {
            $this->endpoint     = 'https://nota-eletronica.betha.cloud/rps/ws/consultarNfsePorRps';
            $servico            = 'ConsultarNfseRpsEnvio';

            $soapEnvelope = '
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:e="'.$this->serviceNs.'">
            <soapenv:Header/>
            <soapenv:Body>
                <e:'.$servico.'>
                    <IdentificacaoRps>
                        <Numero>'.$rpsnumero.'</Numero>
                        <Serie>U</Serie>
                        <Tipo>1</Tipo>
                    </IdentificacaoRps>
                    <Prestador>
                        <Cnpj>83248021000158</Cnpj>
                        <InscricaoMunicipal>1169</InscricaoMunicipal>
                    </Prestador>                    
                </e:'.$servico.'>
            </soapenv:Body>
            </soapenv:Envelope>
            ';

            $soapEnvelope = preg_replace('/>\s+</', '><', $soapEnvelope); // Remove espaços entre tags
            $soapEnvelope = preg_replace('/\s+/', ' ', $soapEnvelope);    // Reduz múltiplos espaços a um só
            $soapEnvelope = trim($soapEnvelope);                          // Remove espaços no início/fim

            // --- Opções de contexto SSL (para usar certificado A1 no handshake TLS, se necessário) ---
            $streamOptions = [];
            if ($this->clientCert) {
                // PHP aceita local_cert em PEM. Se você tem um PFX, converta para PEM (chave + cert) ou aponte direto para PFX em alguns builds.
                // Recomendado: converta .pfx para .pem (cert + key) usando openssl antes:
                // openssl pkcs12 -in certificado.pfx -out cert_and_key.pem -nodes
                // Em seguida use cert_and_key.pem aqui.
                $streamOptions['ssl'] = [
                    'verify_peer'       => true,
                    'verify_peer_name'  => true,
                    'allow_self_signed' => false,
                    'cafile'            => '/etc/ssl/certs/ca-certificates.crt', // ou null se CA do servidor for válida
                    'local_cert'        => $this->clientCert,
                ];
                if ($this->clientCertPass) {
                    $streamOptions['ssl']['passphrase'] = $this->clientCertPass;
                }
            }

            $context = stream_context_create($streamOptions);

            // --- Cabeçalhos HTTP customizados ---
            $headers = [
                'Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($soapEnvelope)
            ];
            if (!empty($this->soapAction)) {
                $headers[] = 'SOAPAction: "' . $this->soapAction . '"';
            }

            // --- Use __doRequest para enviar raw SOAP (mais controle que SoapClient) ---
            // Mas para usar stream_context com TLS client cert, vamos criar SoapClient se precisar analisar WSDL,
            // caso contrário usamos curl para maior controle.
            if ($this->wsdl) {
                // opção: usar SoapClient com contexto customizado
                $options = [
                    'trace' => 1,
                    'exceptions' => 1,
                    'stream_context' => $context,
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    // 'local_cert' não funciona diretamente aqui para todos os setups; preferimos stream_context.
                ];
                $client = new SoapClient($this->wsdl, $options);
                try {
                    // se serviço espera operação chamada 'EnviarLoteRps', troque adequadamente
                    // Aqui chamamos __doRequest diretamente para inserir envelope pronto
                    $responseXml = $client->__doRequest($soapEnvelope, $this->endpoint, $this->soapAction ?: 'RecepcionarLoteRpsEnvio', SOAP_1_1);
                } catch (SoapFault $e) {
                    throw new RuntimeException("Erro SOAP: {$e->getMessage()} (faultcode={$e->faultcode})");
                }
            } else {
                // Sem WSDL: use cURL (mais robusto para TLS client cert e headers)
                $ch = curl_init($this->endpoint);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $soapEnvelope);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOPT_CAINFO, '/etc/ssl/certs/ca-certificates.crt'); // ajustar se necessário

                if ($this->clientCert) {
                    // Se você tem PEM com chave (cert+key), use CURLOPT_SSLCERT
                    // Se tiver PFX, use CURLOPT_SSLCERTTYPE = 'P12' e CURLOPT_SSLCERT = caminho.pfx (nem sempre funciona dependendo do build)
                    $ext = pathinfo($this->clientCert, PATHINFO_EXTENSION);
                    if (in_array(strtolower($ext), ['pfx','p12'])) {
                        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12');
                        curl_setopt($ch, CURLOPT_SSLCERT, $this->clientCert);
                        if ($this->clientCertPass) curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->clientCertPass);
                    } else {
                        // espera PEM (cert+key) ou cert separado
                        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
                        curl_setopt($ch, CURLOPT_SSLCERT, $this->clientCert);
                        // se chave estiver separada:
                        // curl_setopt($ch, CURLOPT_SSLKEY, '/caminho/chave_privada.pem');
                        // curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $clientCertPass);
                    }
                }

                // aplica stream context se existia (não obrigatório para curl)
                $responseXml = curl_exec($ch);
                if ($responseXml === false) {
                    $err = curl_error($ch);
                    $code = curl_errno($ch);
                    curl_close($ch);
                    throw new RuntimeException("cURL error ($code): $err");
                }
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode >= 400) {
                    throw new RuntimeException("HTTP error ao enviar: código $httpCode. Resposta: " . substr($responseXml,0,2000));
                }
            }

            // Retorna raw response e tenta parsear XML body
            $result = ['raw' => $responseXml];

            // tenta extrair Body/EnviarLoteRpsResposta etc.
            libxml_use_internal_errors(true);
            $doc = new DOMDocument();
            if (@$doc->loadXML($responseXml)) {
                $xpath = new DOMXPath($doc);
                $xpath->registerNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
                // tenta extrair qualquer tag de resposta no body (flexível)
                $nodes = $xpath->query('//soap:Body/*');
                if ($nodes->length) {
                    $resultNodes = [];
                    foreach ($nodes as $n) {
                        $resultNodes[$n->localName] = $doc->saveXML($n);
                    }
                    $result['bodyNodes'] = $resultNodes;
                } else {
                    $result['body'] = $doc->saveXML();
                }
            } else {
                $result['parseError'] = true;
            }

            return $result;
        }        

        public function cancelar($nfsenumero,$codigocancelamento){

            $response   = array();
            $msgs       = '';
            $status     = '';
            $nfse       = array(
                "nfsenumero"    => '-',
                "rpsnumero"     => $nfsenumero,
                "rpsserie"      => '-',
                "rpstipo"       => '-',
                "situacao"      => 'N',
                "tomador"       => ''
            );

            try {

                $resp = $this->cancelSigned($nfsenumero,$codigocancelamento);
                if (!empty($resp['bodyNodes'])) {
                    foreach ($resp['bodyNodes'] as $k => $v) {
                        $respXML = new SimpleXMLElement($v);
                        $is_exist_nfse = isset($respXML->CompNfse);
                        
                        $message_error = '';
                        $status = 'success';

                        if (!$is_exist_nfse){
                            $respNFSE = $respXML->return->CancelarNfseReposta->ListaMensagemRetorno;
                                                        
                            $message_retorno    = $respNFSE->MensagemRetorno;
                            $codigo_error       = $message_retorno->Codigo;
                            $descricao_error    = $message_retorno->Mensagem;
                            $message_error      = "Erro: $codigo_error - $descricao_error";
                            $status             = 'error';

                            $nfse = array(
                                "nfsenumero"    => '-',
                                "rpsnumero"     => $rpsnumero,
                                "rpsserie"      => '-',
                                "rpstipo"       => '-',
                                "situacao"      => 'N',
                                "tomador"       => ''
                            );
                        }else{
                            $nfse = array(
                                "nfsenumero"    => (string)$respXML->CompNfse->Nfse->InfNfse->Numero,
                                "rpsnumero"     => (string)$respXML->CompNfse->Nfse->InfNfse->IdentificacaoRps->Numero,
                                "rpsserie"      => (string)$respXML->CompNfse->Nfse->InfNfse->IdentificacaoRps->Serie,
                                "rpstipo"       => (string)$respXML->CompNfse->Nfse->InfNfse->IdentificacaoRps->Tipo,
                                "situacao"      => 'G',
                                "tomador"       => (string)$respXML->CompNfse->Nfse->InfNfse->TomadorServico->RazaoSocial
                            );
                        }

                        $msgs .= $message_error;
                    }
                } else {
                    $msgs .= $resp['raw'];
                    $status = 'error';
                }
            } catch (Exception $e) {
                $msgs .= "ERRO: " . $e->getMessage() . PHP_EOL;
                $status = 'error';
            }finally{
                $response['message']    = $msgs;
                $response['status']     = $status;
                $response['data']       = $nfse;
            }
            return $response;

            
        }

        private function cancelSigned(
            $nfsenumero,
            $codigocancelamento
        ) : array {
            $this->endpoint     =  'https://nota-eletronica.betha.cloud/rps/ws/cancelarNfse';
            $servico            = 'CancelarNfseEnvio';

            $soapEnvelope = '
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:e="'.$this->serviceNs.'">
            <soapenv:Header/>
            <soapenv:Body>
                <e:'.$servico.'>
                    <Pedido>
                        <InfPedidoCancelamento Id="1">
                            <IdentificacaoNfse>
                                <Numero>'.$nfsenumero.'</Numero>
                                <Cnpj>83248021000158</Cnpj>
                                <InscricaoMunicipal>1169</InscricaoMunicipal>
                                <CodigoMunicipio>4204608</CodigoMunicipio>
                            </IdentificacaoNfse>
                            <CodigoCancelamento>'.$codigocancelamento.'</CodigoCancelamento>
                        </InfPedidoCancelamento>
                        '.$this->assinatura().'
                    </Pedido>
                </e:'.$servico.'>
            </soapenv:Body>
            </soapenv:Envelope>
            ';

            $soapEnvelope = preg_replace('/>\s+</', '><', $soapEnvelope); // Remove espaços entre tags
            $soapEnvelope = preg_replace('/\s+/', ' ', $soapEnvelope);    // Reduz múltiplos espaços a um só
            $soapEnvelope = trim($soapEnvelope);                          // Remove espaços no início/fim

            // --- Opções de contexto SSL (para usar certificado A1 no handshake TLS, se necessário) ---
            $streamOptions = [];
            if ($this->clientCert) {
                // PHP aceita local_cert em PEM. Se você tem um PFX, converta para PEM (chave + cert) ou aponte direto para PFX em alguns builds.
                // Recomendado: converta .pfx para .pem (cert + key) usando openssl antes:
                // openssl pkcs12 -in certificado.pfx -out cert_and_key.pem -nodes
                // Em seguida use cert_and_key.pem aqui.
                $streamOptions['ssl'] = [
                    'verify_peer'       => true,
                    'verify_peer_name'  => true,
                    'allow_self_signed' => false,
                    'cafile'            => '/etc/ssl/certs/ca-certificates.crt', // ou null se CA do servidor for válida
                    'local_cert'        => $this->clientCert,
                ];
                if ($this->clientCertPass) {
                    $streamOptions['ssl']['passphrase'] = $this->clientCertPass;
                }
            }

            $context = stream_context_create($streamOptions);

            // --- Cabeçalhos HTTP customizados ---
            $headers = [
                'Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($soapEnvelope)
            ];
            if (!empty($this->soapAction)) {
                $headers[] = 'SOAPAction: "' . $this->soapAction . '"';
            }

            // --- Use __doRequest para enviar raw SOAP (mais controle que SoapClient) ---
            // Mas para usar stream_context com TLS client cert, vamos criar SoapClient se precisar analisar WSDL,
            // caso contrário usamos curl para maior controle.
            if ($this->wsdl) {
                // opção: usar SoapClient com contexto customizado
                $options = [
                    'trace' => 1,
                    'exceptions' => 1,
                    'stream_context' => $context,
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    // 'local_cert' não funciona diretamente aqui para todos os setups; preferimos stream_context.
                ];
                $client = new SoapClient($this->wsdl, $options);
                try {
                    // se serviço espera operação chamada 'EnviarLoteRps', troque adequadamente
                    // Aqui chamamos __doRequest diretamente para inserir envelope pronto
                    $responseXml = $client->__doRequest($soapEnvelope, $this->endpoint, $this->soapAction ?: 'RecepcionarLoteRpsEnvio', SOAP_1_1);
                } catch (SoapFault $e) {
                    throw new RuntimeException("Erro SOAP: {$e->getMessage()} (faultcode={$e->faultcode})");
                }
            } else {
                // Sem WSDL: use cURL (mais robusto para TLS client cert e headers)
                $ch = curl_init($this->endpoint);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $soapEnvelope);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOPT_CAINFO, '/etc/ssl/certs/ca-certificates.crt'); // ajustar se necessário

                if ($this->clientCert) {
                    // Se você tem PEM com chave (cert+key), use CURLOPT_SSLCERT
                    // Se tiver PFX, use CURLOPT_SSLCERTTYPE = 'P12' e CURLOPT_SSLCERT = caminho.pfx (nem sempre funciona dependendo do build)
                    $ext = pathinfo($this->clientCert, PATHINFO_EXTENSION);
                    if (in_array(strtolower($ext), ['pfx','p12'])) {
                        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12');
                        curl_setopt($ch, CURLOPT_SSLCERT, $this->clientCert);
                        if ($this->clientCertPass) curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->clientCertPass);
                    } else {
                        // espera PEM (cert+key) ou cert separado
                        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
                        curl_setopt($ch, CURLOPT_SSLCERT, $this->clientCert);
                        // se chave estiver separada:
                        // curl_setopt($ch, CURLOPT_SSLKEY, '/caminho/chave_privada.pem');
                        // curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $clientCertPass);
                    }
                }

                // aplica stream context se existia (não obrigatório para curl)
                $responseXml = curl_exec($ch);
                if ($responseXml === false) {
                    $err = curl_error($ch);
                    $code = curl_errno($ch);
                    curl_close($ch);
                    throw new RuntimeException("cURL error ($code): $err");
                }
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode >= 400) {
                    throw new RuntimeException("HTTP error ao enviar: código $httpCode. Resposta: " . substr($responseXml,0,2000));
                }
            }

            //var_dump($responseXml);
            // Retorna raw response e tenta parsear XML body
            $result = ['raw' => $responseXml];

            // tenta extrair Body/EnviarLoteRpsResposta etc.
            libxml_use_internal_errors(true);
            $doc = new DOMDocument();
            if (@$doc->loadXML($responseXml)) {
                $xpath = new DOMXPath($doc);
                $xpath->registerNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
                // tenta extrair qualquer tag de resposta no body (flexível)
                $nodes = $xpath->query('//soap:Body/*');
                if ($nodes->length) {
                    $resultNodes = [];
                    foreach ($nodes as $n) {
                        $resultNodes[$n->localName] = $doc->saveXML($n);
                    }
                    $result['bodyNodes'] = $resultNodes;
                } else {
                    $result['body'] = $doc->saveXML();
                }
            } else {
                $result['parseError'] = true;
            }

            return $result;
        }   
        
        private function prepararXmlNfse($xmlContent) {
            // 1. Garante que o XML esteja limpo de espaços em branco desnecessários (opcional, mas recomendado)
            $xmlContent = trim($xmlContent);

            // 2. Compacta o XML usando GZIP (Nível 9 para máxima compressão)
            // A função correta é gzencode, pois gera o cabeçalho GZIP esperado.
            $xmlGzip = gzencode($xmlContent, 9);

            if ($xmlGzip === false) {
                throw new Exception("Falha ao compactar o XML.");
            }

            // 3. Converte o binário compactado para Base64
            $xmlBase64 = base64_encode($xmlGzip);

            return $xmlBase64;
        }        
    }