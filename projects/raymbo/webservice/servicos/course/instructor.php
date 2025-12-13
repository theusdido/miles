<?php
    $instrutor_id           = $_data->instrutor;
    $cursos             = tdc::da('td_raymbo_cursoconteudo', ['instrutor','=',$instrutor_id]);
    $retorno['_data']   = array(
        'instrutor' => tdc::pa('td_raymbo_cursoinstrutor', $instrutor_id),
        'cursos'   => $cursos
    );