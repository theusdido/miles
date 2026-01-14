<?php

    require PATH_CLASS . 'nfse/snnfse.class.php';

    $rpsnumero = tdc::r('nota')['rpsnumero'];
    $nota_id = tdc::r('nota')['id'];

    $nfse = new snNFSE();
    $nfse->addLoteRPS([$rpsnumero]);
    $res = $nfse->send();

    // Atualiza o status e situação na nota no banco de dados
    if ($res['status'] == 'success'){
        $nota = tdc::p('td_erp_nfse_nota',$nota_id);
        $nota->status = 'E';
        $nota->situacao = 'E';
        $nota->armazenar();
    }

    // Retorna JSON requisição
    tdc::wj($res);