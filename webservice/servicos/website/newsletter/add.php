<?php
    $email  = tdc::r('email');
    $nome   = tdc::r('nome');
    if ($email == ''){
        $retorno['status']  = 'error';
        $retorno['msg']     = 'O campo e-mail é obrigatório.';
        exit;
    }

    $newsletter         = tdc::p('td_website_geral_newsletter');
    $newsletter->email  = $email;
    $newsletter->nome   = $nome;

    if ($newsletter->armazenar()){
        $retorno['status']  = 'success';
    }
    
    