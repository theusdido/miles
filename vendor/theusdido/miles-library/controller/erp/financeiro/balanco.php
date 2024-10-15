<?php
    switch($_op){
        case 'all':
            $criterio = tdc::f();
            $criterio->desc('datalancamento');
            $criterio->filtro("DATE_FORMAT(datalancamento,'%Y%m')",tdc::r('referencia'));
            tdc::wj (tdc::da('td_erp_financeiro_caixa',$criterio) );
        break;
    }