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
$para->td_movimentacao= $de[''];
$para->td_atributo= $de[''];
$para->operador=$de[''];
$para->valor=$de[''];
        
        $para->armazenar();
    }