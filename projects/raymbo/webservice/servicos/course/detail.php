<?php

    $curso_id       = $_data->curso;
    $conteudos      = tdc::pa('td_raymbo_cursoconteudo', $curso_id);

    $retorno['_data'] = array(
        'curso' => $conteudos
    );