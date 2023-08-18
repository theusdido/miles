<?php
    switch($op){
        case 'get':
            $retorno    = [];
            $criterio   = tdc::where([
                tdc::f('loja','=',$loja['id']),
                tdc::f('oportunidade','IS',NULL)
            ]);            
            $data_set   = tdc::da('td_ecommerce_oportunidade',$criterio);
            $loja       = tdc::pa('td_ecommerce_loja',$dados['loja']);
            foreach($data_set as $oportunidade){
                $pedido         = tdc::pa('td_ecommerce_pedido', $oportunidade['pedido']);
                $cliente        = tdc::pa('td_ecommerce_cliente',$pedido['cliente']);
                $usuario        = getListaRegFilhoArray(getEntidadeId("ecommerce_cliente"),getEntidadeId("usuario"),$cliente['id'])[0];                
                array_push($retorno,array(
                    'id'                => $oportunidade['id'],
                    'oportunidade'      => $oportunidade,
                    'pedido'            => $pedido,
                    'cliente'           => $cliente,
                    'usuario'           => $usuario,
                    'loja'              => $loja
                ));
            }
            echo json_encode($retorno);
        break;
        case 'load':
            $oportunidade   = tdc::rua('td_ecommerce_oportunidade',$dados['id']);
            tdc::wj($oportunidade);
        break;
    }