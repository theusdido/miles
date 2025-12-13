<?php

    $event              = tdc::rua('td_raymbo_market_event',$_data->_id);
    $events_status      = tdc::da('td_raymbo_market_event_status');

    $retorno['_data']   = array(
        'evento'        => $event,
        'status'        => $events_status
    );