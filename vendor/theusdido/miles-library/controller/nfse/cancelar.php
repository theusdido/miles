<?php

    $op         = tdc::r('op');
    switch($op){
        case 'betha':
            require PATH_MVC_CONTROLLER . 'integracao/betha/nfse/cancelar.php';
        break;
    }