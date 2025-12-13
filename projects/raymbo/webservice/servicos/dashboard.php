<?php
    
    $cliente_id = $_data->cliente;
    $ft_cliente = tdc::f('cliente','=',$cliente_id);
    $cursos_cliente = tdc::da('td_raymbo_cursocliente',$ft_cliente);
    $conteudos = [];

    if (sizeof($cursos_cliente) > 0){
        $ids = [];
        foreach($cursos_cliente as $cc){
            $ids[] = $cc['curso'];
        }
        $ft_doc     = tdc::f();
        $ft_doc->addFiltro('id','IN',$ids);
        $conteudos = tdc::da('td_raymbo_cursoconteudo', $ft_doc);
    }
    
    $retorno = [
        'cursos_cliente' => $cursos_cliente,
        'cursos' => $conteudos
    ];    
    