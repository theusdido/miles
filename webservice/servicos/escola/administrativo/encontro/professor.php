<?php
    $_criterio = tdc::f();
    $_criterio->addFiltro('professor','=', $_data->professor);
    $_criterio->addFiltro('data','=', dateToMysqlFormat($_data->data));
    $retorno['_data']   = tdc::da('td_erp_escola_encontro',$_criterio);