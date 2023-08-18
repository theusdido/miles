<?php
    switch($op){
        case 'setar':
            $loja       = $dados['loja'];
            $cliente    = $dados['cliente'];
            $nota       = $dados['avaliacao'];

            $filtro     = tdc::f();
            $filtro->addFiltro('cliente','=',$cliente);
            $filtro->addFiltro('loja','=',$loja);
            $ds_avaliacao = tdc::d('td_avaliacao',$filtro);
            if (sizeof($ds_avaliacao) > 0){
                $avaliacao = $ds_avaliacao[0];
            }else{
                $avaliacao          = tdc::p('td_avaliacao');
                $avaliacao->cliente = $cliente;
                $avaliacao->loja    = $loja;
            }

            $avaliacao->nota        = $nota;
            $avaliacao->armazenar();
        break;
    }