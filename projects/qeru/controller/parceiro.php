<?php
    switch($op){
        case 'all':
            $filtro = tdc::f();
            $filtro->onlyActive();
            tdc::wj(tdc::da('td_website_geral_quemsomosparceiros',$filtro));
        break;
    }