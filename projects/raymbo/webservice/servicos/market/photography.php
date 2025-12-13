<?php

    $photography                = tdc::rua('td_raymbo_market_photography',$_data->_id);
    $banners                    = tdc::da('td_raymbo_market_photography_banner_home',tdc::f('photography','=',$photography['id']));
    $categories                 = tdc::da('td_raymbo_market_photography_category');

    $retorno['_data']   = array(
        'photography'       => $photography,
        'banners'           => $banners,
        'categories'        => $categories
    );