<?php

    $categoria_id       = $_data->categoria;
    $tipo_id            = $_data->tipo;
    $instrutor_id       = $_data->instrutor;
    $termo              = $_data->termo;

    $modulos        = tdc::da('td_raymbo_cursomodulo', ['categoria','=',$categoria_id]);
    $destaques_modulos = array_map(function($m){
        return $m['id'];
    }, array_filter( $modulos, function($f){
        global $categoria_id;
        return $f['categoria'] == $categoria_id;
    }));

    $criterio = tdc::f();
    $criterio->addFiltro('curso','IN',$destaques_modulos);

    if ($tipo_id > 0){
        $criterio->addFiltro('tipo','=',$tipo_id);
    }

    if ($instrutor_id > 0){
        $criterio->addFiltro('instrutor','=',$instrutor_id);
    }
    
    //var_dump($criterio->dump());
    $cursos             = tdc::da('td_raymbo_curso', $criterio);
    $retorno['_data']   = $cursos;