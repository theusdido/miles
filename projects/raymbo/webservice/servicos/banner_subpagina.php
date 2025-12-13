<?php
    switch($_data->_op){
        case 'advertisement':
            $retorno['_data'] = tdc::rua('td_raymbo_publicity_advertisement');
        break;
        case 'columnist':

            $colunista  = tdc::du('td_website_blog_colunista',['menu','=',$_data->_fixo]);
            $criterio   = tdc::f();
            $criterio->addFiltro('coluna','=',$colunista->coluna);
            $retorno['_data'] = tdc::da('td_raymbo_publicity_columnist',$criterio);
        break;        
    }