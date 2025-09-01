<?php

   
$cep_default = '88800-001';
$configuracoes_ecommerce = tdc::ru('ecommerce_configuracoes');
$cep_origem = !$configuracoes_ecommerce->cep_origem_pedido ? $cep_default : $configuracoes_ecommerce->cep_origem_pedido;

$peso = $comprimento = $altura = $largura = $diametro = 0;

$cep_destino = str_replace(array(' ','-','.'),'',tdc::r('cep_destino'));

$codServico     = "04014"; // SEDEX à vista
$cepOrigem      = str_replace(array(".","-"),array(""),$cep_origem==''?$cep_default:$cep_origem);
$cepDestino     = str_replace(array(".","-"),array(""),$cep_destino);
$peso           = (($peso == 0 || $peso == "" || $peso < 0.3 || $peso > 30) ? 0.3 : $peso);
$codFormato     = 1;
$comprimento    = (($comprimento == 0 || $comprimento == "" || $comprimento < 16) ? 16 : $comprimento);
$altura         = $altura <= 0 ? 1 : $altura;
$largura        = (($largura == 0 || $largura == "" || $largura < 11) ? 11 : $largura);
$diametro       = $diametro;

$params = array(
    
        "SellerCEP" => "88900000",
        "RecipientCEP" => $cep_destino,
        "ShipmentInvoiceValue" => 1,
        "ShippingServiceCode" => $codServico,
        "ShippingItemArray" => [
            array(
                "Height" => $peso,
                "Length" => $comprimento,
                "Quantity" => 1,
                "Weight" => $comprimento,
                "Width" => $largura,
                "SKU" => "1",
                "Category" => "Running"
            )
        ],
        "RecipientCountry" => "BR"
    
);

#echo json_encode($params);
#exit;

// 03220 - Sedex
// 03298 - Pac

#$url = "http://api.frenet.com.br/shipping/quote?" .  http_build_query($params);
$url = "http://api.frenet.com.br/shipping/quote";
$utl = 'http://services.frenet.com.br/logistics/getShippingQuote.asmx';
$token = 'A137C4B8RA465R4BC8R8575R3618BE655B56';

$headers = [
    "Accept: application/json",
    "token: " . $token,
];

$ch = curl_init($url);

// GET
#curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
#curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// POST
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Erro cURL: ' . curl_error($ch);
} else {
    header("Content-Type: application/json");
    echo $response; // ou processe com SimpleXML se quiser extrair valores específicos
}

curl_close($ch);
