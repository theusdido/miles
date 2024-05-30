<?php
    foreach(tdc::d(ATRIBUTO,tdc::f('entidade','=',tdc::r('_entidade'))) as $atributo)
    {
        reordenarAtributo($atributo->id);
    }