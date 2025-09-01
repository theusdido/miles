<?php

    require PATH_CLASS . 'nfse/nfse.class.php';

    $nfse = new NFSE();
    $nfse->clientCert = __DIR__ . '/chave_privada.pem';
    $nfse->addLoteRPS([105965]);
    $res = $nfse->send();
    tdc::wj($res);