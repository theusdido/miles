<?php
    $op = isset($dados['op']) ? $dados['op'] : '';
    switch($op){        
        case 'page':
            tdc::wj(array(
                'banner' => tdc::rua('td_bannerinicial')
            ));            
        break;
    }