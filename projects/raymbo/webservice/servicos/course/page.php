<?php
    $curso_id       = $_data->curso;
    $curso          = tdc::pa('td_raymbo_curso',$curso_id);
    $modulos        = tdc::da('td_raymbo_cursomodulo', ['curso','=',$curso_id]);
    $instrutores    = tdc::da('td_raymbo_cursoinstrutor');
    $destaques      = array();
    $ultimos         = array();
    $music_production = array();
    $estilos = [];

    if ($curso_id == 3)
    {

        $destaques_modulos = array_map(function($m){
            return $m['id'];
        }, array_filter( $modulos, function($f){
            global $curso_id;
            return $f['curso'] == $curso_id;
        }));

        $criterio_modulo = tdc::f();
        $criterio_modulo->addFiltro('curso', 'in', $destaques_modulos);

        $criterio = tdc::f();       
        $criterio->add(tdc::f('modulocurso','=',$curso_id));
        $banners = tdc::dua('td_raymbo_banner_curso', $criterio);

        $criterio = tdc::f();
        $criterio->isFalse('is_destaque');
        $criterio->add($criterio_modulo);
        $criterio->limit(6);
        $ultimos = tdc::da('td_raymbo_cursoconteudo', $criterio);   
                
        $criterio = tdc::f();
        $criterio->add($criterio_modulo);
        $estilos = tdc::da('td_raymbo_cursoestilo',$criterio);
    }

    $retorno['_data'] = array(
        'curso' => $curso,
        'modulos' => $modulos,
        'instrutores' => $instrutores,
        'banners' => $banners,
        'ultimos' => $ultimos,
        'estilos' => $estilos
    );