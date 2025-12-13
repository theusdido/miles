<?php

    $printservices      = tdc::rua('td_raymbo_market_printservices',$_data->_id);
    $banners            = tdc::da('td_raymbo_market_printservices_banner_home',tdc::f('printservices','=',$printservices['id']));
    $services           = tdc::da('td_raymbo_market_printservices_service',tdc::f('printservices','=',$printservices['id']));

    $retorno['_data']   = array(
        'printservices'     => $printservices,
        'banners'           => $banners,
        'services'          => $services
    );