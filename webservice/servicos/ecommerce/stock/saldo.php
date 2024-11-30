<?php
    switch($_op){
        case 'get':
            $produto_id         = tdc::r('produto');
            if ($produto_id == ''){
                $retorno['status']  = 'error';
                $retorno['msg']     = 'Parametro Produto não foi encontrado!';
            }else{
                $retorno['status']  = 'success';
                $retorno['saldo']   = tdEcommerceProduto::getSaldo($produto_id);
            }
        break;
    }