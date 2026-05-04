<?php

    if (!isset($_data->professor) || empty($_data->professor)) {
        $retorno['_erro'] = 'O campo professor é obrigatório.';
        return;
    }

    if (!isset($_data->data) || empty($_data->data)) {
        $retorno['_erro'] = 'O campo data é obrigatório.';
        return;
    }

    $_criterio = tdc::f();
    $_criterio->addFiltro('professor','=', $_data->professor);
    $_criterio->addFiltro('data','=', dateToMysqlFormat($_data->data));
    $retorno['_data']   = tdc::da('td_erp_escola_encontro',$_criterio);