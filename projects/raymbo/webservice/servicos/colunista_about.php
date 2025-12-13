<?php

    $colunista  = tdc::dua('td_website_blog_colunista',['menu','=',$_data->_fixo]);

    $profile = getListaRegFilhoArray(
        getEntidadeId('td_website_blog_colunista'),
        getEntidadeId('td_raymbo_columnist_profile'),
        $colunista['id']
    );

    if (isset($profile[0])){
        $profile[0]['colunista'] = $colunista;
        $_dados = $profile[0];
    }else{
        $_dados = new stdClass;
    }
    

    $retorno['_data'] = $_dados;