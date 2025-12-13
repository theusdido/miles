<?php

    require PATH_CLASS . 'nfse/snnfse.class.php';

    $rpsnumero = tdc::r('nota')['rpsnumero'];

    $nfse = new snNFSE();
    $nfse->clientCert = __DIR__ . '/chave_privada.pem';
    $nfse->publicCert = __DIR__ . '/certificado_publico.pem';
    $nfse->addLoteRPS([$rpsnumero]);
    #$nfse->send();
    $res = $nfse->send();
    tdc::wj($res);