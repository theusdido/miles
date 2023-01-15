<?php
    switch($_op){
        case 'rodape':
            $retorno['data'] = tdc::da('td_website_geral_menurodape');
        break;
        default:
        $retorno['data'] = tdc::da('td_website_geral_menuprincipal');
    }
    