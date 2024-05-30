<?php

    $path_file_migracao = PATH_CURRENT_FILE_TEMP . 'website_geral_blog.sql';
    $fp = fopen($path_file_migracao,'w');

    // Limpa a tabela destino
    fwrite($fp,'TRUNCATE TABLE td_website_geral_blog;' . "\n");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM td_website_geral_blog;";
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        $campos     = array(
            'id',
            'inativo',
            'titulo',
            'arquivo',
            'texto',
            'datahora',
            'chamada',
            'produto'
        );

        $valores    = array(
            $de['id'],
            'NULL',
            '"'. $de['titulo'] . '"',
            '"'. $de['arquivo'] . '"',
            '"'. $de['text'] . '"',
            '"'. $de['datahora'] . '"',
            '"'. $de['chamada'] . '"',
            ($de['td_produto']==''?0:$de['td_produto'])
        );
        $insert = 'INSERT INTO td_website_geral_blog ('.implode(',',$campos).') VALUES ('.implode(',',$valores).');' ."\n";
        fwrite($fp,$insert);
    }
    fclose($fp);