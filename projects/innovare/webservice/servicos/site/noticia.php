<?php
    switch($_op){
        case 'home':
            $criterio = tdc::f();
            $criterio->limit(3);
            $retorno['_data'] = tdc::da('td_website_geral_blog', $criterio);
        break;
        case 'noticia':
            $retorno['_data'] = tdc::pa('td_website_geral_blog',tdc::r('id'));
        break;
        default:
            $retorno['_data'] = tdc::da('td_website_geral_blog');
            
    }