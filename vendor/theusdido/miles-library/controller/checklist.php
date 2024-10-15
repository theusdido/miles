<?php
    switch(tdc::r('op')){
        case 'load':
            tdc::wj( tdc::da( tdc::r('entidade') ) );
        break;
        case 'excluir':
            $_entidade_pai      = tdc::r('entidadepai');
            $_entidade_filho    = tdc::r('entidadefilho');
            $_reg_pai           = tdc::r('regpai');
            $_reg_filho         = tdc::r('regfilho');

            $criterio           = tdc::f();
            $criterio->addFiltro('entidadepai','=',$_entidade_pai);
            $criterio->addFiltro('entidadefilho','=',$_entidade_filho);
            $criterio->addFiltro('regpai','=',$_reg_pai);
            $criterio->addFiltro('regfilho','=',$_reg_filho);

            tdc::de(LISTA,$criterio);
        break;
    }