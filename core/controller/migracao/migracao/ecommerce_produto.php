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
$para->nome=$de[''];
$para->preco` float DEFAULT NULL,
$para->exibirpreco` tinyint(4) NOT NULL,
$para->exibirhome` tinyint(4) NOT NULL,
$para->inativo` tinyint(4) NOT NULL,
$para->descricao` text,
$para->imagemprincipal` text,
$para->imagemextra1` text,
$para->imagemextra2` text,
$para->imagemextra3` text,
$para->peso` float DEFAULT NULL,
$para->comprimento` float DEFAULT NULL,
$para->altura` float DEFAULT NULL,
$para->largura` float DEFAULT NULL,
$para->diametro` float DEFAULT NULL,
$para->td_categoria= $de[''];
$para->referencia= $de[''];
$para->descricaolonga` text,
$para->informacoesadicionais` text,
$para->imagemextra4` text,
$para->imagemextra5` text,
$para->imagemextra6` text,
$para->imagemextra7` text,
$para->imagemextra8` text,
$para->imagemextra9` text,
$para->imagemextra10` text,
$para->imagemextra11` text,
$para->td_subcategoria=$de[''];

        
        $para->armazenar();
    }