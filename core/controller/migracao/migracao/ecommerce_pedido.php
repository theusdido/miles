<?php

    // Limpa a tabela destino
    $conn->exec("TRUNCATE TABLE {$para_entidade};");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM {$de_entidade};";   
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
//         // Adiciona registros na tabela de destino        
//         $para                       = tdc::p($para_entidade);
        
// $para->id= $de[''];
// $para->td_projeto=$de[''];
// $para->td_empresa=$de[''];
// $para->inativo` tinyint(1) DEFAULT NULL,
// $para->td_cliente= $de[''];
// $para->datahoraenvio` datetime NOT NULL,
// $para->datahoraretorno` datetime DEFAULT NULL,
// $para->td_carrinhodecompras= $de[''];
// $para->td_status` tinyint(4) NOT NULL,
// $para->td_metodopagamento= $de[''];
// $para->qtdetotalitens= $de[''];
// $para->valortotal` float DEFAULT NULL,
// $para->metodopagamento= $de[''];
// $para->td_representante= $de[''];
// $para->isrepresentante` tinyint(4) DEFAULT NULL,
// $para->valorfrete` float DEFAULT NULL,

        
//         $para->armazenar();
    }