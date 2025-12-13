<?php
    $aula_id    = $_data->aula;

    $aula = tdc::rua('td_raymbo_cursoaula', $aula_id);
    $retorno['_data']   = [
        'aula' => $aula
    ];