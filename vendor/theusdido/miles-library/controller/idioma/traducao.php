<?php
    switch($_op){
        case 'add':
            $traducoes  = tdc::r('traducoes');
            $atributo   = tdc::r('atributo');
            $registro   = tdc::r('registro');
        
            foreach($traducoes as $t){
        
                $traducao_  = $t['traducao'];
                $lingua     = $t['lingua'];
        
                // Verifica se já tem tradução
                $criterio = tdc::f();
                $criterio->addFiltro('atributo','=',$atributo);
                $criterio->addFiltro('registro','=',$registro);
                $criterio->addFiltro('lingua','=',$lingua);
        
                $traducao               = tdc::p('td_website_idioma_traducao')->newNotExistsCriteria($criterio);
                $traducao->texto        = $traducao_;
                $traducao->lingua       = $lingua;
                $traducao->atributo     = $atributo;
                $traducao->registro     = $registro;
                $traducao->salvar();
            }
        break;
        case 'get':
            $entidade   = tdc::r('entidade');
            $campo      = tdc::r('campo');
            $registro   = tdc::r('registro');
            $lingua     = tdc::r('lingua');

            tdc::wj( Idioma::Traduzir($entidade,$campo,$registro,$lingua) );
        break;
    }