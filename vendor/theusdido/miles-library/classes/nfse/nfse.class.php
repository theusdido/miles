<?php
    use RobRichards\XMLSecLibs\XMLSecurityKey;
    use RobRichards\XMLSecLibs\XMLSecurityDSig;

    /*
        * Framework MILES
        * @license : Teia Tecnologia WEB.
        * @link http://www.teia.tec.br

        * Classe NFSE
        * Data de Criacao: 27/08/2025
        * Author: @Theusdido
    */
    class NFSE {
        
        public string $signedXmlPath    = 'lote_rps.txt';
        public string $endpoint         = '';
        public ?string $wsdl            = null;
        public ?string $clientCert      = __DIR__ . '/chave_privada.pem';
        public ?string $clientCertPass  = 'goes1234';
        private array $lote_rps         = [];
        private int $rps_lote_id        = 0;
        private string $serviceNs       = 'http://www.betha.com.br/e-nota-contribuinte-ws';
        private string $soapAction      = '';

        public function sendSignedLoteRps() : array {
            // leitura do XML assinado
            if (!file_exists($this->signedXmlPath)) {
                throw new RuntimeException("Arquivo XML assinado não encontrado: $this->signedXmlPath");
            }
            $xml = file_get_contents($this->signedXmlPath);
            #// remove possível header XML (<?xml ... \?\>) para inserção no corpo
            $xml = preg_replace('/<\?xml.*?\?>\s*/i', '', $xml);

            $this->endpoint = 'https://nota-eletronica.betha.cloud/rps/ws/recepcionarLoteRps';
            $servico        = 'EnviarLoteRpsEnvio';

        $soapEnvelope = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:e="'.$this->serviceNs.'">
        <soapenv:Header/>
        <soapenv:Body>
            <e:'.$servico.'>
                <nfseCabecMsg>
                    <![CDATA[
                        <cabecalho xmlns="'.$this->serviceNs.'" versao="1.0"><versaoDados>1.0</versaoDados></cabecalho>
                    ]]>
                </nfseCabecMsg>
                <nfseDadosMsg>
                    <![CDATA[
                        '.$xml.'
                    ]]>
                </nfseDadosMsg>
            </e:'.$servico.'>
        </soapenv:Body>
        </soapenv:Envelope>
        ';
            $soapEnvelope = preg_replace('/>\s+</', '><', $soapEnvelope); // Remove espaços entre tags
            $soapEnvelope = preg_replace('/\s+/', ' ', $soapEnvelope);    // Reduz múltiplos espaços a um só
            $soapEnvelope = trim($soapEnvelope);                          // Remove espaços no início/fim

        //var_dump($soapEnvelope);
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
	        $sql = "INSERT INTO td_erp_nfse_lote (id,data_envio) VALUES ($this->rps_lote_id,NOW());";
            $dataset = $conn->exec($sql);

            $fp = fopen($this->signedXmlPath,'w');
            fwrite($fp,$this->inline('
                <?xml version="1.0"?>
                <EnviarLoteRpsEnvio xmlns="http://www.betha.com.br/e-nota-contribuinte-ws">
                    <LoteRps Id="'.$this->rps_lote_id.'" versao="2.02">
                        <NumeroLote>'.$this->rps_lote_id.'</NumeroLote>
                        <Cnpj>83248021000158</Cnpj>
                        <InscricaoMunicipal>1169</InscricaoMunicipal>
                        <QuantidadeRps>'.sizeof($resultset).'</QuantidadeRps>
                        <CodigoMunicipio>4204608</CodigoMunicipio>
                        <ListaRps>	
            '));

            foreach($resultset as $key => $value){
                fwrite($fp,$this->inline($this->layoutRPSXML($value)));
            }

            fwrite($fp,$this->inline('
                        </ListaRps>
                    </LoteRps>
                    '.$this->assinatura().'
                </EnviarLoteRpsEnvio>	
            '));

            fclose($fp);
        }


        private function assinatura(){
            return '
                <Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
                    <SignedInfo>
                        <CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        <SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                        <Reference URI="#pfx4d2e05bd-b4db-eb52-e4b1-bbebe6054a21">
                            <Transforms>
                                <Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/>
                                <Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
                            </Transforms>
                            <DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                            <DigestValue>deyFIPaUh2obYYTz0c+VSIWRvBc=</DigestValue>
                        </Reference>
                    </SignedInfo>
                    <SignatureValue>jFX2okWNM3SMfjQ1nWfFqb7wpakhB4hVawtnJkyvmb/Bqk40nuPBqbn7ArgwA62mBWtlPg1KDm7b4EQEpzwkmVvzYQew98Wh8qwFgdrWbE/O+8dEro34kBL8uIwYM9Xx6T5zsYmMAdE4TNCat91u582ya5t035CegT2N+WNZ1hwmGeXAhhg6oqufG6G9nu6B+gEJTF8FZUlaRcPGFAFA/NEKZMAKAn/Ro2/yjweunWrp7BsJpWbrsSERKEnk3vC+xqXfFo2QlS+GqDiNcr3RXH/cZnyLPucJlxv41NBg2EYWU9h+KBBoUG8eWp6JQI1u6VRyoCLwk+Qwmec9sTXRQg==</SignatureValue>
                    <KeyInfo>
                        <X509Data>
                            <X509Certificate>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</X509Certificate>
                        </X509Data>
                    </KeyInfo>
                </Signature>    
            ';
        }

        public function send(){
            $response   = array();
            $msgs       = '';
            $status     = '';            
            try {    
                $this->createLote();



                $resp = $this->sendSignedLoteRps();
                if (!empty($resp['bodyNodes'])) {
                    $ret = '';
                    foreach ($resp['bodyNodes'] as $k => $v) {
                        #echo "=== $k ===\n$v\n\n";
                        $ret .= $v;
                    }
                    #var_dump($ret);
                    // echo json_encode(array(
                    //     'message' => 'Enviado com Sucesso!'
                    // )); 
                    $msgs .= 'Enviado com Sucesso!';
                    $status = 'success';
                } else {
                    // echo json_encode(array(
                    //     'message' => $resp['raw']
                    // ));  
                    #$msgs .= $resp['raw'];
                    $msgs .= 'teste';
                    $status = 'error';
                }
            } catch (Exception $e) {
                $status = 'error';
                $msgs .= "ERRO: " . $e->getMessage() . PHP_EOL;
                #$msgs .= 'teste';
            } finally {
                $response['message'] = $msgs;
                $response['status'] = $status;

                $situacao_envio = $status == 'success' ? 'E' : 'N';
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

            $codigo_municipio_tomador = $value['tomacmun'];
            
                        
            $uf_tomador = $value['tomauf'];
            $cep_tomador = $value['tomacep'];
            $email_tomador = $value['tomaemail'];

            $responsavel_retencao = $value['respretencao'];
            $responsavel_retencao = '<ResponsavelRetencao>2</ResponsavelRetencao>';
            $responsavel_retencao = '';

            return '
                <Rps>
                    <InfRps Id="rps'.$this->rps_lote_id.'">
                        <IdentificacaoRps>
                            <Numero>'.$rps_numero.'</Numero>
                            <Serie>'.$rps_serie.'</Serie>
                            <Tipo>'.$rps_tipo.'</Tipo>
                        </IdentificacaoRps>
                        <DataEmissao>'.$data_emissao.'T00:00:00.000</DataEmissao>
                        <NaturezaOperacao>'.$natureza_operacao.'</NaturezaOperacao>
                        <OptanteSimplesNacional>1</OptanteSimplesNacional>
                        <IncentivadorCultural>1</IncentivadorCultural>
                        <Status>1</Status>
                        <Servico>
                            <Valores>
                                <ValorServicos>'.$valor_servico.'</ValorServicos>
                                <IssRetido>'.$iss_retido.'</IssRetido>
                                <ValorIss>'.$valor_iss.'</ValorIss>
                                <BaseCalculo>'.$valor_base_calculo.'</BaseCalculo>
                                <Aliquota>'.$valor_aliquota.'</Aliquota>						
                            </Valores>
                            <ItemListaServico>'.$item_servico.'</ItemListaServico>					
                            <Discriminacao>HONORARIO DE CONTRATO REF A ALUGUEL DE IMOVEL</Discriminacao>
                            <CodigoMunicipio>'.$codigo_municipio.'</CodigoMunicipio>
                        </Servico>
                        <Prestador>					
                            <Cnpj>83248021000158</Cnpj>
                            <InscricaoMunicipal>1169</InscricaoMunicipal>
                        </Prestador>
                        <Tomador>
                            <IdentificacaoTomador>
                                <CpfCnpj>
                                    '.$documento_tomador.'
                                </CpfCnpj>
                            </IdentificacaoTomador>
                            <RazaoSocial>'.$razao_social_tomador.'</RazaoSocial>
                            <Endereco>
                                <Endereco>'.$endereco_tomador.'</Endereco>
                                <Numero>'.$numero_tomador.'</Numero>
                                <Complemento>'.$complemento_tomador.'</Complemento>
                                <Bairro>'.$bairro_tomador.'</Bairro>
                                <CodigoMunicipio>'.$codigo_municipio_tomador.'</CodigoMunicipio>
                                <Uf>'.$uf_tomador.'</Uf>
                                <Cep>'.$cep_tomador.'</Cep>
                            </Endereco>
                            <Contato>
                                <Email>'.$email_tomador.'</Email>
                            </Contato>
                        </Tomador>
                        <OutrasInformacoes></OutrasInformacoes>
                    </InfRps>
                    '.$this->assinatura().'
                </Rps>
            ';
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
    }