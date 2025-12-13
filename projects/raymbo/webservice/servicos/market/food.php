<?php

    $food               = tdc::rua('td_raymbo_market_food',$_data->_id);
    $products           = tdc::da('td_raymbo_market_food_product');
    $banners            = tdc::da('td_raymbo_market_food_banner_home',['food','=',$food['id']]);

    $retorno['_data']   = array(
        'food'        => $food,
        'produtos'    => $products,
        'banners'     => $banners
    );