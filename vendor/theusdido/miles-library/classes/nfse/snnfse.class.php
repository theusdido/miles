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

        #public string $endpoint         = 'https://sefin.nfse.gov.br/sefinnacional/nfse';
        public string $endpoint         = 'https://sefin.nfse.gov.br/sefinnacional/nfse';        
        #public string $endpoint          = 'https://adn.nfse.gov.br/nfse';
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
        private bool $is_remove_cabecalho = false;

        private string $serie_dps       = '00001';

        public function sendSignedLoteRps(string $xmlContentRaw) : array {

            if (!$xmlContentRaw) {
                throw new RuntimeException("Conteúdo do XML assinado não pode ser vazio.");
            }

            // 2. Remove o cabeçalho apenas para o ENVIO (Payload JSON)            
            $xmlAssinado = $this->is_remove_cabecalho ? preg_replace('/<\?xml.*?\?>\s*/iu', '', $xmlContentRaw) : $xmlContentRaw;
            #var_dump($xmlAssinado);
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
            $rps_numero = 1;
            
            $tipo_inscricao_federal = '2'; // 1 - CNPJ, 2 - CPF
            // CORREÇÃO: ID da DPS deve conter o tipo de inscrição (2 para CNPJ)
            $dps_id = "DPS" . "4204608" . $tipo_inscricao_federal . "83248021000158" . str_pad((string)$this->serie_dps, 5, "0", STR_PAD_LEFT) . str_pad((string)$rps_numero, 15, "0", STR_PAD_LEFT);
            #$dps_id = 'DPS420460818324802100015800900000000000000008';
            
            $sql = "INSERT INTO td_erp_nfse_lote (id,data_envio) VALUES ($this->rps_lote_id,NOW());";
            $dataset = $conn->exec($sql);

            // --- DADOS DO PRESTADOR PARA O LOTE ---
            // (Estes valores devem vir de um arquivo de configuração ou do cadastro da empresa)
            $cnpj_prestador = '83248021000158';
            $im_prestador = '1169'; // Placeholder, use o valor correto

            // CORREÇÃO: Remover wrappers <GerarNfseEnvio> e <LoteDps>. O root agora é <DPS>.
            // A capitalização de <DPS> e <infDPS> está correta.
            $xmlString = '<DPS versao="1.00" xmlns="http://www.sped.fazenda.gov.br/nfse">';
            $xmlString .= '<infDPS Id="'.$dps_id.'">'; // Casing is correct here.
            
            foreach($resultset as $key => $value){
                $xmlString .= $this->layoutRPSXML($value);
            }
            
            $xmlString .= '</infDPS>';
            $xmlString .= '</DPS>';
            
            // Adiciona o cabeçalho XML aqui, antes da assinatura
            $finalXmlString = '<?xml version="1.0" encoding="UTF-8"?>' . $xmlString;

            // Assina e retorna o XML como string
            $signedXmlString = $this->assinatura($dps_id, $this->inline($finalXmlString));

            // Salva o XML final para depuração
            file_put_contents('debug_nfse.xml', $signedXmlString);

            if (!$signedXmlString) {
                throw new Exception("Falha ao gerar assinatura do XML.");
            }
            
            return $signedXmlString;
        }

        private function assinatura($id, $xmlContent){
            // 1. Instancia forçando UTF-8
            $xml = new DOMDocument('1.0', 'UTF-8');
            
            // CORREÇÃO PROFUNDA: A normalização de espaços deve ser feita APENAS pelo
            // algoritmo de canonização (C14N). Alterar o XML antes disso invalida a assinatura.
            // preserveWhiteSpace=true (padrão) mantém os nós de espaço.
            // formatOutput=false evita que o saveXML adicione indentação.
            $xml->preserveWhiteSpace = true;
            $xml->formatOutput = false;
            
            // Assegura que o conteúdo XML está em UTF-8 válido e limpo.
            // Esta é uma salvaguarda final para o caso de a limpeza anterior não ter sido suficiente.
            $encoding = mb_detect_encoding($xmlContent, 'UTF-8, ISO-8859-1', true);
            if ($encoding) {
                $xmlContent = mb_convert_encoding($xmlContent, 'UTF-8', $encoding);
            } else {
                // Se a detecção falhar, assume UTF-8 e tenta limpar.
                $xmlContent = mb_convert_encoding($xmlContent, 'UTF-8', 'UTF-8');
            }
            // Remove quaisquer caracteres que não são válidos em XML 1.0 ou que são inválidos UTF-8
            // (por exemplo, caracteres de controle, sequências de bytes inválidas).
            $xmlContent = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $xmlContent);
            
            $xml->loadXML($xmlContent);

            $dps = $xml->getElementsByTagName('DPS')->item(0);
            $info_dps = $xml->getElementsByTagName('infDPS')->item(0);

            $info_dps->setIdAttribute('Id', true); 

            // CORREÇÃO: Instanciar XMLSecurityDSig com prefixo vazio ('')
            // para que os elementos da assinatura (<Signature>, <SignedInfo>, etc.)
            // não usem o prefixo "ds:" e herdem o namespace padrão do documento.
            // Isso é necessário para cumprir a exigência do Emissor Nacional (Erro E6155).
            $objDSig = new XMLSecurityDSig('');
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

            // Carrega o conteúdo completo do certificado público PEM.
            // A biblioteca xmlseclibs espera o conteúdo do arquivo PEM, incluindo os cabeçalhos.
            $certContent = file_get_contents($this->publicCertPath);

            if (empty($certContent)) {
                throw new Exception("Conteúdo do certificado público está vazio: " . $this->publicCertPath);
            }

            $objDSig->add509Cert($certContent, true, false, ['subjectName' => false]);

            $objDSig->appendSignature($dps);

            // CORREÇÃO: Adiciona manualmente o namespace default ao elemento <Signature>.
            // O emissor nacional rejeita prefixos (E6155), mas o schema exige que a assinatura
            // esteja no namespace 'xmldsig'. A solução é declarar um namespace default no
            // próprio elemento <Signature>, o que o remove do namespace da NFS-e e o coloca
            // no namespace correto, sem usar prefixos, resolvendo a falha de schema (RNG6110).
            $sigNode = $xml->getElementsByTagName('Signature')->item(0);
            if ($sigNode) {
                $sigNode->setAttribute('xmlns', 'http://www.w3.org/2000/09/xmldsig#');
            }

            return $xml->saveXML(); 
        }

        public function send(){
            $response   = array();
            $msgs       = '';
            $status     = '';            
            try {    
                $signedXml = $this->createLote();
                $resp = $this->sendSignedLoteRps($signedXml);
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
            
            // --- DADOS DA NOTA (Banco de Dados) ---
            $rps_numero = $value['rpsnumero'];
            $rps_numero = 1;
            #$rps_numero = str_pad(1, 15, "0", STR_PAD_LEFT);
            $rps_serie = $this->serie_dps;
            
            #$data_emissao_obj = new DateTime($value['demis']);
            #$data_emissao_obj = new DateTime('2025-12-02');
            #$data_emissao_formatada = $data_emissao_obj->format(DateTime::ATOM);
            $data_emissao_formatada = '2025-12-02T08:00:00-03:00';

            #$data_competencia = $value['dcompetencia'];
            $data_competencia = '2025-12-02';

            $valor_servico = $value['valservicos'];
            $valor_iss = $value['valiss'];
            // Formata alíquota para 2 casas decimais, conforme schema
            $valor_aliquota = number_format($value['valaliqiss'], 2, '.', '');

            $discriminacao = htmlspecialchars(str_replace('<<ENTER>>','',$value['discriminacao']), ENT_QUOTES, 'UTF-8');

            // --- DADOS DO TOMADOR (Banco de Dados) ---
            $cpf_tomador = $value['tomacpf'];
            $cnpj_tomador = $value['tomacnpj'];

            if ($cpf_tomador == ''){
                $documento_tomador = '<CNPJ>'.$cnpj_tomador.'</CNPJ>';
            } else {
                $documento_tomador = '<CPF>'.$cpf_tomador.'</CPF>';
            }

            $razao_social_tomador  = htmlspecialchars($value['tomarazaosocial'], ENT_QUOTES, 'UTF-8');
            $endereco_tomador = htmlspecialchars($value['tomaendereco'], ENT_QUOTES, 'UTF-8');
            $numero_tomador = htmlspecialchars($value['tomanumero'] ?: 'S/N', ENT_QUOTES, 'UTF-8');
            $complemento_tomador = $value['tomacomplemento'] ? '<xCpl>'.htmlspecialchars($value['tomacomplemento'], ENT_QUOTES, 'UTF-8').'</xCpl>' : '';
            $bairro_tomador = htmlspecialchars($value['tomabairro'], ENT_QUOTES, 'UTF-8');
            $codigo_municipio_tomador = $value['tomacmun'];
            $cep_tomador = $value['tomacep'];
            $email_tomador = htmlspecialchars($value['tomaemail'], ENT_QUOTES, 'UTF-8');

            $endereco_tomador_xml = '
                <end>
                    <endNac>
                        <cMun>'.$codigo_municipio_tomador.'</cMun>
                        <CEP>'.$cep_tomador.'</CEP>
                    </endNac>
                    <xLgr>'.$endereco_tomador.'</xLgr>
                    <nro>'.$numero_tomador.'</nro>
                    '.$complemento_tomador.'
                    <xBairro>'.$bairro_tomador.'</xBairro>
                </end>';

            // --- DADOS ESTÁTICOS / CONFIGURÁVEIS DO PRESTADOR ---
            $cnpj_prestador = '83248021000158';
            $codigo_municipio_prestador = '4204608';
            $prestador_im = '0'; // Placeholder
            $prestador_email = 'email@example.com'; // Placeholder

            // Regimes e Códigos
            $regime_apuracao_simples_nacional = 1;
            $regime_especial_tributacao = $value['regesptrib'];
            $regime_especial_tributacao = 0;
            $situacao_perante_simples_nacional = 3;
            $codigo_tributacao_nacional = '100501';
            $codigo_tributacao_municipal = '1601'; // Placeholder
            $nbs = '110012200';
            $tributacao_issqn = 1; // 1 = Operação Tributável
            $retencao = $value['issretido'] == 1 ? 2 : 1;
            
            $total_tributos_federais = '0.00';
            $total_tributos_estaduais = '0.00';
            $total_tributos_municipais = '0.00';

            $tipo_ambiente = 1; // 1 - Produção, 2 - Homologação
            $versao_aplicativo = '1.0.0';
            $emissor_dps = 1; // 1 = Prestador
            
             // Retorna a string XML com a capitalização correta das tags (camelCase)
             return '
                <tpAmb>'.$tipo_ambiente.'</tpAmb>
                <dhEmi>'.$data_emissao_formatada.'</dhEmi>
                <verAplic>'.$versao_aplicativo.'</verAplic>
                <serie>'.$rps_serie.'</serie>
                <nDPS>'.$rps_numero.'</nDPS>
                <dCompet>'.$data_competencia.'</dCompet>
                <tpEmit>'.$emissor_dps.'</tpEmit>
                <cLocEmi>'.$codigo_municipio_prestador.'</cLocEmi>
                <prest>
                    <CNPJ>'.$cnpj_prestador.'</CNPJ>
                    <regTrib>
                        <opSimpNac>'.$situacao_perante_simples_nacional.'</opSimpNac>
                        <regApTribSN>'.$regime_apuracao_simples_nacional.'</regApTribSN>
                        <regEspTrib>'.$regime_especial_tributacao.'</regEspTrib>
                    </regTrib>
                </prest>
                <toma>
                    '.$documento_tomador.'
                    <xNome>'.$razao_social_tomador.'</xNome>
                    '.$endereco_tomador_xml.'
                </toma>
                <serv>
                    <locPrest>
                        <cLocPrestacao>'.$codigo_municipio_prestador.'</cLocPrestacao>
                    </locPrest>
                    <cServ>
                        <cTribNac>'.$codigo_tributacao_nacional.'</cTribNac>
                        <xDescServ>'.$discriminacao.'</xDescServ>
                        <cNBS>'.$nbs.'</cNBS>
                    </cServ>
                </serv>
                <valores>
                    <vServPrest>
                        <vServ>'.$valor_servico.'</vServ>
                    </vServPrest>
                    <trib>
                        <tribMun>
                            <tribISSQN>'.$tributacao_issqn.'</tribISSQN>
                            <tpRetISSQN>'.$retencao.'</tpRetISSQN>                            
                        </tribMun>
                        <totTrib>
                            <vTotTrib>
                                <vTotTribFed>'.$total_tributos_federais.'</vTotTribFed>
                                <vTotTribEst>'.$total_tributos_estaduais.'</vTotTribEst>
                                <vTotTribMun>'.$total_tributos_municipais.'</vTotTribMun>
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

            // CORREÇÃO: Garante a conversão para UTF-8 de forma mais robusta,
            // detectando a codificação original antes de converter.
            $encoding = mb_detect_encoding($string, 'UTF-8, ISO-8859-1', true);
            if ($encoding) {
                $string = mb_convert_encoding($string, 'UTF-8', $encoding);
            } else {
                // Se a detecção falhar, assume UTF-8 e tenta limpar a string.
                $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
            }
            
            // A LINHA ABAIXO FOI REMOVIDA POIS CORROMPIA OS DADOS E CAUSAVA FALHA DE SCHEMA
            // $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
            
            // Remove quebras de linha e tabs, substituindo por espaços
            $string = preg_replace('/[\r\n\t]/', ' ', $string);
            // Reduz múltiplos espaços a um único espaço
            $string = preg_replace('/\s+/', ' ', $string);
            
            return trim($string);
        }
    }