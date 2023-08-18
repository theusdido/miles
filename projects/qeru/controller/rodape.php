<?php
    $op = isset($dados['op']) ? $dados['op'] : '';
    switch($op){        
        case 'redessociais':
            tdc::wj(array(
                'redessociais' => tdc::da('td_website_geral_redessociais')
            ));            
        break;
    }