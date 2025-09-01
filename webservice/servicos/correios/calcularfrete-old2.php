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

$xmlRequest = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cli="http://cliente.bean.master.sigep.bsb.correios.com.br/">
   <soapenv:Header/>
   <soapenv:Body>
      <cli:calculaTarifaServico>
         <codServico>{$codServico}</codServico>
         <cepOrigem>{$cepOrigem}</cepOrigem>
         <cepDestino>{$cepDestino}</cepDestino>
         <peso>{$peso}</peso>
         <codFormato>{$codFormato}</codFormato>
         <comprimento>{$comprimento}</comprimento>
         <altura>{$altura}</altura>
         <largura>{$largura}</largura>
         <diametro>{$diametro}</diametro>
         <codMaoPropria>n</codMaoPropria>
         <valorDeclarado>0</valorDeclarado>
         <codAvisoRecebimento>n</codAvisoRecebimento>
         <codAdministrativo></codAdministrativo>
         <usuario></usuario>
      </cli:calculaTarifaServico>
   </soapenv:Body>
</soapenv:Envelope>
XML;

$url = "https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente";

$headers = [
    "Content-Type: text/xml;charset=UTF-8",
    "SOAPAction: \"\"",
    "Content-Length: " . strlen($xmlRequest),
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Erro cURL: ' . curl_error($ch);
} else {
    header("Content-Type: text/xml");
    echo $response; // ou processe com SimpleXML se quiser extrair valores específicos
}

curl_close($ch);
