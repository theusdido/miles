<?php
    $categoria_id       = $_data->categoria;
    $modulos        = tdc::da('td_raymbo_cursomodulo', ['categoria','=',$categoria_id]);
    $instrutores    = tdc::da('td_raymbo_cursoinstrutor');
    $destaques      = array();
    $ultimos         = array();
    $music_production = array();
    $tipos = [];
    $banners = [];

    
    


    if ($categoria_id == 3)
    {

        $destaques_modulos = array_map(function($m){
            return $m['id'];
        }, array_filter( $modulos, function($f){
            global $categoria_id;
            return $f['categoria'] == $categoria_id;
        }));

        $criterio_modulo = tdc::f();
        $criterio_modulo->addFiltro('curso', 'in', $destaques_modulos);

        $criterio = tdc::f();       
        $criterio->add(tdc::f('modulocurso','=',$categoria_id));
        $banners = tdc::dua('td_raymbo_banner_curso', $criterio);
                
        $criterio = tdc::f();
        $criterio->add($criterio_modulo);
        $tipos = tdc::da('td_raymbo_cursotipo',$criterio);
    }

    $retorno['_data'] = array(
        'modulos' => $modulos,
        'instrutores' => $instrutores,
        'tipos' => $tipos,
        'banners' => $banners
    );