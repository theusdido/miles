<?php
    switch($_op){
        case 'home':
            $criterio = tdc::f();
            $criterio->limit(3);
            tdc::wj(tdc::da('td_website_geral_blog'));
        break;
        case 'noticia':
            tdc::wj(tdc::pa('td_website_geral_blog',tdc::r('id')));
        break;
    }