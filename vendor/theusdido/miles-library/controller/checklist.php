<?php
    switch(tdc::r('op')){
        case 'load':
            $criterio = tdc::f();
            $criterio->limit(10,tdc::r('inicial'));
            $criterio->desc('id');

            tdc::wj( tdc::da(tdc::r('entidade'), $criterio) );
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

			// Atualiza entidade pai no Firebase
			$_entidade_pai_obj 	= tdc::e($_entidade_pai);
			$_pai 				= tdc::p($_entidade_pai_obj->nome,$_reg_pai);
			$_pai->armazenar();
        break;
    }