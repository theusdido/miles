<?php
    $criterio = tdc::f();
    $criterio->addFiltro("inativo","=",0);
    $criterio->addFiltro('link','=',$_data->categoria);

    $categoria                          = tdc::dua("td_ecommerce_categoria",$criterio);
    $retorno["_data"]                   = $categoria;
    $retorno["_data"]['categoria']      = $categoria['id'];
    $retorno["_data"]['portifolio']     = tdc::dua('td_website_geral_portifolio',tdc::f('categoria','=',$categoria['id']));
    $retorno["_data"]['subcategoria']   = tdc::da('td_ecommerce_subcategoria',tdc::f('categoria','=',$categoria['id']));