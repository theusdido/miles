<?php

    require PATH_CLASS . 'nfse/nfse.class.php';

    $rpsnumero = tdc::r('nota')['rpsnumero'];

    $nfse = new NFSE();
    $nfse->clientCert = __DIR__ . '/chave_privada.pem';
    $nfse->addLoteRPS([$rpsnumero]);
    $res = $nfse->send();
    tdc::wj($res);