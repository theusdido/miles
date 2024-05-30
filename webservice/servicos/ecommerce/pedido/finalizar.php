<?php
    $pedido = tdc::p("td_ecommerce_pedido",(int)$dados["pedido"]);
    $pedido->isfinalizado = 1;
    $pedido->armazenar();

    $retorno["status"] = "success";