<?php
    switch($_op){
        case 'politica-privacidade':
            $retorno['_data'] = tdc::rua('td_website_geral_politicaprivacidade');
        break;
        case 'politica-cookie':
            $retorno['_data'] =  tdc::rua('td_website_geral_politicacookies');
        break;
        case 'redessociais':
            $retorno['_data'] = tdc::da('td_website_geral_redessociais');
        break;
        case 'endereco':
            $retorno['_data'] = tdc::rua('td_website_geral_rodape');
        break;        
    }
    