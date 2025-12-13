<?php
    $curso_id           = $_data->curso;
    $cursos             = tdc::da('td_raymbo_cursocategoria', ['curso','=',$curso_id]);
    $retorno['_data']   = $cursos;