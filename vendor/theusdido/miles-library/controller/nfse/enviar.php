<?php

    $webservice = 'betha';
    $layout     = 'betha';
    $version    = '1.0';

    switch($webservice){
        case 'betha':
            require PATH_MVC_CONTROLLER . 'integracao/betha/nfse/enviar_v1.php';
        break;
    }
    