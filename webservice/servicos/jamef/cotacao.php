<?php

    # URL do WEBSERVICE
    $host            = "http://www.jamef.com.br/frete/rest/v1/";

    # Tipo de transporte ou tipo de frete escolhido pelo # Cliente. 1:Rodoviário 2: Aéreo
    $tiptra         = 1;

    #CNPJ do cliente que será responsável pelo pagamento
    $cnpjcpf        = "04678441000106";

    #Nome do Município de origem da Mercadoria. Mesmo Munícipio do Cliente Responsável.
    $munori         = "São Joaquim";

    #Sigla do Estado de origem.
    $estori         = "SC";

    #Tipo de Produto a ser transportado.
    $segprod        = "000004"; // CONFORME NOTA FISCAL

    # Peso total ( KG )
    $peso           = 10.0;

    # Valor total da mercadoria.
    $valmer         = 50.0;

    #Peso cubado em metros
    $metro3         = 0;

    # CEP Destino
    $cepdes         = "88600000";

    #Filial da Jamef que irá efetuar a coleta da mercadoria e emitir o CTRC do cliente.
    $filcot         = "26";

    #Dia correspondente à data de cotação.
    $dia            = date("d");

    #Mês correspondente à data de cotação.
    $mes            = date("m");

    #Ano correspondente à data de cotação.
    $ano            = date("Y");

    # Login cadastrado na Jamef.
    $usuario        = "";

    # URL a ser enviada
    $url = "{$host}}/{$tiptra}}/{$cnpjcpf}/{$munori}/{$estori}/{$segprod}/{$peso}/{$valmer}/{$metro3}/{$cepdes}/{$filcot}/{$dia}/{$mes}/{$ano}/{$usuario}";
    $url = "http://www.jamef.com.br/frete/rest/v1/1/20147617002276/SAO PAULO/SP/000004/5/100.00/0.025/32210130/07/30/10/2018/jameff";

    // Cria o cURL
    $curl = curl_init();

    // Seta algumas opções
    curl_setopt_array($curl, [
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url
    ]);

    // Envia a requisição e salva a resposta
    $response = curl_exec($curl);

    // Fecha a requisição e limpa a memória
    curl_close($curl);

    // Retorno
    $retorno = json_decode($response,true);

    $retorno["status"]  = "success";
    $retorno["dados"]   = json_encode(array("valor" => "1.24514","previsao_entrega" =>"31/10/2018"));