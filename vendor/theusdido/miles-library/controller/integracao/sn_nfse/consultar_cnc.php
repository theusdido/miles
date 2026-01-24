<?php

    require PATH_CLASS . 'nfse/snnfse.class.php'; // The base class
    require PATH_CLASS . 'nfse/sncnc.class.php';  // The new class

    // Assuming CNPJ comes from a request parameter
    #$cnpj = tdc::r('dados')['cnpj']; 
    $cnpj = tdc::r('cnpj'); 
    #var_dump($cnpj);
    #die;

    if (empty($cnpj)) {
        tdc::wj(['status' => 'error', 'message' => 'CNPJ não fornecido para consulta.']);
        exit;
    }

    try {
        $snCnc = new snCnc();
        $res = $snCnc->consultarCnc($cnpj);
    } catch (Exception $e) {
        $res = ['status' => 'error', 'message' => 'Erro na consulta CNC: ' . $e->getMessage()];
    }

    // Retorna JSON com o resultado da consulta
    tdc::wj($res);
