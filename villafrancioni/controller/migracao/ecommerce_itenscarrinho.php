<?php

    $path_file_migracao = PATH_CURRENT_FILE_TEMP . 'ecommerce_carrinhoitem.sql';
    $fp = fopen($path_file_migracao,'w');

    // Limpa a tabela destino
    fwrite($fp,'TRUNCATE TABLE td_ecommerce_carrinhoitem;' . "\n");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM td_ecommerce_itenscarrinho;";
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        $campos     = array(
            'id',
            'inativo',
            'carrinho',
            'produto',
            'qtde',
            'descricao',
            'imgsrc',
            'valor',
            'variacaopeso',
            'variacaotamanho',
            'qtdetotalitens',
            'valortotal'
        );

        $valores    = array(
            $de['id'],
            'NULL',
            $de['td_carrinho'],
            $de['td_produto'],
            ($de['qtde']==''?0:$de['qtde']),
            '"'. $de['descricao'] . '"',
            '"'. $de['imgsrc'] . '"',
            ($de['valor']==''?0:$de['valor']),
            'NULL',
            'NULL',
            'NULL',
            ($de['valortotal']==''?0:$de['valortotal'])
        );
        $insert = 'INSERT INTO td_ecommerce_carrinhoitem ('.implode(',',$campos).') VALUES ('.implode(',',$valores).');' ."\n";
        fwrite($fp,$insert);
    }
    fclose($fp);