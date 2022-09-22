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
$para->td_empresa=$de[''];
$para->td_entidade= $de[''];
$para->td_usuario= $de[''];
$para->inserir` tinyint(4) NOT NULL,
$para->excluir` tinyint(4) NOT NULL,
$para->editar` tinyint(4) NOT NULL,
$para->visualizar` tinyint(4) NOT NULL,
        
        $para->armazenar();
    }