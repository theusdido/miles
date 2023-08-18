<?php
    $op = isset($dados['op']) ? $dados['op'] : '';
    switch($op){        
        case 'usuario':
            $_perfil = 1;
        break;
        case 'parceiro':
            $_perfil = 2;
        break;
        case 'vendedor':
            $_perfil = 3;
        break;                
    }

    tdc::wj(tdc::pa('td_website_geral_perfil',1));