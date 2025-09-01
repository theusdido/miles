<?php

    $op         = tdc::r('op');
    switch($op){
        case 'betha':
            require PATH_MVC_CONTROLLER . 'integracao/betha/nfse/consulta.php';
        break;
        default:
            $rps        = tdc::r('rps');
            $data       = tdc::r('data');
            $situacao   = tdc::r('situacao');
            $filtro     = tdc::f();

            #$filtro->setPropriedade('limit',10);
            if ($rps != ''){
                $filtro->addFiltro("rpsnumero","=",$rps);
            }

            if ($data != ''){
                $filtro->addFiltro("demis","=",$data);
            }

            if ($situacao != ''){
                $filtro->addFiltro("situacao","=",$situacao);
            }

            $filtro->onlyActive();

            $retorno = array();  
            foreach (tdc::d('td_erp_nfse_nota',$filtro) as $d){
                array_push($retorno,array(
                    "id"            => $d->id,
                    "rpsnumero"     => $d->rpsnumero,
                    "rpsserie"      => $d->rpsserie,
                    "rpstipo"       => $d->rpstipo,
                    "situacao"      => $d->situacao,
                    "tomador"       => tdc::d("td_erp_nfse_tomador",tdc::f("nfse","=",$d->id))[0]->tomarazaosocial
                ));
            }

            echo json_encode($retorno);            
        break;
    }