<?php

    $proprietario   = tdc::r('proprietario');
    $data           = tdc::r('data');
    $situacao       = tdc::r('situacao');
    $filtro         = tdc::f();

    if ($proprietario != ''){
        if (is_numeric($proprietario)){
            $filtro->addFiltro("locador_codigo","=",$proprietario);
        }else{
            $filtro->addFiltro("locador_nome","%",$proprietario);
        }
    }

    if ($data != ''){
        $filtro->addFiltro("data_emissao","=",$data);
    }

    if ($situacao != ''){
        
        if ($situacao == 0){
            $ft = tdc::f();
            $ft->addFiltro("enviada","IS", NULL);
            $ft->addFiltro("enviada","=",$situacao,OU);
            $filtro->add($ft);                    
        }else{
            $filtro->addFiltro("enviada","=",$situacao);
        }
    }

    $filtro->onlyActive();

    $retorno = array();  
    foreach (tdc::d('td_demonstrativo',$filtro) as $d){
        $locador = tdc::du('td_demonstrativo_locador',tdc::f("demonstrativo","=",$d->id));
        array_push($retorno,array(
            "id"            => $d->id,
            "enviada"       => $d->enviada == 1 ? 'Enviada' : 'Não Enviada',
            "locador"       => $locador->codigo . ' - ' . $locador->nome,
            'dataemissao'   => $d->data_emissao_dateformatted,
            'email'         => $locador->email,
            'link'          => $d->link
        ));
    }

    echo json_encode($retorno);