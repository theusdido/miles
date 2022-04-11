<?php

    // Limpa a tabela destino
    $conn->exec("TRUNCATE TABLE {$para_entidade};");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM {$de_entidade};";   
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        // Adiciona registros na tabela de destino        
        $para                              = tdc::p($para_entidade);
        $para->projeto                     = $de['td_projeto'];
        $para->empresa                     = $de['td_empresa'];
        $para->atributo                    = $de['td_atributo'];
        $para->usuario                     = $de['td_usuario'];
        $para->inserir                     = $de['inserir'];
        $para->excluir                     = $de['excluir'];
        $para->editar                      = $de['editar'];
        $para->visualizar                  = $de['visualizar'];
        $para->inativo                     = 0;

        $para->armazenar();
        
    }