<?php
    
    switch($_data->_op){
        case 'politicaprivacidade':
            $politica = tdc::rua('td_website_geral_politicaprivacidade');
        break;
        case 'politicacookies':
            $politica = tdc::rua('td_website_geral_politicacookies');
        break;
        case 'termocondicaouso':
            $politica = tdc::rua('td_website_geral_termocondicaouso');
        break;
    }
    
    $retorno['_data'] = $politica;