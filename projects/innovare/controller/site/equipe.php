<?php

    $ft_socio = tdc::f('cargo','=',1);
    $ft_socio->asc('nome');

    $ft_colaborador = tdc::f('cargo','=',2);
    $ft_colaborador->asc('nome');

    tdc::wj([
        'socios'        => tdc::da('website_geral_equipe',$ft_socio),
        'colaboradores' => tdc::da('website_geral_equipe',$ft_colaborador)
    ]);