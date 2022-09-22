<?php

    $path_file_migracao = PATH_CURRENT_FILE_TEMP . 'ecommerce_pedidoitem.sql';
    $fp = fopen($path_file_migracao,'w');

    // Limpa a tabela destino
    fwrite($fp,'TRUNCATE TABLE td_ecommerce_pedidoitem;' . "\n");

    // Seleciona os dados da tabela de origem
    $sql        = '
        SELECT 
            i.id,
            i.inativo,
            i.td_pedido,
            i.td_produto,
            i.qtde,
            i.descricao,
            i.valor,
            ( SELECT t.descricao FROM td_ecommerce_tamanhoproduto t WHERE t.id = c.td_produto ) tamanho,
            ( SELECT 
                ( SELECT p.nome FROM td_ecommerce_produto p WHERE p.id = t.td_produto )
            FROM td_ecommerce_tamanhoproduto t WHERE t.id = c.td_produto ) produtonome,
            ( SELECT 
                ( SELECT p.referencia FROM td_ecommerce_produto p WHERE p.id = t.td_produto )
            FROM td_ecommerce_tamanhoproduto t WHERE t.id = c.td_produto ) referencia    
            #variacao
        FROM td_ecommerce_itenspedido i
        INNER JOIN td_ecommerce_itenscarrinho c ON i.td_produto = c.id;
    ';
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        $campos     = array(
            'id',
            'inativo',
            'pedido',
            'produto',
            'qtde',
            'descricao',
            'valor',
            'produtonome',
            'referencia',
            'tamanho',
            'variacao'
        );

        $valores    = array(
            $de['id'],
            'NULL',
            $de['td_pedido'],
            $de['td_produto'],
            ($de['qtde']==''?0:$de['qtde']),
            '"'. $de['descricao'] . '"',
            ($de['valor']==''?0:$de['valor']),
            '"'. $de['produtonome'] . '"',
            '"'. $de['referencia'] . '"',
            '"'. str_replace('"','',$de['tamanho']) . '"',
            'NULL'
        );
        $insert = 'INSERT INTO td_ecommerce_pedidoitem ('.implode(',',$campos).') VALUES ('.implode(',',$valores).');' ."\n";
        fwrite($fp,$insert);
    }
    fclose($fp);