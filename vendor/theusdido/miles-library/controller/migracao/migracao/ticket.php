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
$para->td_projeto=$de[''];
$para->td_tipo= $de[''];
$para->td_prioridade= $de[''];
$para->td_usuario= $de[''];
$para->td_responsavel= $de[''];
$para->titulo=$de[''];
$para->descricao` text NOT NULL,
$para->previsaoentrega` datetime DEFAULT NULL,
$para->datacriacao` datetime NOT NULL,
$para->anexo` varchar(200) DEFAULT NULL,
$para->status` tinyint(4) DEFAULT NULL,
$para->td_cliente= $de[''];

        $para->armazenar();
    }