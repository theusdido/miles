<?php
    #tdc::wj(tdc::da('td_website_idioma_lingua'));
    $registro   = tdc::r('registro');
    $atributo   = tdc::a(tdc::r('atributo'));
    $entidade   = tdc::e($atributo->entidade);

    $linguas    = array();
    foreach( tdc::da('td_website_idioma_lingua') as $lingua ){
        $texto = '';
        $traducao = Idioma::Traduzir($entidade->nome,$atributo->nome,$registro,$lingua['id']);
        if (sizeof($traducao) > 0){
            $texto = $traducao[0]['texto'];
        }
        array_push($linguas,array(
            'lingua'    => $lingua,
            'texto'     => $texto
        ));
    }
    tdc::wj( $linguas );