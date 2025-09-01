<?php

    $_code = tdc::r('_code');
    $criterio = tdc::f();
    $criterio->addFiltro('codigo','%',$_code);

    $cupom_id = 0;
    $cupom = tdc::da('td_ecommerce_cupomdesconto',$criterio);
    if (count($cupom) > 0 && $_code != ''){
        $cupom = $cupom[0];
        if (strtotime($cupom['datahoravalidade']) >= strtotime(date('Y-m-d H:i:s'))){            
            $cupom_id = $cupom['id'];
            $retorno['status'] = 'success';
            $retorno['_data'] = $cupom;
        }else{
            $retorno['status'] = 'expired';
        }
    }else{
        $retorno['status'] = 'notfound';
    }

    $ecommerce_carrinho->setCupomDesconto($cupom_id);