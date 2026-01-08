<?php

    require PATH_CLASS . 'nfse/nfse.class.php';

    $nfse = new NFSE();
    $nfse->clientCert = __DIR__ . '/chave_privada.pem';    
    $res = $nfse->consultaPorRPS(tdc::r('rpsnumero'));
    tdc::wj($res);