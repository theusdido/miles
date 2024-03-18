<?php
    switch($_op){
        case 'rodape':
            $_entidade = 'td_website_geral_menurodape';
            
        break;
        case 'principal':
        default:
            $_entidade = 'td_website_geral_menuprincipal';
    }
    
    $_criterio  = tdc::f();
    $_criterio->onlyActive();

    $retorno['data'] = tdc::da($_entidade,$_criterio);
    