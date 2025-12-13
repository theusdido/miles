<?php

    $restaurant      = tdc::rua('td_raymbo_market_restaurant',$_data->_id);
    $banners         = tdc::da('td_raymbo_market_restaurant_banner_home',['restaurant','=',$restaurant['id']]);
    $categories      = tdc::da('td_raymbo_market_restaurant_category');
    $gallery         = tdc::da('td_raymbo_market_restaurant_gallery',['restaurant','=',$restaurant['id']]);

    $retorno['_data']   = array(
        'restaurant'     => $restaurant,
        'banners'        => $banners,
        'categories'     => $categories,
        'gallery'        => $gallery
    );