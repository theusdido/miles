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
$para->visitanteonline= $de[''];
$para->totalvisitas= $de[''];
$para->carrinhosativos= $de[''];
$para->carrinhoabandonados= $de[''];
$para->devolucoesetrocas= $de[''];
$para->produtosesgotados= $de[''];
        
        $para->armazenar();
    }