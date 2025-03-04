<?php
    $criterio = tdc::f();
    $criterio->isTrue('exibirhome');
    $criterio->onlyActive();

    $retorno ['data'] = tdc::da('td_ecommerce_produto',$criterio);