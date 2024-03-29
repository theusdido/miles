<?php

    $_termo = $_data->_termo;

    $retorno["_data"]['produtos']       = tdc::da('td_ecommerce_produto',tdc::f('nome','%',$_data->_termo));
    $retorno["_data"]['categorias']      = tdc::da('td_ecommerce_categoria',tdc::f('descricao','%',$_data->_termo));
    $retorno["_data"]['subcategorias']   = tdc::da('td_ecommerce_subcategoria',tdc::f('descricao','%',$_data->_termo));