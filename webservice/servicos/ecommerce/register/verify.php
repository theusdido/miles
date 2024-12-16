<?php
    switch($_op){
        case 'email':
            if (!isemail($_value))
            {
                $retorno['status']  = 'error';
                $retorno['msg']     = 'E-Mail informado é inválido';
            }

            if (tdc::c('td_ecommerce_cliente',tdc::f('email','=',$_value)) > 0)
            {
                $retorno['status']  = 'error';
                $retorno['msg']     = 'Já existe um usuário com este e-mail';
            }
        break;

        case 'cnpj':
            if (!isCNPJ($_value)){
                $retorno['status']  = 'error';
                $retorno['msg']     = 'CNPJ informado é inválido';
            }

            if (tdc::c('td_ecommerce_cliente',tdc::f('cnpj','=',$_value)) > 0)
            {
                $retorno['status']  = 'error';
                $retorno['msg']     = 'Já existe um usuário com este CNPJ';
            }
        break;
    
        case 'cpf':
            if (!isCPF($_value)){
                $retorno['status']  = 'error';
                $retorno['msg']     = 'CPF informado é inválido';
            }

            if (tdc::c('td_ecommerce_cliente',tdc::f('cpf','=',$_value)) > 0)
            {
                $retorno['status']  = 'error';
                $retorno['msg']     = 'Já existe um usuário com este CPF';
            }
        break;
    }