<?php

    $letting                = tdc::rua('td_raymbo_market_letting',$_data->_id);
    $category               = tdc::da('td_raymbo_market_letting_category');
    $hosting                = tdc::da('td_raymbo_market_letting_hosting',['letting','=',$_data->_id]);

    $retorno['_data']   = array(
        'letting'       => $letting,
        'categories'    => $category,
        'hosting'       => $hosting
    );