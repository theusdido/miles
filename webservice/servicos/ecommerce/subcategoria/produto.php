
<?php

    $subcategoria                       = tdc::dua('td_ecommerce_subcategoria',tdc::f('link','=',$_data->subcategoria));

    $criterio = tdc::f();
    $criterio->addFiltro("inativo","=",0);
    $criterio->addFiltro('subcategoria','=',$subcategoria['id']);


    $retorno["_data"]['produtos']       = tdc::da("td_ecommerce_produto",$criterio);
    $retorno["_data"]['subcategoria']   = $subcategoria;