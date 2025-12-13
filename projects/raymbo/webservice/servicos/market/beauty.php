<?php

    $beauty               = tdc::rua('td_raymbo_market_beauty',$_data->_id);
    $speciality            = tdc::da('td_raymbo_market_beauty_speciality',['beauty','=',$beauty['id']]);
    $services            = tdc::da('td_raymbo_market_beauty_service',['beauty','=',$beauty['id']]);


    $banners = getListaRegFilhoArrayUnico(
        getEntidadeId('td_raymbo_market_beauty'),
        getEntidadeId('td_raymbo_market_beauty_banner_home'),
        $beauty['id']
    );    
    #$banners            = tdc::da('td_raymbo_market_beauty_banner_home',['beauty','=',$beauty['id']]);

    $retorno['_data']   = array(
        'beauty'        => $beauty,
        'banners'       => $banners,
        'services'      => $services,
        'speciality'    => $speciality
    );