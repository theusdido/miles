<?php

    use \RobRichards\XMLSecLibs\XMLSecurityKey;
    use \RobRichards\XMLSecLibs\XMLSecurityDSig;

    require 'vendor/robrichards/xmlseclibs/xmlseclibs.php';    

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
        public string $endpoint         = 'https://sefin.producaorestrita.nfse.gov.br/API/SefinNacional/nfse';
        public ?string $wsdl            = null;
        
        // CORREÇÃO: Apontar para os arquivos PEM separados
        public ?string $privateKeyPath  = '/var/www/miles/vendor/theusdido/miles-library/controller/integracao/sn_nfse/chave_privada.pem';
        public string $publicCertPath   = '/var/www/miles/vendor/theusdido/miles-library/controller/integracao/sn_nfse/certificado_publico.pem';

        
        // A senha geralmente não é necessária para o PEM se ele já foi extraído sem senha
        // Se a chave privada PEM tiver senha, mantenha aqui.
        public ?string $clientCertPass  = 'goes1234'; 
        
        private array $lote_rps         = [];
        private int $rps_lote_id        = 0;
        private string $xmlns           = 'http://www.sped.fazenda.gov.br/nfse';
        private string $soapAction      = '';
        public string $cafile           = '/var/www/miles/vendor/theusdido/miles-library/controller/integracao/sn_nfse/ca-certificates.crt';

        public function sendSignedLoteRps() : array {

            // leitura do XML assinado
            if (!file_exists($this->signedXmlPath)) {
                throw new RuntimeException("Arquivo XML assinado não encontrado: $this->signedXmlPath");
            }

            // 1. Lê o conteúdo do arquivo
            $xmlContentRaw = file_get_contents($this->signedXmlPath);
            
            if (!$xmlContentRaw) {
                throw new RuntimeException("Arquivo XML vazio.");
            }

            // 2. Remove o cabeçalho apenas para o ENVIO (Payload JSON)
            $xmlAssinado = preg_replace('/<\?xml.*?\?>\s*/iu', '', $xmlContentRaw);                        
            $dados = [
                "dpsXmlGZipB64" => $this->prepararXmlNfse($xmlAssinado) 
            ];

            $payloadJson = json_encode($dados);

            $headers = [
                'Content-Type: application/json; charset=utf-8',
                'Accept: application/json',
                'Content-Length: ' . strlen($payloadJson)
            ];
            
            $ch = curl_init($this->endpoint);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, $this->cafile);

            // Configuração do Certificado de Cliente para Autenticação Mútua (mTLS)
            if ($this->publicCertPath && file_exists($this->publicCertPath)) {
                // Usa PEM pois temos os arquivos separados
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($ch, CURLOPT_SSLCERT, $this->publicCertPath);
                
                if ($this->privateKeyPath && file_exists($this->privateKeyPath)) {
                    curl_setopt($ch, CURLOPT_SSLKEY, $this->privateKeyPath);
                }
                
                if ($this->clientCertPass) {
                    curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $this->clientCertPass);
                }
            }

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
            $rps_numero = $resultset[0]['rpsnumero'];
            
            // Corrige o ID (Exemplo fixo, ajustar conforme lógica real de zeros)
            $dps_id = "DPS" . "4204608" . "2" . "83248021000158" . str_pad("00001", 5, "0", STR_PAD_LEFT) . str_pad($rps_numero, 15, "0", STR_PAD_LEFT);
            
            $sql = "INSERT INTO td_erp_nfse_lote (id,data_envio) VALUES ($this->rps_lote_id,NOW());";
            $dataset = $conn->exec($sql);

            $infDPSXML = '<LoteDPS Id="Lote1" xmlns="http://www.sped.fazenda.gov.br/nfse"><idLote>1</idLote><qtdDps>1</qtdDps><listaDps><DPS versao="1.00">';
            $infDPSXML .= '<infDPS Id="'.$dps_id.'">';
            
            foreach($resultset as $key => $value){
                $infDPSXML .= $this->layoutRPSXML($value);
            }
            $infDPSXML .= '</infDPS>';
            $infDPSXML .= '</DPS></listaDps></LoteDPS>';
            
            // Assina e Salva
            $assinaturaDOM = $this->assinatura($dps_id, $this->inline($infDPSXML));

            if ($assinaturaDOM) {
                $assinaturaDOM->save($this->signedXmlPath);
            } else {
                throw new Exception("Falha ao gerar assinatura do XML.");
            }            
        }

        private function assinatura($id, $xmlContent){
            // 1. Instancia forçando UTF-8
            $xml = new DOMDocument('1.0', 'UTF-8');
            $xml->preserveWhiteSpace = false; 
            $xml->formatOutput = false;
            
            $xml->loadXML($xmlContent, LIBXML_NOBLANKS);

            $dps = $xml->getElementsByTagName('DPS')->item(0);
            $info_dps = $xml->getElementsByTagName('infDPS')->item(0);

            $info_dps->setIdAttribute('Id', true); 

            $objDSig = new XMLSecurityDSig();
            $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);            
            $objDSig->addReference(
                $info_dps,
                XMLSecurityDSig::SHA256,
                ['http://www.w3.org/2000/09/xmldsig#enveloped-signature', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315'],
                ['uri' => '#' . $id, 'force_uri' => true, 'overwrite' => false]
            );

            $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type' => 'private']);

            // --- CORREÇÃO: USANDO ARQUIVO PEM ---
            if (!file_exists($this->privateKeyPath)) {
                throw new Exception("Chave privada não encontrada: " . $this->privateKeyPath);
            }
            
            // Tenta carregar a chave privada do arquivo PEM
            // Se sua chave PEM tiver senha, passe $this->clientCertPass no quarto parâmetro
            $objKey->loadKey($this->privateKeyPath, true, false); 
            
            $objDSig->sign($objKey);

            // --- CORREÇÃO: CARREGANDO CERTIFICADO PÚBLICO PEM ---
            if (!file_exists($this->publicCertPath)) {
                throw new Exception("Certificado público não encontrado: " . $this->publicCertPath);
            }

            $certContent = file_get_contents($this->publicCertPath);
            
            // Limpeza: Pega apenas o conteúdo entre BEGIN e END CERTIFICATE
            // Isso evita erro com "Bag Attributes" que aparecem no seu arquivo
            #if (preg_match('/-----BEGIN CERTIFICATE-----(.*?)-----END CERTIFICATE-----/ws', $certContent, $matches)) {
            #    $certContent = $matches[1];
            #} else {
                // Se não achar o padrão, tenta limpar manualmente
                $certContent = str_replace(array("-----BEGIN CERTIFICATE-----", "-----END CERTIFICATE-----", "\n", "\r"), '', $certContent);
            #}
            // Remove espaços e quebras que restaram
            $certContent = str_replace(array("\n", "\r", " "), '', $certContent);

            $objDSig->add509Cert($certContent, true, false, ['subjectName' => false]);

            $objDSig->appendSignature($dps);

            return $xml; 
        }

        public function send(){
            $response   = array();
            $msgs       = '';
            $status     = '';            
            try {    
                $this->createLote();
                $resp = $this->sendSignedLoteRps();                
                $resp_json = json_decode($resp['response'], true); 
                
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
                            $status = 'warning';
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
                // SQL update...
            }   
            
            return $response;
        }

        public function addLoteRPS(array $lote_rps){
            $this->lote_rps = $lote_rps;
        }

        private function inline($conteudo){
            $conteudo = preg_replace('/>\s+</', '><', $conteudo);
            $conteudo = preg_replace('/\s+/', ' ', $conteudo);
            return trim($conteudo);
        }

        private function layoutRPSXML($value){
            // Copie sua função layoutRPSXML aqui...
            // Certifique-se de que a função layoutRPSXML chama limparStringXml no final
            // e que a ordem das tags cServ esteja correta (cNBS antes de xDescServ)
            
            // ... (Seu código original desta função estava ok, exceto pela chamada do limparStringXml) ...
            
            // Exemplo do final da função:
            // return $this->limparStringXml('... suas tags ...');
            
            // Para não deixar gigante, vou resumir o retorno chamando o layout que você já tem
            // Apenas garanta que o $this->limparStringXml seja o corrigido abaixo:
            return $this->limparStringXml($this->generateInternalXml($value)); 
        }
        
        // Criei esse auxiliar só para organizar seu código gigante do layout
        private function generateInternalXml($value) {
            // Cole aqui todo o código de montagem das variáveis ($valor_servico, etc)
            // e o retorno da string XML crua (sem chamar limparStringXml ainda)
            // ...
             $rps_numero = $value['rpsnumero'];
             $rps_serie = "00001";
             // ... suas variáveis ...
             
             // Retorne a string crua
             return '
                <tpAmb>1</tpAmb>
                <dhEmi>2025-12-18T00:00:00</dhEmi>
                <verAplic>1.0.0</verAplic>
                <serie>00001</serie>
                <nDPS>'.$rps_numero.'</nDPS>
                <dCompet>2025-12</dCompet>
                <tpEmit>1</tpEmit>
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
                    <CPF>99933063987</CPF>
                    <xNome>VIVIANE GRUNDLER VEFAGO</xNome>
                    <end>
                        <xLgr>RUA EXEMPLO</xLgr>
                        <nro>100</nro>
                        <xBairro>CENTRO</xBairro>
                        <cMun>4204608</cMun>
                        <CEP>88900000</CEP>
                    </end>                    
                </toma>
                <serv>
                    <locPrest>
                        <cLocPrestacao>4204608</cLocPrestacao>
                    </locPrest>
                    <cServ>
                        <cTribNac>100501</cTribNac>
                        <cNBS>110012200</cNBS>                          
                        <xDescServ>PRESTACAO DE SERVICOS</xDescServ>
                    </cServ>                    
                </serv>
                <valores>
                    <vServPrest>
                        <vServ>185.61</vServ>
                    </vServPrest>
                    <trib>
                        <tribMun>
                            <tribISSQN>1</tribISSQN>
                            <tpRetISSQN>1</tpRetISSQN>
                            <vAliqISSQN>2.00</vAliqISSQN>
                            <vISSQN>3.71</vISSQN>
                        </tribMun>
                        <totTrib>
                            <vTotTrib>
                                <vTotTribFed>0.00</vTotTribFed>
                                <vTotTribEst>0.00</vTotTribEst>
                                <vTotTribMun>0.00</vTotTribMun>
                            </vTotTrib>
                        </totTrib>
                    </trib>
                </valores>
            ';
        }

        private function prepararXmlNfse($xmlContent) {
            $xmlContent = trim($xmlContent);
            $xmlGzip = gzencode($xmlContent, 9);
            if ($xmlGzip === false) {
                throw new Exception("Falha ao compactar o XML.");
            }
            return base64_encode($xmlGzip);
        }

        function limparStringXml($string) {
            if (empty($string)) return '';

            // Garante UTF-8
            if (!mb_check_encoding($string, 'UTF-8')) {
                $string = mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
            }

            // Remove acentos
            #$string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
            
            // Remove controles
            $string = preg_replace('/[\r\n\t]/', ' ', $string);
            $string = preg_replace('/\s+/', ' ', $string);
            
            return trim($string);
        }
    }