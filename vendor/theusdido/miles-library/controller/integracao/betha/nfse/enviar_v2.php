<?php



	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;

#require 'assinar.php';
include 'lote.php';


// Envia um XML assinado para um webservice SOAP (EnviarLoteRpsEnvio).
// Ajuste $wsdl/$endpoint/$action/$cert* conforme seu provedor (Betha ou prefeitura).

function sendSignedLoteRps(
    string $signedXmlPath,   // caminho para lote assinado (arquivo .xml)
    string $endpoint,        // URL do endpoint SOAP (ex: https://nfe-prefeitura.gov.br/servico)
    ?string $wsdl = null,    // opcional: WSDL se disponível, senão null
    ?string $clientCert = null, // caminho para arquivo PEM/PFX para TLS cliente (A1). Se null, não usa TLS client cert
    ?string $clientCertPass = null, // senha do certificado PFX (se aplicável)
    string $soapAction = ''  // SOAPAction se o serviço exigir (opcional)
) : array {
    // leitura do XML assinado
    if (!file_exists($signedXmlPath)) {
        throw new RuntimeException("Arquivo XML assinado não encontrado: $signedXmlPath");
    }
    $xml = file_get_contents($signedXmlPath);
    #// remove possível header XML (<?xml ... \?\>) para inserção no corpo
    $xml = preg_replace('/<\?xml.*?\?>\s*/i', '', $xml);

    // --- Monte o envelope SOAP manualmente para total controle ---
    // Ajuste o namespace padrão do seu serviço (aqui usei o seu: http://www.betha.com.br/e-nota-contribuinte-ws)
    $serviceNs = 'http://www.betha.com.br/e-nota-contribuinte-ws';

    $soapEnvelope = '
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header/>
  <soap:Body>
    <RecepcionarLoteRpsEnvio xmlns="'.$serviceNs.'">
      '.$xml.'
    </RecepcionarLoteRpsEnvio>
  </soap:Body>
</soap:Envelope>
';

$servico = 'ConsultarLoteRps';
$servico = 'RecepcionarLoteRps';
$servico = 'GerarNfse';
$servico = 'RecepcionarLoteRps';

$soapEnvelope = '
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:e="'.$serviceNs.'">
   <soapenv:Header/>
   <soapenv:Body>
      <e:'.$servico.'>
        <nfseCabecMsg>
            <![CDATA[
                <cabecalho xmlns="'.$serviceNs.'" versao="2.02"><versaoDados>2.02</versaoDados></cabecalho>
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
    if ($clientCert) {
        // PHP aceita local_cert em PEM. Se você tem um PFX, converta para PEM (chave + cert) ou aponte direto para PFX em alguns builds.
        // Recomendado: converta .pfx para .pem (cert + key) usando openssl antes:
        // openssl pkcs12 -in certificado.pfx -out cert_and_key.pem -nodes
        // Em seguida use cert_and_key.pem aqui.
        $streamOptions['ssl'] = [
            'verify_peer'       => true,
            'verify_peer_name'  => true,
            'allow_self_signed' => false,
            'cafile'            => '/etc/ssl/certs/ca-certificates.crt', // ou null se CA do servidor for válida
            'local_cert'        => $clientCert,
        ];
        if ($clientCertPass) {
            $streamOptions['ssl']['passphrase'] = $clientCertPass;
        }
    }

    $context = stream_context_create($streamOptions);

    // --- Cabeçalhos HTTP customizados ---
    $headers = [
        'Content-Type: text/xml; charset=utf-8',
        'Content-Length: ' . strlen($soapEnvelope)
    ];
    if (!empty($soapAction)) {
        $headers[] = 'SOAPAction: "' . $soapAction . '"';
    }

    // --- Use __doRequest para enviar raw SOAP (mais controle que SoapClient) ---
    // Mas para usar stream_context com TLS client cert, vamos criar SoapClient se precisar analisar WSDL,
    // caso contrário usamos curl para maior controle.
    if ($wsdl) {
        // opção: usar SoapClient com contexto customizado
        $options = [
            'trace' => 1,
            'exceptions' => 1,
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE,
            // 'local_cert' não funciona diretamente aqui para todos os setups; preferimos stream_context.
        ];
        $client = new SoapClient($wsdl, $options);
        try {
            // se serviço espera operação chamada 'EnviarLoteRps', troque adequadamente
            // Aqui chamamos __doRequest diretamente para inserir envelope pronto
            $responseXml = $client->__doRequest($soapEnvelope, $endpoint, $soapAction ?: 'RecepcionarLoteRpsEnvio', SOAP_1_1);
        } catch (SoapFault $e) {
            throw new RuntimeException("Erro SOAP: {$e->getMessage()} (faultcode={$e->faultcode})");
        }
    } else {
        // Sem WSDL: use cURL (mais robusto para TLS client cert e headers)
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $soapEnvelope);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, '/etc/ssl/certs/ca-certificates.crt'); // ajustar se necessário

        if ($clientCert) {
            // Se você tem PEM com chave (cert+key), use CURLOPT_SSLCERT
            // Se tiver PFX, use CURLOPT_SSLCERTTYPE = 'P12' e CURLOPT_SSLCERT = caminho.pfx (nem sempre funciona dependendo do build)
            $ext = pathinfo($clientCert, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['pfx','p12'])) {
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12');
                curl_setopt($ch, CURLOPT_SSLCERT, $clientCert);
                if ($clientCertPass) curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $clientCertPass);
            } else {
                // espera PEM (cert+key) ou cert separado
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($ch, CURLOPT_SSLCERT, $clientCert);
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


// === USO ===
try {
    #$signedXmlPath = 'lote_rps_assinado.xml';
    $signedXmlPath = 'lote_rps.txt';
    $endpoint = 'https://e-gov.betha.com.br/e-nota-contribuinte-ws/nfseWS?wsdl'; // ajustar
    $endpoint = 'https://nota-eletronica.betha.cloud/rps/ws';
    #$wsdl = null; // ou 'https://prefeitura.exemplo.gov.br?wsdl' se tiver
    $wsdl ='https://e-gov.betha.com.br/e-nota-contribuinte-ws/nfseWS?wsdl';
    #$clientCert = __DIR__ . '/cert_and_key.pem'; // ou null se não usar TLS client cert
    #$clientCert = null;
    $clientCert = 'chave_privada.pem';
    $clientCertPass = 'goes1234'; // se necessário (ou null)
    #$clientCertPass = null;

    $resp = sendSignedLoteRps($signedXmlPath, $endpoint, $wsdl, $clientCert, $clientCertPass, ''); // SOAPAction se necessário
    echo "Resposta recebida:\n";
    if (!empty($resp['bodyNodes'])) {
        foreach ($resp['bodyNodes'] as $k => $v) {
            echo "=== $k ===\n$v\n\n";
        }
    } else {
        echo $resp['raw'];
    }
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . PHP_EOL;
}