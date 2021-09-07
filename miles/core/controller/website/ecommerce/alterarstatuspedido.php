<?php

$pedido = tdc::p("td_ecommerce_pedido",tdc::r("pedido"));
$pedido->status = tdc::r("status");
$pedido->armazenar();
echo 1;	