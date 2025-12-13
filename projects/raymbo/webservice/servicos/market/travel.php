<?php

    $travel      = tdc::rua('td_raymbo_market_travel',$_data->_id);
    $banners         = tdc::da('td_raymbo_market_travel_banner_home',['travel','=',$travel['id']]);
    $speciality         = tdc::da('td_raymbo_market_travel_speciality',['travel','=',$travel['id']]);
    $services         = tdc::da('td_raymbo_market_travel_service',['travel','=',$travel['id']]);

    $retorno['_data']   = array(
        'travel'        => $travel,
        'banners'       => $banners,
        'speciality'    => $speciality,
        'services'      => $services
    );