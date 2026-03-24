<?php
    switch($_op){
        case 'rodape':
            $_entidade = 'td_website_geral_menurodape';
        break;
        case 'principal':
        default:
            $_entidade = 'td_website_geral_menuprincipal';
    }
    
    $_criterio  = tdc::f();
    $_criterio->onlyActive();
    $_criterio->order('ordem');

    try{
        if (IS_REDIS){
            #$redis = new Redis();
            #$redis->connect('127.0.0.1', 6379);        
            #var_dump($redis->hGet($_entidade));
            #$retorno['data'] = json_decode($redis->get($_entidade . ":all"));
            $retorno['data'] = $_redis->getAll($_entidade);
        }else{
            $retorno['data'] = tdc::da($_entidade,$_criterio);
        }
    }catch(Exception $e){
        $retorno['data'] = [];
    }