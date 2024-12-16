<?php
    $customer           = tdc::p('td_ecommerce_cliente',tdc::r('_id'));
    $retorno['data']    = array(
        'id'        => $customer->id,
        'nome'      => $customer->nome,
        'tipo'      => $customer->tipo,
        'documento' => $customer->tipopessoa == 0 ? $customer->cpf : $customer->cnpj,
        'email'     => $customer->email,        
        'telefone'  => $customer->telefone
    );