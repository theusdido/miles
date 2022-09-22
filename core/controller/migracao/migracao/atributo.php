<?php

    // Limpa a tabela destino
    $conn->exec("TRUNCATE TABLE {$para_entidade};");
    
    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM {$de_entidade};";   
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        // Adiciona registros na tabela de destino
        $para                               = tdc::p($para_entidade);
        $para->entidade                     = $de['td_entidade'];
        $para->nome                         = $de['nome'];
        $para->descricao                    = $de['descricao'];
        $para->tipo                         = $de['tipo']; 
        $para->tamanho                      = $de['tamanho'];
        $para->nulo                         = $de['nulo'];
        $para->omissao                      = $de['omissao'];
        $para->collection                   = $de['collection'];
        $para->atributos                    = $de['atributos'];
        $para->indice                       = $de['indice'];
        $para->autoincrement                = $de['autoincrement'];
        $para->comentario                   = $de['comentario'];
        $para->exibirgradededados           = $de['exibirgradededados'];
        $para->chaveestrangeira             = $de['chaveestrangeira'];
        $para->tipohtml                     = $de['tipohtml'];
        $para->dataretroativa               = $de['dataretroativa'];
        $para->ordem                        = $de['ordem'];
        $para->inicializacao                = $de['inicializacao'];
        $para->readonly                     = $de['readonly'];
        $para->exibirpesquisa               = $de['exibirpesquisa'];
        $para->tipoinicializacao            = $de['tipoinicializacao'];
        $para->atributodependencia          = $de['atributodependencia'];
        $para->labelzerocheckbox            = $de['labelzerocheckbox'];
        $para->labelumcheckbox              = $de['labelumcheckbox'];
        $para->legenda                      = $de['legenda'];
        $para->desabilitar                  = $de['desabilitar'];
        $para->criarsomatoriogradededados   = $de['criarsomotoriogradededados'];
        $para->naoexibircampo               = $de['naoexibircampo'];
        $para->armazenar();
    }