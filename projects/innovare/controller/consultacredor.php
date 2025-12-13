<?php

    switch(tdc::r('op')){
        case 'empresa':
            $coluna_empresa 				= tdClass::Criar("div");
            $coluna_empresa->class 			= "coluna";
            $coluna_empresa->data_ncolunas 	= 3;
            $coluna_empresa->add(Empresa::Filtro());
            $coluna_empresa->mostrar();
        break;
    }