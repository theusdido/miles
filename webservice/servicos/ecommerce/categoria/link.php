<?php
    $criterio = tdc::f();
    $criterio->addFiltro("inativo","=",0);
    $criterio->addFiltro('link','=',$_data->categoria);

    $retorno["_data"] = tdc::dua("td_ecommerce_categoria",$criterio);