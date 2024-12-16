<?php
    $unidade_curricular = tdc::r('unidade_curricular');

    $criterio           = tdc::f();
    $criterio->filtro('unidadecurricular',$unidade_curricular);
    
    tdc::wj(tdc::da('td_erp_escola_avaliacao',$criterio));