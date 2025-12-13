<?php

    $criterio           = tdc::f();
    switch($_data->_op){
        case 'fixo':
            $colunista          = tdc::du('td_website_blog_colunista',['menu','=',$_data->_value]);
            $colunista_id       = $colunista->id;
        break;
        case 'id':
            $colunista_id       = $_data->_value;
        break;
    }

    $criterio->addFiltro('colunista','=',$colunista_id);
    $criterio->addFiltro('is_autorizado','=',1);    

    $retorno['_data']   = tdc::da('td_website_blog_post',$criterio);