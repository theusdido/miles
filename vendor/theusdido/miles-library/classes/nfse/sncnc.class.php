<?php
    /*
        * Framework MILES
        * @license : Teia Tecnologia WEB.
        * @link http://www.teia.tec.br

        * Classe snCnc
        * Data de Criacao: 14/01/2026
        * Author: @Theusdido
        * Sistema Nacional de NFS-e - Consulta Nacional de Contribuintes (CNC)
    */

    class snCnc extends snNFSE {
        public function __construct() {
            $this->endpoint = 'https://adn.producaorestrita.nfse.gov.br/cnc/CNC';
            $this->ambiente = 2; // Produção Restrita para CNC
        }

        public function consultarCnc(string $cnpj) : array {
            $xmlContentRaw = $this->createConsultaCncXml($cnpj);
            if (!$xmlContentRaw) {
                throw new RuntimeException("Conteúdo do XML de consulta não pode ser vazio.");
            }

            // Remove o cabeçalho XML antes de comprimir e encodar, seguindo o padrão da classe pai
            $xmlSemCabecalho = preg_replace('/<\?xml.*?\?>\s*/iu', '', $xmlContentRaw);
            $dados = [
                "xmlGZipB64" => $this->prepararXmlNfse($xmlSemCabecalho) 
            ];

            $payloadJson = json_encode($dados);
            $headers = [
                'Content-Type: application/json; charset=utf-8',
                'Accept: application/json',
                'Content-Length: ' . strlen($payloadJson)
            ];

            $ch = curl_init($this->getEndPoint());
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, $this->cafile);

            if ($this->publicCertPath && file_exists($this->publicCertPath)) {
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
            $retorno['http_code'] = $httpCode;
            $retorno['response'] = $response;

            if (curl_errno($ch) == 0 && in_array($httpCode, [200, 201])) {
                $retorno['status'] = 'success';
                $retorno['curl_error'] = '';    
            }else{
                $retorno['status'] = 'error';
                $retorno['curl_error'] = curl_error($ch);
            }

            curl_close($ch);
            return $retorno;
        }

        private function createConsultaCncXml(string $cnpj) : string {
            $idConsulta = "CNC" . time(); // Unique ID for the consultation
            $xmlString = '<?xml version="1.0" encoding="UTF-8"?>
                <CNC xmlns="[http://www.sped.fazenda.gov.br/nfse](http://www.sped.fazenda.gov.br/nfse)" versao="1.00">
                    <infCNC Id="CNC43149022000000000001234">
                        <cMun>4314902</cMun>
                        <cnpjMun>88777666000100</cnpjMun>
                        <CPFAgTrib>11122233344</CPFAgTrib>
                        <tpAmb>2</tpAmb>
                        <verAplic>SistemaProprio_v1.0</verAplic>
                        <infContrib>
                            <CNPJ>99888777000155</CNPJ>
                            <IM>000000000001234</IM>
                            <dIM>2024-05-20</dIM>
                            <xFantasia>Tech Solucoes LTDA</xFantasia>
                            <ender>
                                <CEP>90000000</CEP>
                                <xLgr>Av. Ipiranga</xLgr>
                                <nro>1000</nro>
                                <xCpl>Sala 502</xCpl>
                                <xBairro>Praia de Belas</xBairro>
                            </ender>
                            <fone>5133334444</fone>
                            <email>nfe@techsolucoes.com.br</email>
                            <dAutEmiss>2024-05-21</dAutEmiss>
                            <cStatEmiss>1</cStatEmiss>
                            <cSitCNC>1</cSitCNC>
                            <xSitCadMun>Ativo</xSitCadMun>
                            <xMotivoSitCadMun>Cadastro inicial</xMotivoSitCadMun>
                        </infContrib>
                    </infCNC>
                </CNC>
            ';

            // The CNC consultation XML does not need to be signed according to the documentation.
            // If it were necessary, the signing logic would be called here.
            // For now, just returning the unsigned XML.
            return $xmlString;

        }

        // Override getEndPoint to use the specific CNC endpoint
        protected function getEndPoint(){
            return $this->endpoint;
        }
    }