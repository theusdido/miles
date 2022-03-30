<?php    
    $rps        = tdc::r('rps');
    $data       = tdc::r('data');
    $status     = tdc::r('status');
    $filtro     = tdc::f();

    if ($rps != ''){
        $filtro->addFiltro("rpsnumero","=",$rps);
    }

    if ($data != ''){
        $filtro->addFiltro("demis","=",$data);
    }

    if ($status != ''){
        $filtro->addFiltro("status","=",$status);
    }

    $retorno = array();
    foreach (tdc::d('td_erp_nfse_nota',$filtro) as $d){
        array_push($retorno,array(
            "id"            => $d->id,
            "rpsnumero"     => $d->rpsnumero,
            "rpsserie"      => $d->rpsserie,
            "rpstipo"       => $d->rpstipo,
            "status"        => $d->status,
            "tomador"       => tdc::d("td_erp_nfse_tomador",tdc::f("nfse","=",$d->id))[0]->tomarazaosocial
        ));
    }

    echo json_encode($retorno);