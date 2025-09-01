<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;

// Carrega o XML
$xml = new DOMDocument();
$xml->load('RecepcionarLoteRpsPopulado.xml'); // seu XML



// Localiza o nó a ser assinado (por ID)
$elemento = $xml->getElementsByTagName('EnviarLoteRpsEnvio')->item(0); // ajuste conforme a tag e o ID
#$elemento = $xml->getElementsByTagName('LoteRps')->item(0); // ajuste conforme a tag e o ID
$elemento2 = $xml->getElementsByTagName('Rps')->item(0); // ajuste conforme a tag e o ID
#$elemento->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.w3.org/2000/09/xmldsig#');


// Cria o objeto de assinatura
$objDSig = new XMLSecurityDSig(NULL);
$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
$objDSig->addReference(
    $elemento,
    XMLSecurityDSig::SHA1,
    ['http://www.w3.org/2000/09/xmldsig#enveloped-signature', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315'],
    ['uri' => '#lote1'] // ID correspondente no XML
);

// Carrega a chave privada do certificado A1
$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, ['type' => 'private']);
$objKey->loadKey('chave_privada.pem', true, false, 'goes1234');

// Se o certificado estiver em .pfx, você precisa extrair .pem antes:
// openssl pkcs12 -in certificado.pfx -out chave_privada.pem -nodes
// openssl pkcs12 -in certificado.pfx -out certificado_publico.pem -nokeys

// Assina o XML
$objDSig->sign($objKey);

// Anexa o certificado público à assinatura
$objDSig->add509Cert(file_get_contents('certificado_publico.pem'), true, false, ['subjectName' => false]);

// Insere a assinatura no XML
$objDSig->appendSignature($elemento);
$objDSig->appendSignature($elemento2);

// Salva o XML assinado
$xml->save('lote_rps_assinado.xml');
