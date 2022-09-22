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
$para->td_projeto` smallint(6) NOT NULL,
$para->nome` varchar(150) NOT NULL,
$para->login= $de[''];
$para->email` varchar(250) NOT NULL,
$para->senha= $de[''];
$para->permitirexclusao` tinyint(4) NOT NULL,
$para->permitirtrocarempresa` tinyint(4) NOT NULL,
$para->td_grupousuario= $de[''];
$para->perfilusuario` tinyint(4) NOT NULL,
$para->td_perfil= $de[''];
$para->fotoperfil` mediumblob,
        
        $para->armazenar();
    }