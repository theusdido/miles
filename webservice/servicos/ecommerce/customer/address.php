<?php
    require_once PATH_MILES_LIBRARY . 'classes/ecommerce/endereco.class.php';
    switch($_op){
        case 'get':

            $endereco_class = new tdEcommerceEndereco();
            $endereco_class->setCliente($_id);
            $retorno['data']    = $endereco_class->getDados();
            $retorno['status']  = sizeof($retorno['data']) > 0 ? 'success' : 'error';
        break;
    }