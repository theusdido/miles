<?php

    // Limpa a tabela destino
    $conn->exec("TRUNCATE TABLE {$para_entidade};");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM {$de_entidade};";   
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        // Adiciona registros na tabela de destino        
        $para                       = tdc::p($para_entidade);
        
$para->id= $de[''];
$para->nome=$de[''];
$para->email` varchar(120) DEFAULT NULL,
$para->senha= $de[''];
$para->telefone` varchar(35) DEFAULT NULL,
$para->nomefantasia` varchar(200) DEFAULT NULL,
$para->cnpj` varchar(18) DEFAULT NULL,
$para->inscricao= $de[''];
$para->td_situacaotributaria` smallint(6) NOT NULL,
$para->telefoneextra= $de[''];
$para->inativo` tinyint(1) NOT NULL,
$para->observacao` varchar(1000) DEFAULT NULL,
$para->cpf` varchar(14) DEFAULT NULL,
        
        $para->armazenar();
    }