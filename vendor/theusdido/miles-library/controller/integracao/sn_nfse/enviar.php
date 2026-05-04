<?php

    require PATH_CLASS . 'nfse/snnfse.class.php';

    $rpsnumero = tdc::r('nota')['rpsnumero'];
    $nota_id = tdc::r('nota')['id'];
    $nota_referencia = tdc::r('nota')['referencia'];

    $nfse = new snNFSE();
    $nfse->addLoteRPS([$rpsnumero]);
    $res = $nfse->send();

    $is_producao = 0;
    $config = tdc::ru('erp_nfse_configuracoes');
    if ($config->hasData()) {
        $is_producao = (int)$config->is_ambiente_producao == 1 ? 1 : 2;
    }

    $is_enviada = 0;

    // Atualiza o status e situação na nota no banco de dados
    if ($res['status'] == 'success' && $is_producao == 1){
        snNFSE::setNotaEnviada($nota_id);
        $is_enviada = 1;
    }

    // Log de Envio
    $envio = tdc::p('td_erp_nfse_envio_log');
    $envio->datahora = date('Y-m-d H:i:s');
    $envio->nfse = $nota_id;
    $envio->enviada = $is_enviada;
    $envio->nfse_dps = $rpsnumero;
    $envio->referencia = substr($nota_referencia, 0, 2) . '/' . substr($nota_referencia, 2, 4);

    var_dump($res);
    if ($res['status'] == 'error' && !empty($res['message'])){
        $error = explode('|', $res['message']);
        $error_code = str_replace('ERRO: ','',trim($error[0]));
        $error_message = trim($error[1]);

        $envio->error_code = $error_code;
        $envio->error_message = $error_message;

        // Erro de nota já enviada
        if ($error_code == 'E0014'){
            snNFSE::setNotaEnviada($nota_id);
            $envio->enviada = true;

            // Atualiza log de envio para nota já enviada pelo DPS
            $ds_envio = tdc::d('td_erp_nfse_envio_log', tdc::f('nfse_dps',"=",$rpsnumero));
            foreach($ds_envio as $d){
                $d->enviada = true;   
                $d->armazenar();
            }
            
        }
    }

    $envio->armazenar();

    // Retorna JSON requisição
    tdc::wj($res);