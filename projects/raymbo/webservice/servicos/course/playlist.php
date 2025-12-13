<?php
    $nivel_id = $_data->nivel;
    $criterio = tdc::f();
    $criterio->addFiltro('nivel','=',$nivel_id);
    $aulas = tdc::da('td_raymbo_cursoaula',$criterio);
    $retorno['_data']   = [
        'aulas' => $aulas
    ];