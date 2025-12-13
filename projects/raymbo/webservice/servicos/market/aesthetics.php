<?php

    $aesthetics                     = tdc::rua('td_raymbo_market_aesthetics',$_data->_id);
    $aesthetics['tratamentos']      = tdc::da('td_raymbo_market_aesthetics_tratament',['aesthetics','=',$aesthetics['id']]);
    $aesthetics['categorias']       = tdc::da('td_raymbo_market_aesthetics_tratament_category');
    $aesthetics['certificados']     = tdc::da('td_raymbo_market_aesthetics_certificate');
    $retorno['_data']               = $aesthetics;