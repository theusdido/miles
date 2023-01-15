<?php
    switch(tdc::r('_op')){
        case 'change':
            $customer           = tdc::p('td_ecommerce_cliente',tdc::r('_customer_id'));
            $customer->senha    = md5(tdc::r('_pwd'));
            if ($customer->armazenar()){
                $retorno['status'] = 'success';
            }else{
                $retorno['status'] = 'error';
            }            
        break;
    }