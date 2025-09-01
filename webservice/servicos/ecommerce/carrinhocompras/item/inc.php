<?php
    
    $criteria   = tdc::f();
    $criteria->addFiltro('produto','=',tdc::r('_product_id'));

    foreach(tdc::d('td_ecommerce_carrinhoitem',$criteria) as $item){
        $item->qtde = $item->qtde + 1;
        $item->valortotal = $item->qtde * $item->valor;
        $item->armazenar();
    }

    $retorno['data']                = tdc::da('td_ecommerce_carrinhoitem',$criteria);
    $retorno['status']              = 'success';
    $retorno['msg']                 = 'Itens do carrinho de compras.';
    $retorno['code']                = 0;    