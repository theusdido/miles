<?php
    $id         = $_data->id;
    $nome 		= $_data->nome;
    $documento 	= $_data->documento;
    $telefone 	= $_data->telefone;

    if ($nome == "" || $documento == "" || $telefone == ""){
        echo 'Todos os campos são obrigatórios';
        return false;
    }

    $customer           = tdc::p('td_ecommerce_cliente',$id);
    $customer->nome     = $nome;
    $customer->telefone = $telefone;
    if ($_data->tipo == 0) $customer->cpf = $documento;
    else $customer->cnpj = $documento;
    
    if ($customer->armazenar()){
        $retorno['status'] = 'success';
    }else{
        $retorno['status'] = 'error';
    }