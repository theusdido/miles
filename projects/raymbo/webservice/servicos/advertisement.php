<?php
    switch($_data->_op){
        case 'list':
            $criterio = tdc::f();
            $criterio->addFiltro('REPLACE(description," ","")','=',$_data->_fixo);
            $advertisement_category = tdc::dua('td_advertisement_category',$criterio);

            $criterio = tdc::f();
            $criterio->addFiltro('category','=',$advertisement_category['id']);
            $retorno['_data'] = tdc::da('td_advertisement',$criterio);
        break;
        case 'category':
            $criterio = tdc::f();
            $criterio->addFiltro('REPLACE(description," ","")','=',$_data->_fixo);
            $advertisement_category = tdc::dua('td_advertisement_category',$criterio);
            $retorno['_data'] = $advertisement_category;
        break;
        case 'publicity':
            $retorno['_data'] = tdc::rua('td_raymbo_publicity_advertisement');
        break;
    }