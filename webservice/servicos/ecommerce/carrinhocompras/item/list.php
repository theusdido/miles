<?php
    $criteria   = tdc::f();
    $criteria->addFiltro('carrinho','=',tdc::r('_carrinho_id'));

    $retorno['data']                = tdc::da('td_ecommerce_carrinhoitem',$criteria);
    $retorno['status']              = 'success';
    $retorno['msg']                 = 'Itens do carrinho de compras.';
    $retorno['code']                = 0;