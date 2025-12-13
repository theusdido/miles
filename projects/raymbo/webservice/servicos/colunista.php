<?php
    $colunista  = tdc::dua('td_website_blog_colunista',['menu','=',$_data->_fixo]);
    $coluna     = tdc::rua('td_website_blog_coluna',$colunista['coluna']);
    $profile    = getListaRegFilhoArray(
        getEntidadeId('td_website_blog_colunista'),
        getEntidadeId('td_raymbo_columnist_profile'),
        $colunista['id']
    );    

    $retorno['_data'] = array(
        'colunista' => $colunista,
        'coluna'    => $coluna,
        'profile'   => isset($profile[0]) ? $profile[0] : new stdClass
    );