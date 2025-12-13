<?php
    $curso_id       = $_data->curso;
    $curso          = tdc::pa('td_raymbo_curso',$curso_id);

    $retorno['_data'] = array(
        'curso' => $curso
    );