<?php

    // Limpa a tabela destino
    $conn->exec("TRUNCATE TABLE {$para_entidade};");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM {$de_entidade};";   
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        // Adiciona registros na tabela de destino        
        $para                                   = tdc::p($para_entidade);
        $para->id                               = $de['id'];
        $para->projeto                          = $de['td_projeto'];
        $para->empresa                          = $de['td_empresa'];
        $para->urlupload                        = $de['urlupload'];
        $para->urlrequisicoes                   = $de['urlrequisicoes'];
        $para->urlsaveform                      = $de['urlsaveform'];
        $para->urlloadform                      = $de['urlloadform'];
        $para->urlpesquisafiltro                = $de['urlpesquisafiltro'];
        $para->urlenderecofiltro                = $de['urlenderecofiltro'];
        $para->urlexcluirregistros              = $de['urlexcluirregistros'];
        $para->urlinicializacao                 = $de['urlinicializacao'];
        $para->urlloading                       = $de['urlloading'];
        $para->urlloadgradededados              = $de['urlloadgradededados'];
        $para->urlrelatorio                     = $de['urlrelatorio'];
        $para->urlmenu                          = $de['urlmenu'];
        $para->bancodados                       = $de['bancodados'];
        $para->linguagemprogramacao             = $de['linguagemprogramacao'];
        $para->pathfileupload                   = $de['pathfileupload'];
        $para->pathfileuploadtemp               = $de['pathfileuploadtemp'];
        $para->testecharset                     = $de['testecharset'];
        $para->tipogradedados                   = $de['tipogradedados'];
        $para->inativo                          = $de['inativo'];
        $para->urluploadform                    = $de['urluploadform'];
        $para->armazenar();
    }