<?php

    $events             = tdc::da('td_raymbo_market_event');
    $events_status      = tdc::da('td_raymbo_market_event_status');

    $retorno['_data']   = array(
        'list'      => $events,
        'status'    => $events_status
    );