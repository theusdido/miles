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
        
$para->id` int(11) NOT NULL AUTO_INCREMENT,
$para->td_projeto=$de[''];
$para->td_empresa=$de[''];
$para->td_menu= $de[''];
$para->td_usuario= $de[''];
$para->permissao` tinyint(4) NOT NULL,
        
        $para->armazenar();
    }